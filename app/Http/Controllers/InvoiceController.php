<?php
namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Invoice; // Assuming you have an Invoice model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function index()
    {
        // Fetch invoices using the service, or directly if the service method is not suitable for Inertia response
        $invoices = $this->invoiceService->getAll(); // Adjust if necessary
        return Inertia::render('Invoice/InvoiceList', [
            'invoices' => $invoices
        ]);
    }

    public function create()
    {
        return Inertia::render('Invoice/InvoiceForm'); // Assuming you'll create an InvoiceForm.vue
    }

    public function store(InvoiceRequest $request)
    {
        $this->invoiceService->create($request->validated());
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
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
        return Inertia::render('Invoice/InvoiceForm', [
            'invoice' => $invoice
        ]); // Assuming InvoiceForm.vue can handle existing data for editing
    }

    public function update(InvoiceRequest $request, $id)
    {
        $this->invoiceService->update($id, $request->validated());
        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id) : JsonResponse // Kept as JsonResponse for AJAX delete
    {
        $this->invoiceService->delete($id);
        // Consider returning a redirect to index for non-AJAX or a specific success message for AJAX.
        return response()->json(['message' => 'Invoice deleted successfully']);
    }

    public function storeFromPayment(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'price' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $invoice = $this->invoiceService->create([
                'user_id' => Auth::id(),
                'amount' => $validatedData['price'],
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => 'paid',
                'transaction_id' => $validatedData['transaction_id'],
                'paid_at' => Carbon::now(),
            ]);

            $invoice->details()->create([
                'course_id' => $validatedData['course_id'],
                'price' => $validatedData['price'],
            ]);

            DB::commit();

            return response()->json(['message' => 'Invoice created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice creation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create invoice.'], 500);
        }
    }
}

