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

Route::get('/campaign/allActive', [CampaignController::class, 'allActive'])->name('campaign.allActive');
Route::get('/campaign/finishedResult', [CampaignController::class, 'finishedResult'])->name('campaign.finishedResult');
Route::post('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');

Route::post('/voting/create', [VotingController::class, 'create'])->name('campaign.create');


require __DIR__ . '/auth.php';
