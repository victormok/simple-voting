<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CampaignService
{
    public function createCampaign($campaign)
    {
        date_default_timezone_set('Asia/Hong_Kong');

        $id = DB::table('campaign')->insertGetId([
            'admin' => $campaign->admin,
            'description' =>  $campaign->description,
            'start_time' =>  $campaign->start_time,
            'end_time' =>  $campaign->end_time,
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
        date_default_timezone_set('Asia/Hong_Kong');

        $now_time = strtotime(date("Y-m-d H:i:s"));

        $activeCampaigns = DB::table('campaign')
            ->select('id', 'description', 'start_time', 'end_time')
            ->where('start_time', '<', $now_time)
            ->where('end_time', '>', $now_time)
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
        date_default_timezone_set('Asia/Hong_Kong');

        $now_time = strtotime(date("Y-m-d H:i:s"));

        $finishedCampaign = DB::table('campaign')
            ->select('campaign.id', 'description', 'start_time', 'end_time')
            ->where('campaign.id', $finishedCampaign->id)
            ->where('end_time', '<', $now_time)
            ->get()
            ->first();
        if (empty($finishedCampaign)) {
            return null;
        }

        $candidates = DB::table('candidate')
            ->select('id', 'name')
            ->where('campaign_id', $finishedCampaign->id)
            ->get();
        return [$finishedCampaign, $candidates];
    }
}
