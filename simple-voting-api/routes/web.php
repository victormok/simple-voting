<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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
Route::get('/campaign/finishedResults', [CampaignController::class, 'finishedResults'])->name('campaign.finishedResults');
Route::post('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');

Route::post('/voting/create', [VotingController::class, 'create'])->name('campaign.create');


require __DIR__ . '/auth.php';
