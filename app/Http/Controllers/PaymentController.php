<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ManualPaymentGateway;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function manual_payment($payment){

        $gateways = ManualPaymentGateway::where('status', 1)->get();
        return view('website.payments.manual_payment', compact(['payment','gateways']));
    }
    public function manual_payment_submit($payment_id,Request $request){
        // First, find the payment and the selected gateway
        $payment = Payment::findOrFail($payment_id);

        $gateway = ManualPaymentGateway::where('id', $request->gateway_id)
            ->where('status', 1)
            ->first();

        if (!$gateway) {
            return back()->withErrors(['gateway_id' => 'Invalid or inactive payment gateway selected'])->withInput();
        }

        // Validation rules vary depending on gateway type
        $rules = [
            'gateway_id' => 'required|exists:manual_payment_gateways,id',
        ];

        if ($gateway->type === 'mobile') {
            // For mobile_bank: require sender number & transaction id
            $rules['sender_number'] = 'required|string|max:20';
            $rules['transaction_id'] = 'required|string|max:50';
        } elseif ($gateway->type === 'bank') {
            // For bank: require file upload for proof
            $rules['transaction_file'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'; // max 2MB
        }
        $messages = [
            'gateway_id.required' => 'অনুগ্রহ করে পেমেন্ট গেটওয়ে নির্বাচন করুন।',
            'gateway_id.exists' => 'অবৈধ পেমেন্ট গেটওয়ে নির্বাচন করা হয়েছে।',
            'sender_number.required' => 'যে গেটওয়ে থেকে সেন্ড করেছেন সেটার নম্বর দিন।',
            'sender_number.max' => 'গেটওয়ে নম্বর সর্বোচ্চ ২০ অক্ষর হতে পারে।',
            'transaction_id.required' => 'অনুগ্রহ করে ট্রানজেকশন আইডি দিন।',
            'transaction_id.max' => 'ট্রানজেকশন আইডি সর্বোচ্চ ৫০ অক্ষর হতে পারে।',
            'transaction_file.required' => 'অনুগ্রহ করে লেনদেনের প্রমাণ ফাইল আপলোড করুন।',
            'transaction_file.mimes' => 'ফাইলের ধরন হতে হবে jpg, jpeg, png, অথবা pdf।',
            'transaction_file.max' => 'ফাইলের সর্বোচ্চ আকার হতে হবে ৫০০ কিলোবাইট।',
        ];
        $validated = $request->validate($rules,$messages);

        $manualPaymentData = [
            'gateway_id' => $gateway->id,
            'gateway_name' => $gateway->name,
        ];

        if ($gateway->type === 'mobile') {
            $manualPaymentData['sender_number'] = $validated['sender_number'];
            $manualPaymentData['transaction_id'] = $validated['transaction_id'];
        } elseif ($gateway->type === 'bank') {
            if ($request->hasFile('transaction_file')) {
                $filePath = $request->file('transaction_file')->store('manual_payments', 'public');
                $manualPaymentData['transaction_file'] = $filePath;
            }
        }
        $payment->response = json_encode($manualPaymentData);
        $payment->update();
        alert()->success('পেমেন্ট ইনফরমেশন সফলভাবে জমা দেওয়া হয়েছে। অপেক্ষা করুন অথবা সাপোর্টে যোগাযোগ করুন।');

        return 'পেমেন্ট ইনফরমেশন সফলভাবে জমা দেওয়া হয়েছে। অপেক্ষা করুন অথবা সাপোর্টে যোগাযোগ করুন।';
        //return redirect(route('order_details',['id'=>$payment->order_id]));
    }

}
