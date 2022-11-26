<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class VotingService
{
    public function create($vote)
    {
        dump(new \DateTime());
        DB::table('vote')->insert([
            'hkid' => $vote->hkid,
            'campaign_id' => $vote->campaign_id,
            'candidate_id' => $vote->candidate_id,
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime()
        ]);

        return 'vote';
    }
}
