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

        // Virtual Courses Mapping (based on FeaturedCourses.vue indices)
        $virtualCourses = [
            0 => ['title' => 'E-Commerce', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d', 'price' => 97.00, 'points' => ['Setting up your online store', 'Finding winning products', 'Scaling to 6 and 7 figures', 'Managing inventory and logistics']],
            1 => ['title' => 'Copywriting', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a', 'price' => 97.00, 'points' => ['The psychology of persuasion', 'Writing high-converting sales letters', 'Mastering email marketing', 'Crafting compelling headlines']],
            2 => ['title' => 'Stocks', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f', 'price' => 97.00, 'points' => ['Fundamental and technical analysis', 'Building a long-term portfolio', 'Risk management strategies', 'Understanding market cycles']],
            3 => ['title' => 'Crypto Investing', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1621761191319-c6fb62004040', 'price' => 97.00, 'points' => ['Understanding blockchain technology', 'Evaluating altcoins and projects', 'Secure storage and security', 'Long-term investment strategies']],
            4 => ['title' => 'Business & Finance', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f', 'price' => 97.00, 'points' => ['Managing business cash flow', 'Tax strategies for entrepreneurs', 'Building a scalable business model', 'Financial planning for success']],
            5 => ['title' => 'Crypto Trading', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1624555130581-1d9cca783bc0', 'price' => 97.00, 'points' => ['Day trading and swing trading basics', 'Chart patterns and indicators', 'Managing emotions in trading', 'Position sizing and leverage']],
            6 => ['title' => 'Content Creation & AI', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995', 'price' => 97.00, 'points' => ['Leveraging AI for content generation', 'Building a personal brand', 'Mastering social media algorithms', 'Scaling production with tools']],
            7 => ['title' => 'Client Acquisition & Social Media', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0', 'price' => 97.00, 'points' => ['Finding and closing high-ticket clients', 'Optimizing social media profiles', 'Outreach and lead generation', 'Retention and client management']],
            8 => ['title' => 'Ads Mastery', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1533750349088-cd871a92f312', 'price' => 97.00, 'points' => ['Facebook, Google, and TikTok ads', 'Targeting and retargeting strategies', 'Creative testing and optimization', 'Scaling budgets effectively']],
            9 => ['title' => 'DeFi', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0', 'price' => 97.00, 'points' => ['Yield farming and liquidity mining', 'Understanding decentralized exchanges', 'Managing risks in DeFi protocols', 'Navigating the ecosystem safely']],
            10 => ['title' => 'Digital Advertising', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71', 'price' => 97.00, 'points' => ['Multi-channel ad strategies', 'Ad copywriting and design', 'Analytics and tracking mastery', 'Maximizing ROI on spend']],
            11 => ['title' => 'Sales', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3', 'price' => 97.00, 'points' => ['The art of the close', 'Handling objections effectively', 'Building rapport and trust', 'Advanced negotiation techniques']],
            12 => ['title' => 'Airbnb', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688', 'price' => 97.00, 'points' => ['Rental arbitrage and management', 'Optimizing listings for ranking', 'Automating guest communication', 'Scaling to multiple properties']],
            13 => ['title' => 'Influencer Network Management', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113', 'price' => 97.00, 'points' => ['Building and managing a creator team', 'Monetization through networks', 'Campaign management and tracking', 'Scaling through partnerships']],
            14 => ['title' => 'Digital Marketing', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a', 'price' => 97.00, 'points' => ['Comprehensive marketing strategies', 'Funnel building and optimization', 'Conversion rate optimization (CRO)', 'Growth hacking techniques']],
            15 => ['title' => 'Lead Generation', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978', 'price' => 97.00, 'points' => ['B2B and B2C lead generation', 'Cold outreach mastery', 'Automating the sales funnel', 'Qualifying and nurturing leads']],
            16 => ['title' => 'Credit Repair', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f', 'price' => 97.00, 'points' => ['Understanding credit reporting', 'Strategies for credit improvement', 'Leveraging credit for business', 'Financial literacy and planning']],
            17 => ['title' => 'Drop Shipping', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1518133910546-b6c2fb7d79e3', 'price' => 97.00, 'points' => ['Product research for dropshipping', 'Building high-converting stores', 'Managing suppliers and shipping', 'Scaling through paid traffic']],
            18 => ['title' => 'Social Media Automation', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f', 'price' => 97.00, 'points' => ['Automating posting and engagement', 'Building massive social reach', 'Tooling and workflow efficiency', 'Monetizing automated accounts']],
            19 => ['title' => 'SEO Consulting', 'instructor' => 'ELEVATEU EXPERT', 'image' => 'https://images.unsplash.com/photo-1571721795195-a2ca2d3370a9', 'price' => 97.00, 'points' => ['On-page and off-page SEO', 'Keyword research and strategy', 'Building a consulting business', 'Technical SEO and auditing']],
        ];

        // Try to get course from query param
        $courseId = $request->query('course_id');
        $courseData = null;

        if ($courseId !== null && isset($virtualCourses[$courseId])) {
            $courseData = (object) array_merge($virtualCourses[$courseId], ['id' => $courseId]);
        } else {
            // Try DB if no virtual match
            $course = Course::find($courseId);
            if ($course) {
                $courseData = $course;
            } else {
                // Fallback to first available or a default
                $course = Course::first();
                if ($course) {
                    $courseData = $course;
                } else {
                    $courseData = (object) [
                        'id' => 1,
                        'title' => 'ElevateU University Membership',
                        'instructor' => 'GRANT CARDONE',
                        'price' => 497.00,
                        'thumbnail' => null,
                        'points' => ['Learn essential life skills', 'Join an exclusive community', 'Get direct mentorship']
                    ];
                }
            }
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
                'id' => $courseData->id,
                'title' => $courseData->title,
                'instructor' => property_exists($courseData, 'instructor') ? $courseData->instructor : 'ELEVATEU EXPERT',
                'price' => (float) $courseData->price,
                'thumbnail' => property_exists($courseData, 'thumbnail') && $courseData->thumbnail ? asset($courseData->thumbnail) : (property_exists($courseData, 'image') ? $courseData->image : null),
                'learning_points' => property_exists($courseData, 'points') ? $courseData->points : (method_exists($courseData, 'learningPoints') ? $courseData->learningPoints() : []),
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
