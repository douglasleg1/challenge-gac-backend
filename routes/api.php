<?php

use App\Http\Controllers\AdditionalFieldController;
use App\Http\Controllers\AdditionalFieldAnswerController;
use App\Http\Controllers\AdditionalFieldOptionController;
use App\Http\Controllers\AnswerTicketController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ModalityController;
use App\Http\Controllers\ProductBatchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypesController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

});

Route::prefix('open')->group(function () {

    Route::prefix('user')->group(function () {
        Route::post('/', [UserController::class, 'store']);
    });

});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });


    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->can('read-users');
        Route::get('me', [UserController::class, 'me'])->can('read-users');;
        Route::get('{id}', [UserController::class, 'show'])->can('read-users');
        Route::patch('{id}', [UserController::class, 'update'])->can('edit-users');
        Route::delete('{id}', [UserController::class, 'destroy'])->can('delete-users');
    });

    Route::prefix('wallet')->group(function () {
        Route::post('deposit', [WalletController::class, 'deposit'])->can('create-deposit');
        Route::post('transfer', [WalletController::class, 'transfer'])->can('create-transfer');
        Route::post('revert', [WalletController::class, 'revertTransaction'])->can('create-revert');
        Route::get('list-transfers', [WalletController::class, 'listTransfers'])->can('list-transfer');
    });

});


