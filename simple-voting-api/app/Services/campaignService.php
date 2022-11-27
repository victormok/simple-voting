<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CampaignService
{
    public function createCampaign($campaign)
    {
        $id = DB::table('campaign')->insertGetId([
            'admin' => $campaign->admin,
            'description' =>  $campaign->description,
            'start_time' =>  $campaign->start,
            'end_time' =>  $campaign->end,
            'is_active' => $campaign->is_active,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime()
        ]);

        foreach ($campaign->candidates as $k => $v) {
            DB::table('candidate')->insert([
                'name' => $v,
                'campaign_id' => $id
            ]);
        }

        $createdCampaign = DB::table('campaign')
            ->select('id', 'description', 'start_time', 'end_time')
            ->where('id', $id)
            ->get();

        $candidate = DB::table('candidate')
            ->select('name')
            ->where('campaign_id', $id)
            ->get();
        $createdCampaign[0]->candidates = $candidate;

        return $createdCampaign;
    }

    public function getAllActiveCampaign()
    {
        $activeCampaigns = DB::table('campaign')
            ->select('id', 'description', 'start_time', 'end_time')
            ->where('is_active', 1)
            ->get();
        foreach ($activeCampaigns as $k => $v) {
            $candidate = DB::table('candidate')
                ->select('name')
                ->where('campaign_id', $v->id)
                ->get();
            $activeCampaigns[$k]->candidates = $candidate;
        }

        return $activeCampaigns;
    }

    public function finishedResult($finishedCampaign)
    {
        $finishedCampaign = DB::table('campaign')
            ->select('campaign.id', 'description', 'start_time', 'end_time')
            ->where('campaign.id', $finishedCampaign->id)
            ->where('is_active', 0)
            ->get()
            ->first();
        if (empty($finishedCampaign)) {
            return "The campaign have not been finished";
        }

        $candidates = DB::table('candidate')
            ->select('id', 'name')
            ->where('campaign_id', $finishedCampaign->id)
            ->get();

        return [$finishedCampaign, $candidates];
    }
}
