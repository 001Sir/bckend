<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Admin Routes
 * @version 1.0.0
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Auth'], function () {
    Route::post('auth/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('auth/details', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'details']);
        Route::get('auth/logout', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'logout']);
    });
    // Dashboard Insights
    Route::get('insights', [\App\Http\Controllers\Admin\DashboardController::class, 'insights']);
    Route::get('conversion-metrics', [\App\Http\Controllers\Admin\DashboardController::class, 'conversionMetrics']);
});

//denis
Route::group(['prefix' => 'login'], function() {
    Route::post('partner', [\App\Http\Controllers\User\AuthController::class,'loginPartner']);
    Route::post('worker', [\App\Http\Controllers\User\AuthController::class,'loginWorker']);
});

Route::group(['prefix' => 'signup'], function() {
    Route::post('partner', [\App\Http\Controllers\User\AuthController::class,'registerPartner']);
    Route::post('worker', [\App\Http\Controllers\User\AuthController::class,'registerWorker']);
});

Route::group(['prefix' => 'profile'], function() {
    Route::post('company', [\App\Http\Controllers\User\AuthController::class,'companyUpdate']);
    Route::post('personal', [\App\Http\Controllers\User\AuthController::class,'personalUpdate']);
});

Route::group(['prefix' => 'shifts'], function() {
    Route::post('list', [\App\Http\Controllers\Shift\ShiftController::class,'listPartner']);
    Route::post('listWorker', [\App\Http\Controllers\Shift\ShiftController::class,'listWorker']);
    Route::post('post', [\App\Http\Controllers\Shift\ShiftController::class,'post']);
    Route::post('detail', [\App\Http\Controllers\Shift\ShiftController::class,'detail']);
    Route::post('edit', [\App\Http\Controllers\Shift\ShiftController::class,'edit']);
    Route::post('delete', [\App\Http\Controllers\Shift\ShiftController::class,'delete']);
    Route::post('applicants', [\App\Http\Controllers\Shift\ShiftController::class,'applicantsList']);
    Route::post('promote', [\App\Http\Controllers\Shift\ShiftController::class,'promote']);
    Route::post('demote', [\App\Http\Controllers\Shift\ShiftController::class,'demote']);
    Route::post('apply', [\App\Http\Controllers\Shift\ShiftController::class,'apply']);
    Route::post('cancel', [\App\Http\Controllers\Shift\ShiftController::class,'cancel']);
});

Route::group(['prefix' => 'messages'], function() {
    Route::post('list', [\App\Http\Controllers\Message\MessageController::class,'list']);
    Route::post('contact', [\App\Http\Controllers\Message\MessageController::class,'contact']);
    Route::post('insert', [\App\Http\Controllers\Message\MessageController::class,'insert']);
});
//denis end

/**
 * User Routes
 * @version 1.0.0
 */
Route::group(['prefix' => 'user', 'namespace' => 'Auth'], function () {
    Route::post('register', [\App\Http\Controllers\User\UserController::class, 'store']);
    Route::post('auth/login', [\App\Http\Controllers\User\Auth\LoginController::class, 'login']);
    Route::post('update/{id}', [\App\Http\Controllers\User\UserController::class, 'update']);
    Route::post('reset', [\App\Http\Controllers\User\UserController::class, 'reset']);
    Route::post('reset-submission', [\App\Http\Controllers\User\UserController::class, 'resetSubmission']);
    Route::post('verify', [\App\Http\Controllers\User\UserController::class, 'verifyEmail']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('auth/details', [\App\Http\Controllers\User\Auth\LoginController::class, 'details']);
        Route::get('auth/logout', [\App\Http\Controllers\User\Auth\LoginController::class, 'logout']);
    });
    Route::delete('delete/{id}', [\App\Http\Controllers\User\UserController::class, 'destroy']);
    Route::get('block/{id}', [\App\Http\Controllers\User\UserController::class, 'block']);
    Route::get('unblock/{id}', [\App\Http\Controllers\User\UserController::class, 'unblock']);
    Route::get('open-app', [\App\Http\Controllers\User\ActivityLogController::class, 'saveAppOpen']);
});

/**
 * Partner Routes
 * @version 1.0.0
 */
