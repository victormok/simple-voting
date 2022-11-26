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
        $today = date("Y-m-d");

        DB::table('campaign')
            ->whereDate('start_time', '=', '2022-11-26')
            ->update(['is_active' => 1]);
        DB::table('campaign')
            ->whereDate('end_time', '=', '2022-11-30')
            ->update(['is_active' => 0]);

        return Command::SUCCESS;
    }
}
