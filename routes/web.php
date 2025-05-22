<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CertificateController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/swiper', function () {
    return Inertia::render('library/swiper');
})->middleware(['auth', 'verified'])->name('swiper');
Route::get('/careerJourney', function () {
    return Inertia::render('careerJourney/myCareerJourney');
})->middleware(['auth', 'verified'])->name('careerJourney');
Route::get('/library', function () {
    return Inertia::render('library/mylibrary');
})->middleware(['auth', 'verified'])->name('library');
Route::get('/content', function () {
    return Inertia::render('content/mycontent');
})->middleware(['auth', 'verified'])->name('content');
Route::get('/coursess', function () {
    return Inertia::render('Courses/myCourses');
})->middleware(['auth', 'verified'])->name('coursess');
Route::get('/addnewcourses', function () {
    return Inertia::render('addCourses/addNewCourses');
})->middleware(['auth', 'verified'])->name('addnewcourses');
Route::get('/leadershipAndManagement', function () {
    return Inertia::render('leadershipAndManagement/myleadershipAndManagement');
})->middleware(['auth', 'verified'])->name('leadershipAndManagement');
Route::get('/artificialIntelligence', function () {
    return Inertia::render('artificialIntelligence/myartificialIntelligence');
})->middleware(['auth', 'verified'])->name('artificialIntelligence');
Route::get('/cyberSecurity', function () {
    return Inertia::render('cyberSecurity/mycyberSecurity');
})->middleware(['auth', 'verified'])->name('cyberSecurity');
Route::get('/Instructor', function () {
    return Inertia::render('Instructor/myInstructor');
})->middleware(['auth', 'verified'])->name('Instructor');
Route::get('/help', function () {
    return Inertia::render('help/help');
})->middleware(['auth', 'verified'])->name('help');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::apiResource('categories', CategoryController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('videos', VideoController::class);
Route::apiResource('enrollments', EnrollmentController::class);
Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('progresses', ProgressController::class);
Route::apiResource('watchlists', WatchlistController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('certificates', CertificateController::class);


require __DIR__.'/auth.php';
