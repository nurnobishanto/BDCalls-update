<?php

namespace App\Console\Commands;

use App\Models\IpNumber;
use App\Models\UserIpNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncIpNumberStatus extends Command
{
    protected $signature = 'ipnumbers:sync-status';

    protected $description = 'Sync IP Numbers status based on UserIpNumber assignments';

    public function handle()
    {
        $this->info('Starting IP numbers status sync...');

        $ipNumbers = IpNumber::all();
        Log::info('Called');
        foreach ($ipNumbers as $ip) {
            $assigned = UserIpNumber::where('number', $ip->number)
                ->whereNull('deleted_at')
                ->exists();

            $newStatus = $assigned ? 'unavailable' : 'available';

            if ($ip->status !== $newStatus) {
                $ip->status = $newStatus;
                $ip->update();
                $this->info("Updated IP {$ip->number} status to {$newStatus}");
            }
        }

        $this->info('IP numbers status sync completed.');

        return 0;
    }
}
