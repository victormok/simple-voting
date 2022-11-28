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

            $candidates =  $this->votingService->create($vote);

            $result = new \stdClass();
            $result->campaign_id = $vote->campaign_id;

            foreach ($candidates as $k => $v) {
                $vote = $this->votingService->getVotes($result->campaign_id, $v->id);

                $result->candidates["$k"]['id'] = $v->id;
                $result->candidates["$k"]['name'] = $v->name;
                $result->candidates["$k"]['vote_count'] = $vote->vote_count;
            }
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("$e");
        }
    }
}
