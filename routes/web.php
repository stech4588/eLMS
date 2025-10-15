<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StripeController;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\CourseCertificateController;
use App\Http\Controllers\CourseIndustryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\InstructorRegisteredUserController;
use App\Http\Controllers\CourseFavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CareerJourneyController;
use App\Http\Controllers\Admin\MetaTagController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\UserListingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\AppleAuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommunityPostController;
use App\Http\Controllers\Admin\CommunitySettingsController;
use App\Http\Middleware\CheckCommunityAccess;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\PromotionController;
use App\Models\Review;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AiChatController;

Route::get('/', [WelcomeController::class, 'index']);

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacy.policy');

Route::get('/terms-of-services', function () {
    return Inertia::render('TermsOfService');
})->name('terms.of.services');

Route::get('/CommunityChat', [CommunityController::class, 'index'])
    ->middleware(['auth', 'verified', CheckCommunityAccess::class])
    ->name('community');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/communitysettings', [CommunitySettingsController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('communitysettings');

Route::post('/users/{user}/toggle-community-access', [CommunitySettingsController::class, 'toggleAccess'])
    ->middleware(['auth', 'verified'])->name('users.toggleCommunityAccess');

Route::get('/swiper', function () {
    return Inertia::render('library/swiper');
})->middleware(['auth', 'verified'])->name('swiper');
Route::get('/my-career-journey', [CareerJourneyController::class, 'index'])->middleware(['auth', 'verified'])->name('career.journey');
Route::get('/library', [ContentController::class, 'mylibrary'])
    ->middleware(['auth', 'verified'])
    ->name('library');
