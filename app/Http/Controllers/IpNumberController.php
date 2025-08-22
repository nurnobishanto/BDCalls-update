<?php

namespace App\Http\Controllers;

use App\Models\IpNumber;
use App\Models\MinuteBundle;
use App\Models\MinuteBundlePurchase;
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
    public function bill_payment(Request $request)
    {
        // 1️⃣ Validate input
        $request->validate([
            'number' => 'required|exists:user_ip_numbers,number',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:manual,automatic',
        ]);

        $userIpNumber = UserIpNumber::where('number', $request->number)
            ->with(['user', 'dueBills' => fn($q) => $q->where('payment_status', 'unpaid')])
            ->firstOrFail();

        $user = $userIpNumber->user;
        $unpaidBills = $userIpNumber->dueBills;

        if ($unpaidBills->isEmpty()) {
            return redirect()->back()->with('info', 'No unpaid bills for this IP number.');
        }

        // 2️⃣ Find existing pending order for these bills
        $dueBillIds = $unpaidBills->pluck('id')->toArray();

        $order = Order::whereJsonContains('billing_details->due_bill_ids', $dueBillIds)
            ->where('status', 'pending')
            ->first();

        // 3️⃣ If no pending order, create one
        if (!$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'total' => round($unpaidBills->sum('total')),
                'billing_details' => [
                    'ip_number' => $userIpNumber->number,
                    'due_bill_ids' => $dueBillIds,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'phone_country_code' => $user->phone_country_code,
                    'whatsapp_number' => $user->whatsapp_number,
                    'whatsapp_country_code' => $user->whatsapp_country_code,
                ],
            ]);
            // Create order items
            foreach ($unpaidBills as $bill) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $bill->id,
                    'item_type' => get_class($bill),
                    'quantity' => 1,
                    'price' => $bill->total,
                ]);
            }
        }

        // 4️⃣ Find or create pending payment
        $payment = Payment::firstOrCreate(
            [
                'order_id' => $order->id,
                'status' => 'pending',
            ],
            [
                'user_id' => $user->id,
                'amount' => $order->total,
                'payment_method' => $request->payment_method,
            ]
        );

        // 5️⃣ Handle payment via service
        return PaymentService::handlePayment($payment);
    }
    public function searchIp(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
        ]);

        $ip = UserIpNumber::with('user')
            ->where('number', $request->number)
            ->first();

        if (!$ip) {
            return response()->json([
                'success' => false,
                'message' => 'IP number not found or has no package assigned.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $ip->id,
                'number' => $ip->number,
                'user_name' => $ip->user->name,
            ],
        ]);
    }
    public function orderMinuteBundle(Request $request)
    {
        $request->validate([
            'minute_bundle_id'         => 'required|exists:minute_bundles,id',
            'user_ip_number_id' => 'required|exists:user_ip_numbers,id',
            'payment_method'    => 'required|in:manual,automatic',
        ]);
        $userIpNumber = UserIpNumber::where('id',$request->user_ip_number_id)->first();
        $bundle = MinuteBundle::where('id',$request->minute_bundle_id)->first();
        $user = $userIpNumber->user;

        $order = Order::whereJsonContains('billing_details->user_ip_number_id', $userIpNumber->id)
            ->whereJsonContains('billing_details->minute_bundle_id', $bundle->id)
            ->where('status', 'pending')
            ->first();

        // 3️⃣ If no pending order, create one
        if (!$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'total' => $bundle->price,
                'billing_details' => [
                    'user_ip_number_id' => $userIpNumber->id,
                    'minute_bundle_id' => $bundle->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'phone_country_code' => $user->phone_country_code,
                    'whatsapp_number' => $user->whatsapp_number,
                    'whatsapp_country_code' => $user->whatsapp_country_code,
                ],
            ]);
            $mbp = MinuteBundlePurchase::create([
                'minute_bundle_id'=>$bundle->id,
                'user_id'=>$user->id,
                'user_ip_number_id'=>$userIpNumber->id,
                'price'=>$bundle->price,
                'status'=>'pending',
                'payment_status'=>'pending',
            ]);

            // Create order items
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $mbp->id,
                'item_type' => get_class($mbp),
                'quantity' => 1,
                'price' => $mbp->price,
            ]);

        }

        // 4️⃣ Find or create pending payment
        $payment = Payment::firstOrCreate(
            [
                'order_id' => $order->id,
                'status' => 'pending',
            ],
            [
                'user_id' => $user->id,
                'amount' => $order->total,
                'payment_method' => $request->payment_method,
            ]
        );

        // 5️⃣ Handle payment via service
        return PaymentService::handlePayment($payment);
    }

}
