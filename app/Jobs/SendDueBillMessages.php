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
                $ip = $ipRecords->first()->userIpNumber;
                $month = $ipRecords->first()->month;
                $currentDue = $ipRecords->where('month', $month)->sum('total');
                $previousDue = $ipRecords->sum('total') - $currentDue;
                $total = $currentDue + $previousDue;

                $link = route('bill_pay', ['number' => $ip->number]);

                $message = "আপনার {$ip->number} আইপি নাম্বারের, {$month} মাসের বিল {$currentDue} টাকা।";

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
