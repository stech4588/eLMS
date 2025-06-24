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
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
})->name('privacy.policy');

Route::get('/terms-of-services', function () {
    return Inertia::render('TermsOfService');
})->name('terms.of.services');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
Route::get('/joinnow', function () {
    return Inertia::render('joinNow/join_now');
})->name('joinnow');    
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

    Route::get('/admin/instructors', [InstructorController::class, 'index'])->name('admin.instructors.index');
    Route::get('/admin/instructors/{user}', [InstructorController::class, 'show'])->name('admin.instructors.show');
    Route::post('/admin/instructors/{instructor}/approve', [InstructorController::class, 'approve'])->name('admin.instructors.approve');
    Route::post('/admin/instructors/{instructor}/reject', [InstructorController::class, 'reject'])->name('admin.instructors.reject');
    Route::post('/admin/instructors/{user}/toggle-status', [InstructorController::class, 'toggleStatus'])->name('admin.instructors.toggleStatus');
    Route::delete('/admin/instructors/{user}', [InstructorController::class, 'destroy'])->name('admin.instructors.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsReadAndRedirect'])->name('notifications.read');
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

// Add this route for the course player page
Route::get('/courses/{course}/play/{video?}', [CourseController::class, 'play'])
    ->middleware(['auth', 'verified'])->name('courses.play');
    Route::get('/fetch-intent/{amount}', [StripeController::class, 'fetchIntent']);
    //Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);
// Route for toggling course favorite status
Route::post('/courses/{course}/favorite', [CourseFavoriteController::class, 'toggle'])
    ->middleware(['auth', 'verified'])
    ->name('courses.toggleFavorite');

Route::middleware('guest')->group(function () {
    Route::get('instructor/register', [InstructorRegisteredUserController::class, 'create'])
        ->name('instructor.register');

    Route::post('instructor/register', [InstructorRegisteredUserController::class, 'store']);
});

Route::get('/topic/{topic:name}', [TopicController::class, 'show'])->middleware(['auth'])->name('topic.show');

require __DIR__.'/auth.php';
