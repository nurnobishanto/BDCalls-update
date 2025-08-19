<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Payment;
use App\Models\Recharge;
use App\Services\PaymentService;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function order_pay($id, Request $request)
    {
        $defaultMethod = match (true) {
//            env('BKASH_PAYMENT') => 'bkash',
//            env('SSLCZ_PAYMENT') => 'sslcommerz',
//            env('UDDOKTAPAY_PAYMENT') => 'uddoktapay',
            env('MANUAL_PAYMENT') => 'manual',
            default => null,
        };

        $payment_method = $request->input('payment_method', $defaultMethod);
        if (is_null($payment_method)) {
            alert()->error('You must to select a payment method.');
            return redirect()->back();
        }
        $user = Auth::guard('web')->user();
        if (!$user) {
            return redirect(route('login'));
        }
        $order = Order::find($id);
        if ($order) {
            if ($order->status == 'paid') {
                return redirect(route('order_details', ['id' => $order->id]));
            } else {
                $payment = Payment::where('order_id', $order->id)->where('status', 'pending')->where('payment_method', $payment_method)->first();
                if (!$payment) {
                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'amount' => $order->total,
                        'payment_method' => $payment_method,
                        'status' => 'pending',
                        'request' => json_encode($request->all()),
                        'response' => null,
                    ]);
                }else{
                    $payment->amount = $order->total;
                    $payment->update();
                }


                return PaymentService::handlePayment($payment);
            }

        }
        abort(404);
    }

    function order_details($id)
    {
        $order = Order::find($id);
        SEOTools::setTitle("Order Details");
        if ($order) {
            return view('website.orders.details', compact(['order']));
        }
        abort(404);
    }
    public function order_paid(Payment $payment): bool
    {
        if ($payment->status == 'paid') {
            $order = Order::find($payment->order_id);
            if ($order) {
                if ($order->status != 'paid') {
                    foreach ($order->items as $item) {
                        if($item->item_type == 'App\Models\Recharge'){
                            $recharge = Recharge::find($item->item_id);
                            $recharge->payment_method = $order->payment_method;
                            $recharge->status = 'in-progress';
                            $recharge->payment_status = 'paid';
                            $recharge->payment_response = json_encode($payment);
                            $recharge->update();
                        }
                    }
                    $order->status = 'paid';
                    $order->update();
                } else {
                    return true;
                }
            } else {
                Log::warning('Order not found for payment', [
                    'payment_id' => $payment->id
                ]);
                return false;
            }
        } else {
            Log::warning('Payment status is not paid', [
                'payment_id' => $payment->id,
                'status' => $payment->status
            ]);
            return false;
        }

        Log::error('Unreachable fallback reached in order_paid', [
            'payment_id' => $payment->id
        ]);
        return false;
    }
}
