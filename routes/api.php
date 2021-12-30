<?php

use App\Http\Controllers\Auth\AuthorizeDeviceController;
use App\Http\Controllers\Auth\DisableAccountController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CutoffPointController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

###################
# GUEST
###################
//add test route that returns db connection env variables
Route::prefix('test')->group(function () {
    $db_connection = env('DB_CONNECTION');
    $db_host = env('DB_HOST');
    $db_port = env('DB_PORT');
    $db_database = env('DB_DATABASE');
    $db_username = env('DB_USERNAME');  
    $db_password = env('DB_PASSWORD');
    //return array of the env variables
    Route::get('/', function () use ($db_connection, $db_host, $db_port, $db_database, $db_username, $db_password) {
        return [
            'DB_CONNECTION' => $db_connection,
            'DB_HOST' => $db_host,
            'DB_PORT' => $db_port,
            'DB_DATABASE' => $db_database,
            'DB_USERNAME' => $db_username,
            'DB_PASSWORD' => $db_password
        ];
    });
});

Route::group(['middleware' => 'guest'], function () {
    Route::post('email/verify/{token}', [EmailVerificationController::class, 'verify'])
        ->middleware('throttle:hard')
        ->name('api.email.verify');

    Route::post('devices/authorize/{token}', [AuthorizeDeviceController::class, 'authorizeDevice'])
        ->middleware('throttle:hard')
        ->name('api.device.authorize');

    Route::post('login', [LoginController::class, 'login'])
        ->name('api.auth.login');

    Route::post('register', [RegisterController::class, 'register'])
        ->name('api.auth.register');

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->middleware('throttle:hard')
        ->name('api.reset.email-link');

    Route::post('password/reset', [ResetPasswordController::class, 'reset'])
        ->middleware('throttle:hard')
        ->name('api.reset.password');
});

###################
# JUST AUTH
###################

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout'])
        ->name('api.auth.logout');

    Route::post('generate2faSecret', [TwoFactorAuthenticationController::class, 'generate2faSecret'])
        ->name('api.generate2faSecret');

    Route::post('enable2fa', [TwoFactorAuthenticationController::class, 'enable2fa'])
        ->name('api.enable2fa');
});

###################
# 2FA
###################

Route::group([
    'middleware' => [
        'auth:api',
        '2fa',
    ],
], function () {
    Route::post('disable2fa', [TwoFactorAuthenticationController::class, 'disable2fa'])
        ->name('api.disable2fa');

    Route::post('verify2fa', [TwoFactorAuthenticationController::class, 'verify2fa'])
        ->name('api.verify2fa');

    Route::get('me', [UserController::class, 'profile'])
        ->name('api.me');

    Route::patch('me', [UserController::class, 'updateMe'])
        ->name('api.me.update');

    Route::apiResource('users', UserController::class)
        ->only([
            'index',
            'show',
            'store',
            'update',
        ])
        ->names([
            'index'  => 'api.users.index',
            'show'   => 'api.users.show',
            'store'  => 'api.users.store',
            'update' => 'api.users.update',
        ]);
    Route::prefix('users')->group(function () {
        Route::get('/{user}/results', [ResultController::class, 'userResults']);
    });

    Route::patch('password/update', [UserController::class, 'updatePassword'])
        ->name('api.password.update');

    Route::patch('notifications/visualize-all', [NotificationController::class, 'visualizeAllNotifications'])
        ->name('api.notifications.visualize-all');

    Route::patch('notifications/{id}/visualize', [NotificationController::class, 'visualizeNotification'])
        ->name('api.notifications.visualize');

    Route::delete('devices/{id}', [AuthorizeDeviceController::class, 'destroy'])
        ->middleware('throttle:hard')
        ->name('api.device.destroy');
});

Route::get('ping', [UtilController::class, 'serverTime'])
    ->name('api.server.ping');

Route::post('/account/disable/{token}', [DisableAccountController::class, 'disable'])
    ->middleware(['throttle:hard'])
    ->name('api.account.disable');

//create universities group crud endpoints,auth middleware
Route::prefix('universities')->middleware(['auth:api'])->group(function () {
    Route::post('/', [UniversityController::class, 'store']);    //create university
    Route::get('/', [UniversityController::class, 'index']); //get all universities
    Route::get('/{id}', [UniversityController::class, 'show']); //get university by id
    Route::patch('/{id}', [UniversityController::class, 'update']); //update university
    Route::delete('/{id}', [UniversityController::class, 'destroy']); //delete university
});
Route::prefix('campuses')->middleware(['auth:api'])->group(function () {
    Route::post('/', [CampusController::class, 'store']);
    Route::get('/', [CampusController::class, 'index']);
    Route::get('/{id}', [CampusController::class, 'show']);
    Route::patch('/{id}', [CampusController::class, 'update']);
    Route::delete('/{id}', [CampusController::class, 'destroy']);
});
Route::group(['prefix' => 'courses', 'middleware' => 'auth:api'], function () {
    Route::post('/', [CourseController::class, 'store']);
    Route::get('/eligble', [CourseController::class, 'getEligibleCourses']);
    Route::get('/', [CourseController::class, 'index']);
    Route::get('/{course}', [CourseController::class, 'show']);
    Route::patch('/{course}', [CourseController::class, 'update']);
    Route::delete('/{course}', [CourseController::class, 'destroy']);
});
Route::group(['prefix' => 'subjects', 'middleware' => 'auth:api'], function () {
    Route::post('/', [SubjectController::class, 'store']);
    Route::get('/', [SubjectController::class, 'index']);
    Route::get('/{id}', [SubjectController::class, 'show']);
    Route::patch('/{id}', [SubjectController::class, 'update']);
    Route::delete('/{id}', [SubjectController::class, 'destroy']);
});
Route::group(['prefix' => 'results', 'middleware' => 'auth:api'], function () {
    Route::post('/', [ResultController::class, 'store']);
    Route::get('/', [ResultController::class, 'index']);
    Route::get('/{id}', [ResultController::class, 'show']);
    Route::patch('/{id}', [ResultController::class, 'update']);
    Route::delete('/{id}', [ResultController::class, 'destroy']);
});
Route::group(['prefix' => 'points', 'middleware' => 'auth:api'], function () {
    Route::post('/', [CutoffPointController::class, 'store']);
    Route::get('/', [CutoffPointController::class, 'index']);
    Route::get('/{id}', [CutoffPointController::class, 'show']);
    Route::patch('/{id}', [CutoffPointController::class, 'update']);
    Route::delete('/{point}', [CutoffPointController::class, 'destroy']);
});
Route::group(['prefix' => 'schools', 'middleware' => 'auth:api'], function () {
    Route::post('/', [SchoolController::class, 'store']);
    Route::get('/', [SchoolController::class, 'index']);
    Route::get('/{school}', [SchoolController::class, 'show']);
    Route::patch('/{school}', [SchoolController::class, 'update']);
    Route::delete('/{school}', [SchoolController::class, 'destroy']);
});
