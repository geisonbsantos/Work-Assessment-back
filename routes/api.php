<?php

use App\Http\Controllers\Api\AbilityController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomUserLogController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReportErrorController;
use App\Http\Controllers\Api\UserController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('forget-password', [ForgotPasswordController::class, 'sendEmail'])->name('forget.password');
Route::post('valid-token', [ForgotPasswordController::class, 'validToken'])->name('valid.token');
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');

Route::group(['middleware' => ['auth:sanctum', 'refreshTokenSanctum']], function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/report', [ReportErrorController::class, 'sendEmailReport']);

    /*
    |--------------------------------------------------------------------------
    | Profiles Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(ProfileController::class)->prefix('profiles')->name('profiles.')->group(function () {
        Route::get('/', 'index')->middleware(['abilities:list_perfil'])->name('index');
        Route::get('/{profile}', 'show')->middleware(['abilities:list_perfil'])->name('show');
        Route::get('/{profile}/abilities', 'getAbilities')->middleware(['abilities:list_perfil'])->name('abilities');
        Route::post('/', 'beforeStore')->middleware(['abilities:cad_perfil'])->name('store');
        Route::post('/{profile}/abilities', 'storeAbilities')->middleware(['abilities:cad_perfil'])->name('abilities.store');
        Route::put('/{profile}', 'beforeUpdate')->middleware(['abilities:cad_perfil'])->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
    /*
    |--------------------------------------------------------------------------
    | Abilities Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(AbilityController::class)->prefix('abilities')->group(function () {
        Route::get('/', 'index')->middleware(['abilities:list_habilidade']);
        Route::get('/{id}', 'show')->middleware(['abilities:list_habilidade']);
        Route::post('/', 'beforeStore')->middleware(['abilities:cad_habilidade']);
        Route::put('/{id}', 'beforeUpdate')->middleware(['abilities:cad_habilidade']);
        Route::delete('/{id}', 'destroy')->middleware(['abilities:del_habilidade']);
    });
    /*
    |--------------------------------------------------------------------------
    | Users Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/', 'index')->middleware(['abilities:list_usuario']);
        Route::get('/{id}', 'show')->middleware(['abilities:list_usuario']);
        Route::post('/', 'beforeStore')->middleware(['abilities:cad_usuario']);
        Route::put('/{id}', 'beforeUpdate')->middleware(['abilities:cad_usuario']);
        Route::delete('/{id}', 'destroy')->middleware(['abilities:del_usuario']);
        Route::put('/restore/{id}', 'restore')->middleware(['abilities:del_usuario']);
    });

    /*
    |--------------------------------------------------------------------------
    | Faq Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(FaqController::class)->prefix('faqs')->group(function () {
        Route::get('/{id}', [FaqController::class, 'show'])->middleware(['abilities:list_faqs']);
        Route::post('/', [FaqController::class, 'beforeStore'])->middleware(['abilities:cad_faqs']);
        Route::put('/{id}', [FaqController::class, 'beforeUpdate'])->middleware(['abilities:cad_faqs']);
        Route::delete('/{id}', [FaqController::class, 'destroy'])->middleware(['abilities:del_faqs']);
    });

    /*
    |--------------------------------------------------------------------------
    | Type Logs
    |--------------------------------------------------------------------------
    */
    Route::prefix('user_custom_logs')->group(function () {
        Route::get('/', [CustomUserLogController::class, 'index'])->middleware(['abilities:list_logs']);
        Route::post('/', [CustomUserLogController::class, 'store'])->middleware(['abilities:cad_logs']);
    });

});
