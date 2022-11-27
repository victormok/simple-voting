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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allActive()
    {
        try {
            return $this->campaignService->getAllActiveCampaign();
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
            $request->validate([
                'admin' => 'required',
                'description' => 'required|max:255',
                'start' => 'required|date',
                'end' => 'required|date',
                'candidates' => 'required',
            ]);

            $today = strtotime(date("Y-m-d H:00:00"));
            $start_time =  strtotime($request->start);
            $end_time =  strtotime($request->end);

            if ($today > $start_time) {
                return "Start date should not be earlier than today";
            }
            if ($end_time < $start_time) {
                return "End date should not be earlier than today";
            }

            $campaign = new \stdClass();

            if ($today == $start_time) {
                $campaign->is_active = 1;
            }

            if ($today < $start_time) {
                $campaign->is_active = 0;
            }

            $start_time = date_create($request->start);
            $end_time = date_create($request->end);

            $campaign->admin = $request->admin;
            $campaign->description = $request->description;
            $campaign->start = date_format($start_time, 'Y-m-d H:00:00');
            $campaign->end = date_format($end_time, 'Y-m-d H:00:00');
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
    public function finishedResult(Request $request)
    {
        $finishedCampaign = new \stdClass();
        $finishedCampaign->id = $request->id;

        [$finishedCampaign, $candidates] = $this->campaignService->finishedResult($finishedCampaign);

        $result = new \stdClass();
        $result->campaign_id = $finishedCampaign->id;
        $result->description = $finishedCampaign->description;
        $result->start_time = $finishedCampaign->start_time;
        $result->end_time = $finishedCampaign->end_time;

        foreach ($candidates as $k => $v) {
            $vote = $this->votingService->getVotes($finishedCampaign->id, $v->id);

            $result->candidates["$k"]['name'] = $v->name;
            $result->candidates["$k"]['vote_count'] = $vote->vote_count;
        }

        return $result;
    }
}
