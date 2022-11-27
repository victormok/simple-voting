<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VotingService;
use App\Rules\VaildHkid;

class VotingController extends Controller
{
    private $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    /**
     * Create a vote.
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'hkid' => ['required', 'string', new VaildHkid],
                'campaign_id' => 'required|integer',
                'candidate_id' => 'required|integer',
            ]);

            $vote = new \stdClass();
            $vote->hkid = $request->hkid;
            $vote->campaign_id = $request->campaign_id;
            $vote->candidate_id = $request->candidate_id;

            return $this->votingService->create($vote);
        } catch (\Exception $e) {
            throw new \Exception("$e");
        }
    }
}
