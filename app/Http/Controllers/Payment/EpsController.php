<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Payment;
use App\Services\EpsPaymentService;
use Illuminate\Http\Request;

class EpsController extends Controller
{
    protected EpsPaymentService $eps;

    public function __construct(EpsPaymentService $eps)
    {
        $this->eps = $eps;
    }

    // Get EPS Token
    public function getToken()
    {
        $username = config('eps.username'); // set in .env
        $password = config('eps.password');

        $tokenResponse = $this->eps->getToken($username, $password);
        return $tokenResponse;
    }

    // Initialize Payment
    public function PayWithEps($payment)
    {
        $tokenResponse = $this->getToken();
        $token = $tokenResponse['token'] ?? null;

        if (!$token) {
            return response()->json(['error' => 'Token generation failed'], 500);
        }

        $order =  Order::find($payment->order_id);
        $data = [
            'merchantId' => config('eps.merchant_id'),
            'storeId' => config('eps.store_id'),
            'CustomerOrderId' => $order->invoice_no . '_' . now()->timestamp,
            'merchantTransactionId' => $payment->transaction_id. '_' . now()->timestamp,
            'transactionTypeId' => 1, // Web
            'totalAmount' => $payment->amount,
            'successUrl' => route('eps.success'),
            'failUrl' => route('eps.fail'),
            'cancelUrl' => route('eps.cancel'),
            'customerName' => $payment->user?->name ?? 'Customer Name',
            'customerEmail' => $payment->user?->email ?? 'customer@bdcalls.com',
            'customerAddress' => "Mirpur",
            'customerCity' => "Dhaka",
            'customerCountry' => 'BD',
            'customerPhone' => $payment->user?->phone ?? '017xxxxxxxx',
            'ProductName' => "IP Service",
            'ProductProfile' => 'general',
            'ProductCategory' => 'Service',
            'ProductList' =>  [],
            'ValueA' =>  "$payment->id",
            'ValueB' =>  "$order->id",
            'ValueC' =>  null,
            'ValueD' =>  null,
        ];

        $initResponse = $this->eps->initializePayment($data, $token);
        if (!empty($initResponse['RedirectURL'])) {
            return redirect()->to($initResponse['RedirectURL']); // 🔹 redirect user
        }

        alert()->error($initResponse['ErrorMessage'] ?? 'Payment initialization failed');
        return redirect()->route('order_details', ['id' => $payment->order_id]);
    }



    public function success(Request $request)
    {
        $tokenResponse = $this->getToken();
        $token = $tokenResponse['token'] ?? null;

        $merchantTransactionId = $request->query('MerchantTransactionId', '');

        $data = $this->eps->verifyTransaction($merchantTransactionId, $token);
        $transaction_id = explode('_', $merchantTransactionId)[0] ?? null;
        $payment = Payment::where('transaction_id', $transaction_id)->first();
        if ($data['Status'] == "Success"){

            $payment->payment_method = $data['FinancialEntity'];
            $payment->status = 'completed';
            $payment->response = json_encode($data);
            $payment->update();
            $order = new OrderController();
            $order->order_paid($payment);
            alert()->success('Payment Completed');
            return redirect()->route('order_details', ['id' => $payment->order_id]);
        }else{
            alert()->error('Payment issue, Contact with system admin');
            return redirect()->route('order_details', ['id' => $payment->order_id]);
        }
    }
    public function fail(Request $request)
    {
        // Get the merchantTransactionId from query parameters
        $merchantTransactionId = $request->query('MerchantTransactionId', '');
        $transaction_id = explode('_', $merchantTransactionId)[0] ?? null;
        $payment = Payment::where('transaction_id', $transaction_id)->first();
        alert()->error('Payment failed');
        return redirect()->route('order_details', ['id' => $payment->order_id]);
    }
    public function cancel(Request $request)
    {
        // Get the merchantTransactionId from query parameters
        $merchantTransactionId = $request->query('MerchantTransactionId', '');
        $transaction_id = explode('_', $merchantTransactionId)[0] ?? null;
        $payment = Payment::where('transaction_id', $transaction_id)->first();
        alert()->error('Payment canceled');
        return redirect()->route('order_details', ['id' => $payment->order_id]);
    }
}
