<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PayStationController extends Controller
{
    public function payWithPayStation($payment): array|\Illuminate\Http\RedirectResponse
    {
        $merchantId = env('PAY_STATION_MERCHANT_ID');
        $password   = env('PAY_STATION_PASSWORD');
        $order =  Order::find($payment->order_id);

        $payload = [
            'invoice_number' => $order->invoice_no,   // must be unique
            'currency'       => 'BDT',
            'payment_amount' => $payment->amount,
            'reference'      => $payment->reference ?? 'Payment Reference',
            'cust_name'      => $payment->user?->name ?? 'Customer Name',
            'cust_phone'     => $payment->user?->phone ?? '017xxxxxxxx',
            'cust_email'     => $payment->user?->email ?? 'customer@bdcalls.com',
            'cust_address'   => 'Dhaka, Bangladesh',
            'callback_url'   => route('pay_station.callback',['id'=>$payment->id]), // your callback route
            'checkout_items' => null,
            'merchantId'     => $merchantId,
            'password'       => $password,
            'opt_a'         =>$payment->id,
            'opt_b'         =>$order->id,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.paystation.com.bd/initiate-payment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $payload,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            alert()->error('cURL Error: ' . $err);
            return redirect()->route('order_details', ['id' => $payment->order_id]);
        }

        $result = json_decode($response, true);

        if (isset($result['status']) && $result['status'] === 'success') {
            // ✅ Success: redirect user to payment page
            return redirect()->away($result['payment_url']);
        }

        alert()->error($result['message'] ?? 'Unknown error');
        return redirect()->route('order_details', ['id' => $payment->order_id]);
    }
    public function payStationCallback(Request $request,$id)
    {
        $status        = $request->get('status');           // Successful / Failed / Canceled
        $invoiceNumber = $request->get('invoice_number');   // your unique invoice
        $trxId         = $request->get('trx_id');           // may be empty if not successful

        // ✅ First, double-check the payment status from PayStation API
        $merchantId = env('PAY_STATION_MERCHANT_ID');

        $header = [
            "merchantId: {$merchantId}"
        ];

        $body = [
            'invoice_number' => $invoiceNumber
        ];

        $curl = curl_init("https://api.paystation.com.bd/transaction-status");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $responseData = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            alert()->error('Payment verification failed. Please contact support.');
            return redirect()->route('home');
        }

        $result = json_decode($responseData, true);

        // ✅ Check response
        if (!isset($result['status_code']) || $result['status_code'] != "200") {
            alert()->error('Invalid payment verification: ' . ($result['message'] ?? 'Unknown error'));
            return redirect()->route('home');
        }

        $data = $result['data'];

        // Example: find your order by invoice_number
        $payment = Payment::where('id', $id)->first();
        $order   = Order::where('id', $payment->order_id)->first();

        if (!$payment) {
            alert()->error('Order Payment not found.');
            return redirect()->route('order_details', ['id' => $order->id]);
        }

        // ✅ Update order/payment status
        if (strtolower($data['trx_status']) === 'success') {
            $payment->status = 'paid';
            $payment->payment_method = $data['payment_method'];
            $payment->response = json_encode($data);
            $payment->update();
            $order->transaction_id = $data['trx_id'];
            $order->update();
            $orderController =  new OrderController();
            $orderController->order_paid($payment);

            alert()->success('Payment successful. Thank you!');
            return redirect()->route('order_details', ['id' => $payment->order_id]);
        } else {
            $payment->status = 'failed';
            $payment->update();

            alert()->error('Payment ' . ucfirst($data['trx_status']) . '. Please try again.');
            return redirect()->route('order_details', ['id' => $payment->order_id]);
        }
    }
}
