<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Topic;
use App\Models\Invoice;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Services\SubscriptionService;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        // Capture referral code if present and store for later use
        if ($request->filled('ref')) {
            Session::put('referral_code', $request->query('ref'));
        }
        $topics = Topic::all();
        $user = Auth::user();

        return Inertia::render('Auth/Register', [
            'topics' => $topics,
            'auth' => [
                'user' => $user ? [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'phone_country_code' => 'PK', // Default value since it's not stored in DB
                    'primary_learning_goal' => $user->primary_learning_goal,
                    'preferred_topic_ids' => $user->preferred_topic_ids ?? [],
                    'profile_picture' => $user->profile_picture,
                ] : null,
            ]
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            $request->validate([
                'phone_number' => 'required|string|max:20',
                'primary_learning_goal' => 'required|string',
                'preferred_topics' => 'required|array',
                'preferred_topics.*' => 'exists:topics,id',
                'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'profile_picture' => 'nullable|image|max:2048',
                'agree_to_terms' => 'accepted',
            ]);

            $updateData = [
                'phone_number' => $request->phone_number,
                'primary_learning_goal' => $request->primary_learning_goal,
                'preferred_topic_ids' => $request->preferred_topics,
            ];
            
            // Only update resume_path if a new file was uploaded
            if ($request->hasFile('resume')) {
                $resumeDir = public_path('resumes');
                if (!File::exists($resumeDir)) {
                    File::makeDirectory($resumeDir, 0755, true);
                }
                
                $file = $request->file('resume');
                $filename = 'resume_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($resumeDir, $filename);
                $updateData['resume_path'] = 'resumes/' . $filename;
            }
            
            // Only update profile_picture if a new file was uploaded
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                    @unlink(public_path($user->profile_picture));
                }
                
                $profilePicturesDir = public_path('profile-pictures');
                if (!File::exists($profilePicturesDir)) {
                    File::makeDirectory($profilePicturesDir, 0755, true);
                }
                
                $file = $request->file('profile_picture');
                $filename = 'profile_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($profilePicturesDir, $filename);
                $updateData['profile_picture'] = 'profile-pictures/' . $filename;
            }
            
            $user->update($updateData);

            // Apply referral rewards once at profile completion
            if (Session::has('referral_code')) {
                $code = Session::get('referral_code');
                $referrerId = intval(base_convert($code, 36, 10));
                if ($referrerId > 0 && $referrerId !== $user->id) {
                    $referrer = User::find($referrerId);
                    if ($referrer) {
                        $referrer->increment('points', 100);
                        $user->increment('points', 25);
                    }
                }
                Session::forget('referral_code');
            }

            return redirect('/dashboard');
        }


        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
        //     'password' => ['required', Rules\Password::defaults()],
        //     'phone_number' => 'required|string|max:20',
        //     'primary_learning_goal' => 'required|string',
        //     'preferred_topics' => 'required|array',
        //     'preferred_topics.*' => 'exists:topics,id',
        //     'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        //     'profile_picture' => 'required|image|max:2048',
        //     'agree_to_terms' => 'accepted',
        // ]);

        // $resumePath = null;
        // if ($request->hasFile('resume')) {
        //     $file = $request->file('resume');
        //     $filename = 'resume_' . time() . '.' . $file->getClientOriginalExtension();
        //     $file->move(public_path('resumes'), $filename);
        //     $resumePath = 'resumes/' . $filename;
        // }

        // $profilePicturePath = null;
        // if ($request->hasFile('profile_picture')) {
        //     $file = $request->file('profile_picture');
        //     $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
        //     $file->move(public_path('profile-pictures'), $filename);
        //     $profilePicturePath = 'profile-pictures/' . $filename;
        // }

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'phone_number' => $request->phone_number,
        //     'primary_learning_goal' => $request->primary_learning_goal,
        //     'preferred_topic_ids' => json_encode($request->preferred_topics),
        //     'resume_path' => $resumePath,
        //     'profile_picture' => $profilePicturePath,
        //     'role_id' => 3,
        //     'type' => 'student',
        // ]);

        // event(new Registered($user));

        // Auth::login($user);

        return redirect('/dashboard');
    }

    public function storeFromPayment(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'transaction_id' => 'required|string',
            'amount' => 'required|numeric',
            'plan' => 'required|string',
            'billing_cycle' => 'required|string',
            'payment_method' => 'required|string',
        ]);
    
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role_id' => 3, // student
                'type' => 'student',
            ]);
    
            /** @var SubscriptionService $subscriptionService */
            $subscriptionService = app(SubscriptionService::class);
            $billingDate = now();

            $invoice = $subscriptionService->createMonthlyInvoice($user, $billingDate, [
                'amount' => $request->amount,
                'plan' => $request->plan,
                'billing_cycle' => $request->billing_cycle,
            ]);

            if (!$invoice) {
                throw new \RuntimeException('Unable to create invoice for registration payment.');
            }

            $subscriptionService->markPaid($invoice, [
                'transaction_id' => $request->transaction_id,
                'paid_at' => now(),
                'payment_method' => $request->payment_method,
                'notes' => 'Initial subscription payment',
            ]);
    
            DB::commit();
    
            Auth::login($user);
    
            event(new Registered($user));
    
            return redirect('/dashboard');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Registration from payment failed: ' . $e->getMessage());
            return back()->withErrors(['payment_error' => 'An error occurred during registration. Please contact support.']);
        }
    }
}