Route::get('/content', [\App\Http\Controllers\ContentController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('content');
Route::get('/coursess', [CourseController::class, 'myCourses'])
    ->middleware(['auth', 'verified'])
    ->name('coursess');
// Route::get('/addnewcourses', function () {
//     return Inertia::render('addCourses/addNewCourses');
// })->middleware(['auth', 'verified'])->name('addnewcourses');
Route::get('/addnewcourses', [CourseController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('addnewcourses');
// Route::get('/leadershipAndManagement', function () {
//     return Inertia::render('leadershipAndManagement/myleadershipAndManagement');
// })->middleware(['auth', 'verified'])->name('leadershipAndManagement');
Route::get('/joinnow', [PricingController::class, 'showJoinNowPage'])->name('join.now');
// Route::get('/artificialIntelligence', function () {
//     return Inertia::render('artificialIntelligence/myartificialIntelligence');
// })->middleware(['auth', 'verified'])->name('artificialIntelligence');
// Route::get('/cyberSecurity', function () {
//     return Inertia::render('cyberSecurity/mycyberSecurity');
// })->middleware(['auth', 'verified'])->name('cyberSecurity');
Route::get('/Instructor', function () {
    return Inertia::render('Instructor/myInstructor');
})->name('Instructor');
Route::get('/help', function () {
    return Inertia::render('help/help');
})->middleware(['auth', 'verified'])->name('help');

// Route::get('/cart', function () {
//     return Inertia::render('cart/cart');
// })->middleware(['auth', 'verified'])->name('cart');

Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::get('/BecomeInstructor', function () {
    return Inertia::render('Auth/becomeInstructor');
})->middleware(['auth', 'verified'])->name('BecomeInstructor');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-resume', [ProfileController::class, 'uploadResume'])->name('profile.uploadResume');
    Route::post('/profile/upload-picture', [ProfileController::class, 'uploadPicture'])->name('profile.uploadPicture');

    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/career-goal', [UserController::class, 'updateCareerGoal'])->name('career-goal.update');
    Route::patch('/preferred-topics', [UserController::class, 'updatePreferredTopics'])->name('preferred-topics.update');

    // Invoice Management Routes
     Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
     Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::post('/invoices/create-from-payment', [InvoiceController::class, 'storeFromPayment'])->name('invoices.storeFromPayment');

    // Course routes
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::post('/courses-with-videos', [CourseController::class, 'storeWithVideos'])->name('courses.storeWithVideos');


    // permission routes
     Route::post('/check-permissions', [RoleController::class, 'checkPermissions'])->middleware('auth');
    // Route::post('/check-permissions', function (Request $request) {
    //     $permissions = $request->input('permissions', []);
    //     $results = [];
    //     foreach ($permissions as $permission) {
    //         $results[$permission] = auth()->user()->can($permission);
    //     }
    //     return response()->json(['permissions' => $results]);
    // })->name('check-permissions');

    // Course Type routes
    Route::get('/course-management', [CourseTypeController::class, 'showManagementPage'])->name('course-management.index');
    Route::resource('course-types', CourseTypeController::class)->except(['index', 'create', 'show', 'edit']);
    Route::resource('topics', TopicController::class)->except(['index', 'create', 'show', 'edit']);
    Route::resource('course-certificates', CourseCertificateController::class)->except(['index', 'create', 'show', 'edit']);
    Route::resource('course-industries', CourseIndustryController::class)->except(['index', 'create', 'show', 'edit']);
    Route::get('/trending-topics-list', [TopicController::class, 'fetchTrending'])->name('topics.fetchTrending');

    // Meta Tags routes
    Route::resource('metatags', MetaTagController::class);
    Route::get('/api/pages', [PageController::class, 'index'])->name('api.pages.index');

    // Comments route
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::apiResource('progresses', ProgressController::class);
    Route::post('/progresses/storeUserVideoProgress', [ProgressController::class, 'storeUserVideoProgress'])
    ->name('progress.storeUserVideoProgress');
    Route::get('/video-progress/{video}', [ProgressController::class, 'getUserVideoProgress'])->name('progress.getUserVideoProgress');
    Route::get('/course-progress/{course}', [ProgressController::class, 'getCourseProgress'])->name('progress.getCourseProgress');
    Route::get('/course/{course}/completion-status', [ProgressController::class, 'getCompletionStatus'])->name('courses.completionStatus');

    Route::get('/admin/instructors', [InstructorController::class, 'index'])->name('admin.instructors.index');
    Route::get('/admin/instructors/{user}', [InstructorController::class, 'show'])->name('admin.instructors.show');
    Route::post('/admin/instructors/{instructor}/approve', [InstructorController::class, 'approve'])->name('admin.instructors.approve');
    Route::post('/admin/instructors/{instructor}/reject', [InstructorController::class, 'reject'])->name('admin.instructors.reject');
    Route::post('/admin/instructors/{user}/toggle-status', [InstructorController::class, 'toggleStatus'])->name('admin.instructors.toggleStatus');
    Route::delete('/admin/instructors/{user}', [InstructorController::class, 'destroy'])->name('admin.instructors.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsReadAndRedirect'])->name('notifications.read');

    Route::get('/register/complete', [RegisteredUserController::class, 'create'])->name('register.complete');
    Route::resource('admin/pricings', \App\Http\Controllers\Admin\PricingController::class);

    // Marketing Management Routes (explicitly defined for clarity and to resolve issues)
    Route::get('/admin/marketing', [App\Http\Controllers\Admin\MarketingController::class, 'showMarketingPage'])->name('admin.marketing.index');
    
    Route::post('/admin/marketing', [App\Http\Controllers\Admin\MarketingController::class, 'storeQuote'])->name('admin.marketing.store');
    Route::put('/admin/marketing/{quote}', [App\Http\Controllers\Admin\MarketingController::class, 'updateQuote'])->name('admin.marketing.update');
    Route::delete('/admin/marketing/{quote}', [App\Http\Controllers\Admin\MarketingController::class, 'destroyQuote'])->name('admin.marketing.destroy');
    Route::put('/admin/marketing/{quote}/toggle-status', [App\Http\Controllers\Admin\MarketingController::class, 'toggleQuoteStatus'])->name('admin.marketing.toggleStatus');

    // Promotion Routes (explicitly defined)
    Route::post('/admin/promotions', [App\Http\Controllers\Admin\MarketingController::class, 'storePromotion'])->name('admin.promotions.store');
    Route::put('/admin/promotions/{promotion}', [App\Http\Controllers\Admin\MarketingController::class, 'updatePromotion'])->name('admin.promotions.update'); // Use POST with _method for PUT
    Route::delete('/admin/promotions/{promotion}', [App\Http\Controllers\Admin\MarketingController::class, 'destroyPromotion'])->name('admin.promotions.destroy');
    Route::put('/admin/promotions/{promotion}/toggle-status', [App\Http\Controllers\Admin\MarketingController::class, 'togglePromotionStatus'])->name('admin.promotions.toggleStatus');

    Route::get('/promotions/random-active', [App\Http\Controllers\Admin\MarketingController::class, 'getRandomActivePromotion'])->name('promotions.randomActive');

    // Community post routes
    Route::get('/api/community-posts', [CommunityPostController::class, 'index']);
    Route::post('/api/community-posts', [CommunityPostController::class, 'store']);
    Route::get('/api/community-posts/{communityPost}', [CommunityPostController::class, 'show']);

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // AI Chatbot route
    Route::post('/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');
});

// //For Roles Routes
// Route::controller(RoleController::class)->group(function(){

//     Route::get('/check-permissions', 'checkPermissions');


// });

Route::apiResource('categories', CategoryController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('enrollments', EnrollmentController::class);
Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('progresses', ProgressController::class);
Route::apiResource('watchlists', WatchlistController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('certificates', CertificateController::class);

// Add this route for the course detail page
Route::get('/courses/{course}', [CourseController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('courses.show');

Route::get('/courses/{course}/feedback', [CourseController::class, 'showFeedback'])
    ->middleware(['auth', 'verified'])->name('courses.feedback');

// Add this route for the course player page
Route::get('/courses/{course}/play/{video?}', [CourseController::class, 'play'])
    ->middleware(['auth', 'verified'])->name('courses.play');

Route::get('/courses/{course}/related', [CourseController::class, 'related'])->name('courses.related');

    Route::get('/fetch-intent/{amount}', [StripeController::class, 'fetchIntent']);
    //Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);
// Route for toggling course favorite status
Route::post('/courses/{course}/favorite', [CourseFavoriteController::class, 'toggle'])
    ->middleware(['auth', 'verified'])
    ->name('courses.toggleFavorite');



Route::middleware('guest')->group(function () {
    Route::post('/register-from-payment', [RegisteredUserController::class, 'storeFromPayment'])->name('register.from.payment');
    Route::get('instructor/register', [InstructorRegisteredUserController::class, 'create'])
        ->name('instructor.register');

    Route::post('instructor/register', [InstructorRegisteredUserController::class, 'store']);
});

// Google Auth Routes
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// Facebook Auth Routes
Route::get('/auth/facebook/redirect', [FacebookAuthController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback'])->name('facebook.callback');

// Apple Auth Routes
Route::get('/auth/apple/redirect', [AppleAuthController::class, 'redirect'])->name('apple.redirect');
Route::get('/auth/apple/callback', [AppleAuthController::class, 'callback'])->name('apple.callback');

Route::get('/topic/{topic:name}', [TopicController::class, 'show'])->middleware(['auth'])->name('topic.show');

require __DIR__.'/auth.php';
