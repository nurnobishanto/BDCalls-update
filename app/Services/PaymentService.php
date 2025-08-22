<?php
namespace App\Services;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Payment\BkashController;
use App\Http\Controllers\Payment\EpsController;
use App\Http\Controllers\Payment\PayStationController;
use App\Http\Controllers\PaymentController;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public static function handlePayment(Payment $payment)
    {
        if ($payment->amount == 0){
            $payment->status = 'paid';
            $payment->update();
            $orderController = new OrderController();
            $orderController->order_paid($payment);
            alert()->success('Payment has been successfully processed', 'Success');
            return redirect(route('order_details', ['id' => $payment->order_id]));
        }
        $paymentHandlers = [
            'bkash' => [BkashController::class, 'PayWithBkash'],
            'eps' => [EpsController::class, 'PayWithEps'],
            'pay_station' => [PayStationController::class, 'payWithPayStation'],
            'manual' => [PaymentController::class, 'manual_payment'],
            'automatic' => [PayStationController::class, 'payWithPayStation'],
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
