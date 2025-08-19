<?php

namespace App\Http\Controllers;

use App\Models\IpNumber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Recharge;
use App\Models\UserIpNumber;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpNumberController extends Controller
{
    public function recharge(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_ip_numbers,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:manual,automatic',
        ]);

        $userIpNumber = UserIpNumber::findOrFail($request->id);
        $user = $userIpNumber->user;

        // 1️⃣ Check if a pending recharge already exists with the same number and amount
        $recharge = Recharge::where('number', $userIpNumber->number)
            ->where('amount', $request->amount)
            ->where('payment_status', 'pending')
            ->first();

        if (!$recharge) {
            // Create new Recharge if not found
            $recharge = Recharge::create([
                'user_id' => $user->id,
                'number' => $userIpNumber->number,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);
        }

        // 2️⃣ Check if an Order exists for this pending recharge
        $order = Order::whereJsonContains('billing_details->recharge_id', $recharge->id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'total' => $recharge->amount,
                'billing_details' => [
                    'ip_number' => $userIpNumber->number,
                    'recharge_id' => $recharge->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'phone_country_code' => $user->phone_country_code,
                    'whatsapp_number' => $user->whatsapp_number,
                    'whatsapp_country_code' => $user->whatsapp_country_code,
                ],
            ]);

            // Create OrderItem
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $recharge->id,
                'item_type' => get_class($recharge),
                'quantity' => 1,
                'price' => $recharge->amount,
            ]);
        }

        // 3️⃣ Check if Payment exists for this order
        $payment = Payment::where('order_id', $order->id)
            ->where('amount', $recharge->amount)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            $payment = Payment::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'amount' => $recharge->amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);
        }

        // 4️⃣ Handle payment via service
        return PaymentService::handlePayment($payment);
    }

}
