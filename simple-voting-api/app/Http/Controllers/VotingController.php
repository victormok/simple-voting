<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VotingService;

class VotingController extends Controller
{
    private $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    public function create(Request $request)
    {
        $this->votingService->create();
        return 'vote';
    }
}
