<?php

namespace App\Services;

use App\Models\IpNumber;
use Illuminate\Support\Facades\Log;

class IpNumberService
{
    /**
     * Check each IP number's usage and update its status accordingly.
     */
    public function checkAndUpdateStatus(): void
    {
        $ipNumbers = IpNumber::all();
        foreach ($ipNumbers as $ip) {
            // Example logic: if this IP is assigned to any user, mark unavailable, else available.
            $isAssigned = $ip->userIpNumbers()->exists(); // assuming relation userIpNumbers exists

            $newStatus = $isAssigned ? 'unavailable' : 'available';

            if ($ip->status !== $newStatus) {
                $ip->status = $newStatus;
                $ip->save();
            }
        }
    }
}