Route::group(['prefix' => 'partner', 'namespace' => 'Auth'], function () {
    Route::post('register', [\App\Http\Controllers\Partner\PartnerController::class, 'store']);
    Route::post('auth/login', [\App\Http\Controllers\Partner\Auth\LoginController::class, 'login']);
    Route::post('reset', [\App\Http\Controllers\Partner\PartnerController::class, 'reset']);
    Route::post('reset-submission', [\App\Http\Controllers\Partner\PartnerController::class, 'resetSubmission']);
    Route::post('verify', [\App\Http\Controllers\Partner\PartnerController::class, 'verifyEmail']);
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('auth/details', [\App\Http\Controllers\Partner\Auth\LoginController::class, 'details']);
        Route::get('auth/logout', [\App\Http\Controllers\Partner\Auth\LoginController::class, 'logout']);
        Route::put('edit-partner/{id}', [\App\Http\Controllers\Partner\PartnerController::class, 'update']);
        Route::post('new-invoice', [\App\Http\Controllers\Partner\InvoiceController::class, 'createInvoice']);
        Route::post('pay-card-invoice', [\App\Http\Controllers\Partner\PaymentController::class, 'payCardInvoice']);
    });
    Route::delete('delete/{id}', [\App\Http\Controllers\Partner\PartnerController::class, 'destroy']);
    Route::get('all', [\App\Http\Controllers\Partner\PartnerController::class, 'all']);
    Route::get('list', [\App\Http\Controllers\Partner\PartnerController::class, 'list']);
    Route::get('dashboard', [\App\Http\Controllers\Partner\DashboardController::class, 'index']);
    Route::get('top-performers', [\App\Http\Controllers\Partner\PartnerController::class, 'topPerformers']);
});


// Blog Posts
Route::group(['prefix' => 'blogposts'], function() {
    Route::get('all', [\App\Http\Controllers\API\V1\BlogPostController::class, 'index']);
    Route::get('featured', [\App\Http\Controllers\API\V1\BlogPostController::class, 'featuredArticles']);
    Route::get('/{id}', [\App\Http\Controllers\API\V1\BlogPostController::class, 'show']);
});

// Web Pages
Route::group(['prefix' => 'webpages'], function() {
    Route::get('all', [\App\Http\Controllers\Admin\WebPageController::class, 'allPosts']);
});

// Category Routes
Route::group(['prefix' => 'categories'], function () {
    Route::get('all', [\App\Http\Controllers\API\V1\CategoryController::class, 'index']);
    Route::get('jobs/{id}', [\App\Http\Controllers\API\V1\CategoryController::class, 'getJobsInCategory']);
});

// Skill Routes
Route::group(['prefix' => 'skills'], function () {
    Route::get('all', [\App\Http\Controllers\API\V1\SkillController::class, 'index']);
    Route::get('autocomplete', [\App\Http\Controllers\API\V1\SkillController::class, 'hintableSkills']);
    Route::get('user/{id}', [\App\Http\Controllers\API\V1\SkillController::class, 'userSkills']);
    Route::post('user/{id}', [\App\Http\Controllers\API\V1\SkillController::class, 'addSkillsToUser']);
});

// Resume Routes
Route::group(['prefix' => 'resume'], function () {
    Route::get('user/{id}', [\App\Http\Controllers\API\V1\ResumeController::class, 'userResumes']);
    Route::post('new', [\App\Http\Controllers\API\V1\ResumeController::class, 'store']);
    Route::get('delete/{id}', [\App\Http\Controllers\API\V1\ResumeController::class, 'destroy']);
});

// Job Type Routes
Route::group(['prefix' => 'job-types'], function () {
    Route::get('all', [\App\Http\Controllers\API\V1\JobTypeController::class, 'index']);
});

// Job Routes
Route::group(['prefix' => 'jobs'], function () {
    Route::get('all', [\App\Http\Controllers\API\V1\JobController::class, 'index']);
    Route::get('view/{id}', [\App\Http\Controllers\API\V1\JobController::class, 'show']);
    Route::get('home', [\App\Http\Controllers\API\V1\JobController::class, 'homePagePosts']);
    Route::get('list', [\App\Http\Controllers\API\V1\JobController::class, 'allPosts']);
    Route::get('user', [\App\Http\Controllers\API\V1\JobController::class, 'jobUser']);
    Route::get('partner', [\App\Http\Controllers\Admin\JobController::class, 'listJobsPartner']);
    Route::get('category', [\App\Http\Controllers\Admin\JobController::class, 'listJobsCategory']);
    Route::get('details/{id}', [\App\Http\Controllers\Admin\JobController::class, 'listJobsApplicants']);
    Route::get('stats', [\App\Http\Controllers\Admin\JobController::class, 'stats']);
    Route::post('new', [\App\Http\Controllers\API\V1\JobController::class, 'store']);
    Route::post('edit/{id}', [\App\Http\Controllers\API\V1\JobController::class, 'update']);
    Route::get('toggle-status/{id}/{status}', [\App\Http\Controllers\API\V1\JobController::class, 'toggleJobStatus']);
    Route::get('delete/{id}', [\App\Http\Controllers\API\V1\JobController::class, 'destroy']);
});

// Jobs Saved
Route::group(['prefix' => 'jobs-saved'], function () {
    Route::get('{id}', [\App\Http\Controllers\API\V1\JobSavedController::class, 'show']);
    Route::get('delete/{user}/{id}', [\App\Http\Controllers\API\V1\JobSavedController::class, 'destroy']);
    Route::post('/', [\App\Http\Controllers\API\V1\JobSavedController::class, 'store']);
});

