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

        $candidate = DB::table('candidate')
            ->select('name')
            ->where('campaign_id', '=', $vote->campaign_id)
            ->where('id', '=', $vote->candidate_id)
            ->get()
            ->first();

        if (empty($candidate)) {
            return "The candidate is not existed";
        }

        DB::table('vote')->insert([
            'hkid' => $vote->hkid,
            'campaign_id' => $vote->campaign_id,
            'candidate_id' => $vote->candidate_id,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime()
        ]);

        $vote = DB::table('vote')
            ->select('campaign.description', 'candidate.name')
            ->join('campaign', 'vote.campaign_id', '=', 'campaign.id')
            ->join('candidate', 'vote.candidate_id', '=', 'candidate.id')
            ->get()
            ->first();

        return $vote;
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
