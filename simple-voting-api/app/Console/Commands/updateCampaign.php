<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class UpdateCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateCampaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update campaign when start time or end time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        date_default_timezone_set('Asia/Hong_Kong');
        $todayTime = date("Y-m-d H:00:00");

        DB::table('campaign')
            ->where('start_time', '=', $todayTime)
            ->update(['is_active' => 1]);
        DB::table('campaign')
            ->where('end_time', '=', $todayTime)
            ->update(['is_active' => 0]);

        return Command::SUCCESS;
    }
}
