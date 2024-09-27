<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apps\AbstractAPIController;
use App\Http\Controllers\Apps\AiSensyController;
use App\Http\Controllers\Apps\EmailValidationController;
use App\Http\Controllers\Apps\ActiveCampaignController;
use App\Http\Controllers\Apps\ActiveCampaignAPIv3Controller;




Route::get('/test', function () {
    return "This is Test Api from api.php";
});

// Routes for Abstract API
Route::prefix('abstract-api')->group(function () {
    Route::get('/email-validation', [AbstractAPIController::class, 'validateEmail']);
    Route::get('/holidays', [AbstractAPIController::class, 'getHolidays']);
});

// Routes for AiSensy
Route::prefix('ai-sensy')->group(function () {
    Route::post('send-template-message', [AiSensyController::class, 'sendTemplateMessage']);
});

// Email validation route



Route::post('/email/validate', [EmailValidationController::class, 'validateEmail']);

// Route for retrieving an account



// Route to get all contacts
Route::get('activecampaign/contacts', [ActiveCampaignController::class, 'getContacts']);

// Route to get all messages
Route::get('activecampaign/messages', [ActiveCampaignController::class, 'getMessages']);

// Route to get all tags
Route::get('activecampaign/tags', [ActiveCampaignController::class, 'getTags']);

// Route to get all users
Route::get('activecampaign/users', [ActiveCampaignController::class, 'getUsers']);

// Route to list all accounts
Route::get('activecampaign/accounts', [ActiveCampaignController::class, 'listAllAccounts']);

// Route::get('/activecampaign/account/{id}', [ActiveCampaignPhpController::class, 'retrieveAccount']);

Route::post('/activecampaign/accounts', [ActiveCampaignController::class, 'createAccount']);


Route::post('/activecampaign/create-contact', [ActiveCampaignController::class, 'createContact']);




Route::put('/activecampaign/event-tracking', [ActiveCampaignController::class, 'eventTracking']);






// Route::delete('activecampaign/accounts/{accountId}', [ActiveCampaignController::class, 'deleteAccount']);
// routes/web.php or routes/api.php
Route::delete('activecampaign/account', [ActiveCampaignController::class, 'deleteAccount']);
