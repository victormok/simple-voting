<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/getCSRFToken', [CampaignController::class, 'getCSRFToken'])->name('campaign.getCSRFToken');

Route::controller(CampaignController::class)->group(function () {
    Route::get('/getCSRFToken', 'getCSRFToken');

    Route::get('/campaign/allActive', 'allActive');
    Route::get('/campaign/finishedResult/{id}', 'finishedResult');
    Route::post('/campaign/create', 'create');
});

Route::post('/voting/create', [VotingController::class, 'create'])->name('campaign.create');


require __DIR__ . '/auth.php';
