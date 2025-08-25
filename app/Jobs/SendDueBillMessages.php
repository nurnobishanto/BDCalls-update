<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\DueBill;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDueBillMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $dueBills;
    public $type; // sms or whatsapp

    /**
     * Create a new job instance.
     */
    public function __construct($dueBills, $type = 'sms')
    {
        $this->dueBills = $dueBills;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $records = $this->dueBills;

        // Group by user
        $usersGrouped = $records->groupBy('user_id');

        foreach ($usersGrouped as $userId => $userRecords) {
            $user = $userRecords->first()->user;

            $messageParts = [];

            foreach ($userRecords->groupBy('user_ip_number_id') as $ipRecords) {
                Log::info($ipRecords);
                $ipId = $ipRecords->first()->user_ip_number_id; // ID for queries
                $ip = $ipRecords->first()->userIpNumber;       // model for display
                // Get the latest month for this IP
                $latestMonth = DueBill::where('user_ip_number_id', $ipId)
                    ->where('payment_status', 'unpaid')
                    ->latest('month')
                    ->value('month');

                if (!$latestMonth) {
                    continue; // skip if no unpaid dues
                }

                // Current month due
                $currentDue = DueBill::where('user_ip_number_id', $ipId)
                    ->where('month', $latestMonth)
                    ->sum('total');

                // Previous dues (before latest month)
                $previousDue = DueBill::where('user_ip_number_id', $ipId)
                    ->where('month', '<', $latestMonth)
                    ->where('payment_status', 'unpaid')
                    ->sum('total');

                // Total due
                $total = $currentDue + $previousDue;

                $link = route('bill_pay', ['number' => $ip->number]);

                $message = "আপনার {$ip->number} আইপি নাম্বারের, {$latestMonth} মাসের বিল {$currentDue} টাকা।";

                if ($previousDue > 0) {
                    $message .= " পূর্বের বকেয়া বিল {$previousDue} টাকা। সর্বমোট: {$total} টাকা।";
                }

                $message .= "\nপেমেন্ট করতে ক্লিক করুন {$link}\n";

                $messageParts[] = $message;
            }

            $finalMessage = "আসসালামু আলাইকুম, প্রিয় গ্রাহক\n" . implode("\n", $messageParts) . "\n\nঅনুগ্রহ করে আগামী সাত দিনের মধ্যেই বিল পরিশোধ করুন।";

            if ($this->type === 'sms' && $user->phone_sms) {
                $result = netsmsbd_sms_send(number_validation($user->phone), $finalMessage);
                Log::info('SMS sent', [
                    'user_id' => $user->id,
                    'phone' => $user->phone,
                    'message' => $finalMessage,
                    'status' => $result ? 'success' : 'failed',
                ]);
            } elseif ($this->type === 'whatsapp' && $user->whatsapp_sms) {
                $wa_number = "$user->whatsapp_country_code" . "$user->whatsapp_number";
                wa_cloud_sms_send($wa_number, $finalMessage);

            }
        }
    }
}
