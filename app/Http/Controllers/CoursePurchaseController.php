<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class CoursePurchaseController extends Controller
{
    /** Hardcoded for now; will come from course page later */
    private const COURSE_ID = 1;

    /**
     * Show the purchase course page.
     * Guest: full form (name, email, phone, password, card).
     * Auth: only card and purchase button.
     */
    public function show(Request $request): Response
    {
        $user = Auth::user();
        $course = Course::find(self::COURSE_ID);

        if (!$course) {
            abort(404, 'Course not found.');
        }

        $authUser = null;
        if ($user) {
            $authUser = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        }

        return Inertia::render('PurchaseCourse/Index', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'price' => (float) $course->price,
                'thumbnail' => $course->thumbnail ? asset($course->thumbnail) : null,
            ],
            'authUser' => $authUser,
        ]);
    }

    /**
     * Process course purchase.
     * Guest: validate email does not exist, then create user + invoice + detail, login, redirect.
     * Auth: create invoice + detail, redirect.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $courseId = (int) $request->input('course_id', self::COURSE_ID);
        $course = Course::find($courseId);

        if (!$course) {
            return back()->withErrors(['course' => 'Course not found.']);
        }

        $amount = (float) $course->price;
        if ($amount <= 0) {
            return back()->withErrors(['course' => 'Invalid course price.']);
        }

        if ($user) {
            return $this->processAuthenticatedPurchase($request, $user, $course, $amount);
        }

        return $this->processGuestPurchase($request, $course, $amount);
    }

    /**
     * Guest: validate email unique, then create user, invoice, detail, login, redirect.
     */
    private function processGuestPurchase(Request $request, Course $course, float $amount): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'phone_number' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'transaction_id' => 'required|string',
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role_id' => 3,
                'type' => 'student',
            ]);

            $this->createCoursePurchaseInvoice($user->id, $course->id, $amount, $request->transaction_id, $request->input('payment_method', 'card'));

            DB::commit();

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Course purchased successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors(['payment_error' => 'An error occurred. Please try again or contact support.']);
        }
    }

    /**
     * Logged-in user: create invoice + detail, redirect.
     */
    private function processAuthenticatedPurchase(Request $request, User $user, Course $course, float $amount): RedirectResponse
    {
        $request->validate([
            'transaction_id' => 'required|string',
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        // Optional: prevent duplicate purchase of same course
        $alreadyPurchased = Invoice::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->whereHas('details', fn ($q) => $q->where('course_id', $course->id))
            ->exists();

        if ($alreadyPurchased) {
            return redirect()->route('dashboard')->with('info', 'You already own this course.');
        }

        DB::beginTransaction();
        try {
            $this->createCoursePurchaseInvoice(
                $user->id,
                $course->id,
                $amount,
                $request->transaction_id,
                $request->input('payment_method', 'card')
            );
            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Course purchased successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors(['payment_error' => 'An error occurred. Please try again or contact support.']);
        }
    }

    /**
     * Create a one-time course purchase invoice (no subscription status update).
     */
    private function createCoursePurchaseInvoice(int $userId, int $courseId, float $amount, string $transactionId, string $paymentMethod): void
    {
        $invoice = Invoice::create([
            'user_id' => $userId,
            'amount' => $amount,
            'plan' => 'course_purchase',
            'billing_cycle' => 'one_time',
            'payment_method' => $paymentMethod,
            'payment_status' => 'paid',
            'transaction_id' => $transactionId,
            'paid_at' => now(),
            'billing_month' => null,
            'due_date' => null,
            'status' => 'paid',
            'notes' => 'One-time course purchase',
            'reminder_count' => 0,
            'last_reminded_at' => null,
        ]);

        InvoiceDetail::create([
            'invoice_id' => $invoice->id,
            'course_id' => $courseId,
            'price' => $amount,
        ]);
    }

    /**
     * Check if email already exists (for guest flow before payment).
     * Called via AJAX from frontend to block payment if email exists.
     */
    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $exists = User::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }
}
