@extends('layouts.master')
@section('content')

    <section class="py-3 py-md-5">
        <div class="container">
            <div class="card shadow rounded">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-light"><i class="ri-file-list-2-line me-1"></i> Order Details</h5>
                    <span class="badge bg-light text-dark">{{ strtoupper($order->status) }}</span>
                </div>

                <div class="card-body">
                    <h6 class="mb-3">Invoice #: <strong>{{ $order->invoice_no }}</strong></h6>

                    @php $billing = $order->billing_details; @endphp

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="ri-information-line me-1"></i> Order Items</h6>
                            @foreach($order->items as $item)
                                @php $product = $item->item; @endphp
                                <p class="m-0 p-0">
                                    <strong>{{ $product->name ?? $product->title ?? 'Item' }}</strong>
                                    - Quantity: {{ $item->quantity }}
                                    - Price: ৳{{ number_format($item->price, 2) }}
                                </p>
                            @endforeach
                            <p class="mt-2"><strong>Total:</strong> ৳{{ number_format($order->total, 2) }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6><i class="ri-user-line me-1"></i> Billing Details</h6>
                            <p class="m-0 p-0"><strong>Name:</strong> {{ $billing['name'] ?? '' }}</p>
                            <p class="m-0 p-0"><strong>Email:</strong> {{ $billing['email'] ?? '' }}</p>
                            <p class="m-0 p-0"><strong>Phone:</strong> {{ $billing['phone'] ?? '' }}</p>
                            <p class="m-0 p-0"><strong>IP Number:</strong> {{ $billing['ip_number'] ?? '' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="m-0 p-0"><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                            <p class="m-0 p-0"><strong>Transaction ID:</strong> {{ $order->transaction_id ?? 'N/A' }}</p>
                            <p class="m-0 p-0"><strong>Paid At:</strong> {{ $order->paid_at?->format('d M Y, h:i A') ?? 'Not Paid' }}</p>
                        </div>

                        <div class="col-md-6 text-end">
                            @if($order->status === 'pending')
                                <form id="checkoutForm" method="POST" action="{{ route('order_pay',['id'=>$order->id]) }}">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                                    <div class="form-check mt-3 text-start">
                                        <input class="form-check-input" type="checkbox" name="terms" id="terms">
                                        <label class="form-check-label" for="terms">
                                            I agree to the
                                            <a href="{{ route('slug',['terms-and-conditions']) }}" target="_blank">Terms</a>,
                                            <a href="{{ route('slug',['privacy-policy']) }}" target="_blank">Privacy Policy</a>, and
                                            <a href="{{ route('slug',['refund-policy']) }}" target="_blank">Refund Policy</a>.
                                        </label>
                                    </div>

                                    <h4 class="text-start mt-2">Pay with :</h4>
                                    <div class="mt-1 d-flex gap-1 justify-content-start">
                                        @if(env('BKASH_PAYMENT'))
                                            <button type="submit" name="payment_method" value="bkash" class="btn btn-danger">
                                                <i class="ri-bank-card-line me-1"></i>Bkash
                                            </button>
                                        @endif
                                        @if(env('SSLCZ_PAYMENT'))
                                            <button type="submit" name="payment_method" value="sslcommerz" class="btn btn-primary">
                                                <i class="ri-global-line me-1"></i> Card/Mobile Banking
                                            </button>
                                        @endif
                                        @if(env('UDDOKTAPAY_PAYMENT'))
                                            <button type="submit" name="payment_method" value="uddoktapay" class="btn btn-primary">
                                                <i class="ri-money-dollar-box-line me-1"></i> Mobile Banking
                                            </button>
                                        @endif
                                        @if(env('MANUAL_PAYMENT'))
                                            <button type="submit" name="payment_method" value="manual" class="btn btn-primary">
                                                <i class="ri-money-dollar-box-line me-1"></i> Manual Payment
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            @elseif($order->status === 'paid')
                                <span class="btn btn-success disabled"><i class="ri-check-double-line me-1"></i> Payment Successful</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <h6><i class="ri-time-line me-1"></i> Payment History</h6>
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th>Transaction</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->payments as $payment)
                            <tr>
                                <td>{{ $payment->transaction_id }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>{{ $payment->status }}</td>
                                <td>{{ $payment->updated_at->format('d M Y h:i A') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom_js')
    <script>
        document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                e.preventDefault();
                Swal.fire({
                    title: 'Terms Not Accepted',
                    text: 'You must agree to the Terms and Policies before proceeding.',
                    icon: 'warning',
                    confirmButtonText: 'I Agree',
                }).then(result => {
                    if (result.value) {
                        terms.checked = true;
                    }
                });
            }
        });
    </script>
@endsection
