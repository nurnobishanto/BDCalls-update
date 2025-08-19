<?php
namespace App\Services;
//use App\Http\Controllers\Website\BkashController;
use App\Http\Controllers\PaymentController;
//use App\Http\Controllers\Website\SslCommerzController;
//use App\Http\Controllers\Website\UddoktapayController;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public static function handlePayment(Payment $payment)
    {
        $paymentHandlers = [
//            'bkash' => [BkashController::class, 'PayWithBkash'],
//            'sslcommerz' => [SslCommerzController::class, 'PayWithSSLCOMMERZ'],
//            'uddoktapay' => [UddoktapayController::class, 'payWithUddoktapay'],
            'manual' => [PaymentController::class, 'manual_payment'],
        ];

        if (isset($paymentHandlers[$payment->payment_method]) && class_exists($paymentHandlers[$payment->payment_method][0])) {
            [$controllerClass, $method] = $paymentHandlers[$payment->payment_method];
            $controller = app()->make($controllerClass); // Use Laravel's service container
            if (method_exists($controller, $method)) {
                return $controller->$method($payment);
            } else {
                Log::error("Method {$method} does not exist in {$controllerClass}");
            }
        } else {
            Log::error("Payment handler not found for method: {$payment->payment_method}");
        }
        alert()->error('Invalid payment method');
        return redirect(route('order_details', ['id' => $payment->order_id]));
    }
}
