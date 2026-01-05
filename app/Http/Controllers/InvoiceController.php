<?php
namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Services\InvoiceService;
use App\Services\SubscriptionService;
use App\Models\Pricing;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Invoice; // Assuming you have an Invoice model
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService,
        protected SubscriptionService $subscriptionService
    ) {
    }

    public function index()
    {
        $invoices = Invoice::with(['user', 'details.course'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->through(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'user' => [
                        'id' => $invoice->user?->id,
                        'name' => $invoice->user?->name,
                        'email' => $invoice->user?->email,
                    ],
                    'details' => $invoice->details,
                    'amount' => $invoice->amount,
                    'payment_method' => $invoice->payment_method,
                    'payment_status' => $invoice->payment_status,
                    'transaction_id' => $invoice->transaction_id,
                    'billing_month' => $invoice->billing_month,
                    'status' => $invoice->status,
                ];
            });

        return Inertia::render('Invoice/InvoiceList', [
            'invoices' => $invoices,
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $plans = $this->getPlanOptions();
        return Inertia::render('Invoice/InvoiceForm', [
            'users' => $users,
            'plans' => $plans,
        ]);
    }

    public function store(InvoiceRequest $request)
    {
        $payload = $this->prepareInvoicePayload($request);
        $this->invoiceService->create($payload);
        return redirect('/invoices')->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        $invoice = $this->invoiceService->getById($id);
        return Inertia::render('Invoice/InvoiceShow', [
            'invoice' => $invoice
        ]);
    }

    public function edit($id)
    {
        $invoice = $this->invoiceService->getById($id);
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $plans = $this->getPlanOptions();
        return Inertia::render('Invoice/InvoiceForm', [
            'invoice' => $invoice,
            'users' => $users,
            'plans' => $plans,
        ]); // Assuming InvoiceForm.vue can handle existing data for editing
    }

    public function update(InvoiceRequest $request, $id)
    {
        $payload = $this->prepareInvoicePayload($request);
        $this->invoiceService->update($id, $payload);
        return redirect('/invoices')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id) : JsonResponse // Kept as JsonResponse for AJAX delete
    {
        $this->invoiceService->delete($id);
        // Consider returning a redirect to index for non-AJAX or a specific success message for AJAX.
        return response()->json(['message' => 'Invoice deleted successfully']);
    }

    public function storeFromPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.5',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
            'price' => 'required|numeric|min:0.5',
            'billing_month' => 'nullable|date',
            'plan' => 'nullable|string',
            'billing_cycle' => 'nullable|in:monthly,yearly',
            'notes' => 'nullable|string',
            'invoice_id' => 'nullable|exists:invoices,id',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $billingMonth = isset($validated['billing_month'])
            ? Carbon::parse($validated['billing_month'])->startOfMonth()
            : now()->startOfMonth();
        $chargedTotal = $validated['amount'];
        if ($chargedTotal > 100) {
            $chargedTotal = $chargedTotal / 100;
        }
        $noteSuffix = 'Charged total: $'.number_format($chargedTotal, 2);

        DB::beginTransaction();
        try {
            if (!empty($validated['invoice_id'])) {
                $invoice = Invoice::where('id', $validated['invoice_id'])
                    ->where('user_id', $user->id)
                    ->first();
            } else {
                $invoice = null;
            }

            if (!$invoice) {
                $invoice = Invoice::where('transaction_id', $validated['transaction_id'])->first();
            }

            if (!$invoice) {
                $invoice = Invoice::where('user_id', $user->id)
                    ->whereDate('billing_month', $billingMonth->toDateString())
                    ->first();
            }

            if (!$invoice) {
                $invoice = $this->subscriptionService->createMonthlyInvoice($user, $billingMonth, [
                    'amount' => $validated['price'],
                    'plan' => $validated['plan'] ?? $user->invoices()->latest()->value('plan'),
                    'billing_cycle' => $validated['billing_cycle'] ?? 'monthly',
                ]);
            }

            if (!$invoice) {
                throw new \RuntimeException('Unable to resolve invoice for payment.');
            }

            $invoice = $this->subscriptionService->markPaid($invoice, [
                'transaction_id' => $validated['transaction_id'],
                'paid_at' => now(),
                'notes' => trim(($validated['notes'] ?? '').' '.$noteSuffix),
                'payment_method' => $validated['payment_method'] ?? 'card',
            ]);

            if (!empty($validated['course_id'])) {
                $invoice->details()->firstOrCreate(
                    ['course_id' => $validated['course_id']],
                    ['price' => $validated['price']]
                );
            }

            if (config('subscription.catch_up_strategy') === 'full') {
                Invoice::where('user_id', $user->id)
                    ->where('status', 'unpaid')
                    ->where('id', '!=', $invoice->id)
                    ->orderBy('billing_month')
                    ->get()
                    ->each(function (Invoice $overdue) use ($validated) {
                        $this->subscriptionService->markPaid($overdue, [
                            'transaction_id' => $validated['transaction_id'],
                            'paid_at' => now(),
                            'notes' => 'Auto catch-up payment',
                        ]);
                    });
            }

            DB::commit();

            return response()->json(['message' => 'Invoice recorded successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Invoice creation failed: '.$e->getMessage());
            return response()->json(['message' => 'Failed to create invoice.'], 500);
        }
    }

    public function portal()
    {
        $user = Auth::user();
        $perPage = 10;
        $query = $user?->invoices()
            ->with('details.course')
            ->orderByDesc('billing_month')
            ->orderByDesc('created_at');

        $paginated = $query->paginate($perPage);

        $service = app(InvoiceService::class);
        foreach ($paginated->items() as $invoice) {
            $service->normalizeInvoice($invoice);
        }

        return Inertia::render('Invoice/InvoicePortal', [
            'invoices' => $paginated,
            'subscriptionStatus' => $user?->subscriptionStatus,
        ]);
    }

    protected function getPlanOptions(): array
    {
        return Pricing::query()
            ->get()
            ->groupBy('type')
            ->map(function ($items) {
                return $items->map(function ($pricing) {
                    return [
                        'label' => ucfirst(strtolower($pricing->plan_name)),
                        'value' => strtolower($pricing->plan_name),
                        'price' => (float) $pricing->price,
                    ];
                })->values();
            })
            ->toArray();
    }

    protected function prepareInvoicePayload(Request $request): array
    {
        $validated = $request->validated();

        $planValue = strtolower($validated['plan']);
        $cycle = $validated['billing_cycle'];

        $pricing = Pricing::whereRaw('LOWER(plan_name) = ?', [$planValue])
            ->where('type', $cycle)
            ->first();

        $amount = $pricing?->price ?? config('subscription.default_amount');

        $dueDate = !empty($validated['due_date']) ? Carbon::parse($validated['due_date']) : null;
        $billingMonth = $dueDate ? $dueDate->copy()->startOfMonth() : now()->startOfMonth();

        return array_merge($validated, [
            'plan' => $planValue,
            'billing_cycle' => $cycle,
            'amount' => $amount,
            'billing_month' => $billingMonth->toDateString(),
            'due_date' => $dueDate ? $dueDate->toDateString() : null,
            'payment_method' => $validated['payment_method'] ?? 'stripe',
        ]);
    }
}

