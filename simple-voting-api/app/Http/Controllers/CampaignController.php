<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampaignService;
use App\Services\VotingService;

class CampaignController extends Controller
{
    private $campaignService;
    private $votingService;

    public function __construct(CampaignService $campaignService, VotingService $votingService)
    {
        $this->campaignService = $campaignService;
        $this->votingService = $votingService;
    }

    /**
     *  Return a csrf token.
     */
    public function getCSRFToken()
    {
        return csrf_token();
    }

    /**
     * Display a listing of the active campaign with votes.
     *
     * @return \Illuminate\Http\Response
     */
    public function allActive()
    {
        try {
            $activeCampaigns = $this->campaignService->getAllActiveCampaign();

            $results = [];

            foreach ($activeCampaigns as $campaignKey => $activeCampaign) {
                $results["$campaignKey"]["id"] = $activeCampaign->id;
                $results["$campaignKey"]["description"] = $activeCampaign->description;
                $results["$campaignKey"]["start_time"] = $activeCampaign->start_time;
                $results["$campaignKey"]["end_time"] = $activeCampaign->end_time;
                foreach ($activeCampaign->candidates as $candidateKey => $candidate) {

                    $vote = $this->votingService->getVotes($activeCampaign->id, $candidate->id);

                    $results["$campaignKey"]["candidates"]["$candidateKey"]["id"] = $candidate->id;
                    $results["$campaignKey"]["candidates"]["$candidateKey"]["name"] = $candidate->name;
                    $results["$campaignKey"]["candidates"]["$candidateKey"]["vote_count"] = $vote->vote_count;
                }
            }

            return $results;
        } catch (\Exception $e) {
            throw new \Exception("$e");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            date_default_timezone_set('Asia/Hong_Kong');

            $request->validate([
                'admin' => 'required',
                'description' => 'required|max:255',
                'start_time' => 'required|date',
                'end_time' => 'required|date',
                'candidates' => 'required',
            ]);

            $now_time = strtotime(date("Y-m-d H:i:s"));
            $start_time = strtotime($request->start_time);
            $end_time = strtotime($request->end_time);

            if ($now_time > $start_time) {
                return "Start time should not be earlier than now";
            }
            if ($end_time < $start_time) {
                return "End time should not be earlier than start time";
            }

            $campaign = new \stdClass();

            if ($now_time == $start_time) {
                $campaign->is_active = 1;
            }

            if ($now_time < $start_time) {
                $campaign->is_active = 0;
            }

            $campaign->admin = $request->admin;
            $campaign->description = $request->description;
            $campaign->start_time = $start_time;
            $campaign->end_time = $end_time;
            $campaign->candidates = $request->candidates;

            $data = $this->campaignService->createCampaign($campaign);
            return $data;
        } catch (\Exception $e) {
            throw new \Exception("$e");
        }
    }

    /**
     * Display a finished result.
     */
    public function finishedResult($id)
    {
        $finishedCampaign = new \stdClass();
        $finishedCampaign->id = $id;

        [$finishedCampaign, $candidates] = $this->campaignService->finishedResult($finishedCampaign);

        if ($finishedCampaign == null) {
            return "The campaign has not been finished";
        }

        $result = new \stdClass();
        $result->campaign_id = $finishedCampaign->id;
        $result->description = $finishedCampaign->description;
        $result->start_time = $finishedCampaign->start_time;
        $result->end_time = $finishedCampaign->end_time;

        foreach ($candidates as $k => $v) {
            $vote = $this->votingService->getVotes($finishedCampaign->id, $v->id);

            $result->candidates["$k"]['id'] = $v->id;
            $result->candidates["$k"]['name'] = $v->name;
            $result->candidates["$k"]['vote_count'] = $vote->vote_count;
        }

        return $result;
    }
}
