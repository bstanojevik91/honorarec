<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobListingController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\Employer\EmployerAuthController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicMediaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/media/{path}', [PublicMediaController::class, 'show'])->where('path', '.*')->name('media.public');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog.index');
Route::get('/chpp', [HomeController::class, 'faq'])->name('faq');
Route::get('/blog/{slug}', [HomeController::class, 'showBlogPost'])->name('blog.show');
Route::get('/oglasi', [HomeController::class, 'jobs'])->name('jobs.index');
Route::get('/oglasi/{slug}', [HomeController::class, 'showJob'])->name('jobs.show');
Route::post('/oglasi/{slug}/apliciraj', [HomeController::class, 'apply'])->name('jobs.apply');
Route::redirect('/login', '/admin/login')->name('login');
Route::redirect('/company/login', '/employer/login');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::post('/companies/{company}/employer-account', [CompanyController::class, 'storeEmployerAccount'])
            ->name('companies.employer-account.store');
        Route::patch('/companies/{company}/employer-account', [CompanyController::class, 'updateEmployerAccount'])
            ->name('companies.employer-account.update');
        Route::resource('companies', CompanyController::class)->except('show');
        Route::patch('/jobs/{job}/engagement-type', [JobListingController::class, 'updateEngagementType'])
            ->name('jobs.engagement-type.update');
        Route::patch('/jobs/{job}/approve', [JobListingController::class, 'approve'])->name('jobs.approve');
        Route::patch('/jobs/{job}/reject', [JobListingController::class, 'reject'])->name('jobs.reject');
        Route::resource('jobs', JobListingController::class)->except('show');
        Route::resource('blog-posts', BlogPostController::class)->parameters(['blog-posts' => 'blog_post'])->except('show');
        Route::post('/blog-posts/import-defaults', [BlogPostController::class, 'importDefaults'])->name('blog-posts.import-defaults');
        Route::patch('/blog-posts/{blog_post}/toggle-status', [BlogPostController::class, 'toggleStatus'])->name('blog-posts.toggle-status');

        Route::get('/applications', [JobApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
        Route::get('/applications/{application}/cv', [JobApplicationController::class, 'cv'])->name('applications.cv');
    });
});

Route::prefix('employer')->name('employer.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [EmployerAuthController::class, 'create'])->name('login');
        Route::post('/login', [EmployerAuthController::class, 'store'])->name('login.store');
        Route::get('/register', [EmployerAuthController::class, 'createRegister'])->name('register');
        Route::post('/register', [EmployerAuthController::class, 'register'])->name('register.store');
    });

    Route::get('/verify-email/{id}/{hash}', [EmployerAuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::middleware(['auth', 'employer'])->group(function (): void {
        Route::post('/logout', [EmployerAuthController::class, 'destroy'])->name('logout');
        Route::get('/verify-email', [EmployerAuthController::class, 'verificationNotice'])->name('verification.notice');
        Route::post('/email/verification-notification', [EmployerAuthController::class, 'resendVerification'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
    });

    Route::middleware(['auth', 'employer', 'employer.verified'])->group(function (): void {
        Route::get('/', EmployerDashboardController::class)->name('dashboard');

        Route::patch('/jobs/{job}/engagement-type', [EmployerJobController::class, 'updateEngagementType'])
            ->name('jobs.engagement-type.update');
        Route::resource('jobs', EmployerJobController::class)->except('show');
        Route::get('/applications', [EmployerApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [EmployerApplicationController::class, 'show'])->name('applications.show');
        Route::get('/applications/{application}/cv', [EmployerApplicationController::class, 'cv'])->name('applications.cv');
    });
});
