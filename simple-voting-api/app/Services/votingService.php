<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class VotingService
{
    /**
     * Create a campaign.
     */
    public function create($vote)
    {
        date_default_timezone_set('Asia/Hong_Kong');

        $now_time = strtotime(date("Y-m-d H:i:s"));
        $campaign = DB::table('campaign')
            ->select('description')
            ->where('id', '=', $vote->campaign_id)
            ->where('start_time', '<', $now_time)
            ->where('end_time', '>', $now_time)
            ->get()
            ->first();

        if (empty($campaign)) {
            return "The campaign have not active";
        }

        $candidates = DB::table('candidate')
            ->select('id', 'name')
            ->where('campaign_id', '=', $vote->campaign_id)
            /*             ->where('id', '=', $vote->candidate_id) */
            ->get();

        if (empty($candidates)) {
            return "The candidate is not existed";
        }

        DB::table('vote')->insert([
            'hkid' => $vote->hkid,
            'campaign_id' => $vote->campaign_id,
            'candidate_id' => $vote->candidate_id,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime()
        ]);

        return $candidates;
    }

    /**
     * Get count of votes.
     */
    public function getVotes($campaign_id, $candidate_id)
    {
        $vote = DB::table('vote')
            ->select(DB::raw('count(*) as vote_count'))
            ->where('campaign_id', '=', $campaign_id)
            ->where('candidate_id', '=', $candidate_id)
            ->get()
            ->first();

        return $vote;
    }
}
