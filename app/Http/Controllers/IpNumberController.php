<?php

namespace App\Http\Controllers;

use App\Models\IpNumber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Recharge;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpNumberController extends Controller
{
    public function recharge(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ip_numbers,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:manual,automatic',
        ]);

        $ipNumber = IpNumber::findOrFail($request->id);
        $user = $ipNumber->user;

        DB::transaction(function () use ($request, $ipNumber, $user) {
            // 1️⃣ Create Recharge record
            $recharge = Recharge::create([
                'user_id' => $user->id,
                'number' => $ipNumber->number,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);


            $order = Order::create([
                'user_id' => $user->id,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'total' => $request->amount,
                'billing_details' => [
                    'ip_number' => $ipNumber->number,
                    'recharge_id' => $recharge->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'phone_country_code' => $user->phone_country_code,
                    'whatsapp_number' => $user->whatsapp_number,
                    'whatsapp_country_code' => $user->whatsapp_country_code,
                ],
            ]);
            // Optional: Create OrderItem for this recharge
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $recharge->id,
                'item_type' => get_class($recharge),
                'quantity' => 1,
                'price' => $recharge->amount,
            ]);
            // Create Payment
            $payment = Payment::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'amount' => $order->amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            return PaymentService::handlePayment($payment);

        });


    }
}
