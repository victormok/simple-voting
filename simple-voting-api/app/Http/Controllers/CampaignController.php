<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampaignService;

class CampaignController extends Controller
{
    private $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
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

            $today = strtotime(date("Y-m-d"));
            $start_date =  strtotime($request->start);
            $end_date =  strtotime($request->end);

            if ($today > $start_date) {
                return "Start date should not be earlier than today";
            }
            if ($end_date < $start_date) {
                return "End date should not be earlier than today";
            }
            
            $campaign = new \stdClass();

            if ($today == $start_date) {
                $campaign->is_active = 1;
            }

            if ($today < $start_date) {
                $campaign->is_active = 0;
            }

            $start_date = date_create($request->start);
            $end_date = date_create($request->end);

            $campaign->admin = $request->admin;
            $campaign->description = $request->description;
            $campaign->start = date_format($start_date, 'Y-m-d H:i:s');
            $campaign->end = date_format($end_date, 'Y-m-d H:i:s');
            $campaign->candidates = $request->candidates;

            $data = $this->campaignService->createCampaign($campaign);

            return $data;
        } catch (\Exception $e) {
            throw new \Exception("$e");
        }
    }

    /**
     */
    public function finishedResults()
    {
        $this->campaignService->finishedResults();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
