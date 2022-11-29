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


Route::controller(CampaignController::class)->group(function () {
    Route::get('/api/getCSRFToken', 'getCSRFToken');

    Route::get('/api/campaign/allActive', 'allActive');
    Route::get('/api/campaign/finishedResult/{id}', 'finishedResult');
    Route::post('/api/campaign/create', 'create');
});

Route::post('/api/voting/create', [VotingController::class, 'create'])->name('campaign.create');


require __DIR__ . '/auth.php';