// Job Applications
Route::group(['prefix' => 'job-applications'], function () {
    Route::get('shortlist-count', [\App\Http\Controllers\API\V1\JobApplicationController::class, 'shortlistedCount']);
    Route::get('shortlist/toggle/{id}', [\App\Http\Controllers\API\V1\JobApplicationController::class, 'toggleReadStatus']);
    Route::get('jobs/applied', [\App\Http\Controllers\API\V1\JobApplicationController::class, 'listApplications']);
    Route::get('jobs/shortlisted', [\App\Http\Controllers\API\V1\JobApplicationController::class, 'listShortlistedJobs']);
    Route::get('shortlist/{id}', [\App\Http\Controllers\API\V1\JobApplicationController::class, 'shortlistApplicant']);
    Route::APIResource('/', \App\Http\Controllers\API\V1\JobApplicationController::class);
});

// Post Duration Routes
Route::group(['prefix' => 'post-durations'], function () {
    Route::get('all', [App\Http\Controllers\API\V1\PostDurationController::class, 'index']);
    Route::get('partner/{id}', [\App\Http\Controllers\API\V1\PostDurationController::class, 'listForPartners']);
});

Route::group(['prefix' => 'job-types'], function () {
    Route::get('all', [\App\Http\Controllers\Admin\JobTypeController::class, 'index']);
});

// Invoice Routes
Route::group(['prefix' => 'invoices', 'middleware' => 'auth:sanctum'], function () {
    Route::get('sales-revenue', [\App\Http\Controllers\Admin\InvoiceController::class, 'paidInvoiceStats']);
    Route::get('partner/{id}', [\App\Http\Controllers\Admin\InvoiceController::class, 'partnerInvoices']);
});

// Packages Routes
Route::group(['prefix' => 'packages', 'middleware' => 'auth:sanctum'], function() {
    Route::get('all', [\App\Http\Controllers\Admin\PackageController::class, 'index']);
});

// Notification
Route::group(['prefix' => 'notifications'], function () {
    Route::get('user/{user_id}', [\App\Http\Controllers\API\V1\NotificationSettingController::class, 'retrieveUser']);
    Route::get('partner/{partner_id}', [\App\Http\Controllers\API\V1\NotificationSettingController::class, 'retrievePartner']);
    Route::post('user/setting', [\App\Http\Controllers\API\V1\NotificationSettingController::class, 'saveSettingUser']);
    Route::post('partner/setting', [\App\Http\Controllers\API\V1\NotificationSettingController::class, 'saveSettingPartner']);
    Route::get('user/devices/{id}', [\App\Http\Controllers\API\V1\NotificationDeviceController::class, 'retrieveUserDevices']);
    Route::get('partner/devices/{id}', [\App\Http\Controllers\API\V1\NotificationDeviceController::class, 'retrievePartnerDevices']);
    Route::post('user/device', [\App\Http\Controllers\API\V1\NotificationDeviceController::class, 'saveUserDevice']);
    Route::post('partner/device', [\App\Http\Controllers\API\V1\NotificationDeviceController::class, 'savePartnerDevice']);
});

// App Settings Routes
Route::group(['prefix' => 'settings'], function() {
    Route::get('general', [\App\Http\Controllers\Admin\AppSettingController::class, 'generalSettings']);
    Route::get('application', [\App\Http\Controllers\Admin\AppSettingController::class, 'applicationSettings']);
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::post('save', [\App\Http\Controllers\Admin\AppSettingController::class, 'store']);
    });
});

// Admin Operations Routes
Route::group(['prefix' => 'admin'], function() {
    Route::APIResource('partners', \App\Http\Controllers\Admin\PartnerController::class);
    Route::APIResource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::APIResource('jobs', \App\Http\Controllers\Admin\JobController::class);
    Route::APIResource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::APIResource('skills', \App\Http\Controllers\Admin\SkillController::class);
    Route::APIResource('job-types', \App\Http\Controllers\Admin\JobTypeController::class);
    Route::APIResource('job-saved', \App\Http\Controllers\API\V1\JobSavedController::class);
    Route::APIResource('post-durations', \App\Http\Controllers\Admin\PostDurationController::class);
    Route::APIResource('webpages', \App\Http\Controllers\Admin\WebPageController::class);
    Route::APIResource('blogposts', \App\Http\Controllers\Admin\BlogPostController::class);
    Route::get('blogposts/toggle-public/{id}', [\App\Http\Controllers\Admin\BlogPostController::class, 'togglePublishedStatus']);
    Route::APIResource('packages', \App\Http\Controllers\Admin\PackageController::class);
    Route::APIResource('features', \App\Http\Controllers\Admin\FeatureController::class);
    Route::APIResource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
    Route::APIResource('resume', \App\Http\Controllers\API\V1\ResumeController::class);
});

// Misc. Routes
Route::post('/file/upload-image', [\App\Http\Controllers\API\V1\FileUploadController::class, 'uploadImage']);
Route::post('/file/upload-doc', [\App\Http\Controllers\API\V1\FileUploadController::class, 'uploadDoc']);
