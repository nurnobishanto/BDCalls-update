@extends('layouts.master')

@section('content')

    <!-- End Page Title Area -->
    <section class="py-4">
        <div class="container">
            <h2 class="text-center">Pay Your IP Number Due Bill</h2>
            <div class="row gy-4 justify-content-center align-items-center">
                <div class="col-12 col-md-5">
                    <form action="" method="GET" class="p-4 shadow rounded bg-white">
                        <div class="input-group">
                            <input
                                type="text"
                                name="number"
                                value="{{ request('number') }}"
                                class="form-control form-control-lg"
                                placeholder="096XXXXXXXX"
                                required
                            >
                            <button type="submit" class="btn btn-primary btn-md px-4">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                    @forelse($numbers as $number)
                        <div class="card shadow border-0 mt-3">
                            <div class="card-body">
                                <h6 class="card-title mb-2 text-primary fw-bold">
                                    IP Number: <span class="text-dark">{{ $number->number }}</span>
                                </h6>
                                <p class="mb-0"><strong>User:</strong> {{ $number->user?->name ?? '-' }}</p>
                                <p class="mb-0"><strong>Package:</strong> {{ $number->package?->name ?? '-' }}
                                </p>
                                <p class="mb-0 fw-bold fs-4 text-danger text-center">Total
                                    Due: {{ round($number->dueBills->where('payment_status', 'unpaid')->sum('total')) }}
                                </p>
                                <a href="#"
                                   data-number="{{ $number->number }}"
                                   data-amount="{{ round($number->dueBills->where('payment_status', 'unpaid')->sum('total')) }}"
                                   class="btn btn-sm btn-success w-100 mt-2 pay-bill-btn">Pay Bill</a>
                            </div>
                        </div>
                    @empty
                        @if(request('number'))
                            <div class="alert alert-warning text-center">
                                No numbers found for "{{ request('number') }}".
                            </div>
                        @endif
                    @endforelse
                </div>

            </div>
        </div>
    </section>
    <form id="billPaymentForm" action="{{ route('bill_payment') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="number" id="bill-number">
        <input type="hidden" name="amount" id="bill-amount">
        <input type="hidden" name="payment_method" id="bill-method">
    </form>
@endsection
@section('custom_css')
    <style>
        .payment-options .option {
            cursor: pointer;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 8px;
            transition: all 0.3s;
        }

        .payment-options .option:hover {
            border-color: #198754;
            background-color: #e7f7ee;
        }

        .payment-options input[type="radio"] {
            cursor: pointer;
        }
    </style>
@endsection
@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.pay-bill-btn').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    let number = this.dataset.number;
                    let amount = this.dataset.amount;

                    Swal.fire({
                        title: 'Pay Your Due Bill',
                        html: `
                    <p class="fs-6"><strong>IP Number:</strong> ${number}</p>
                    <p class="text-center text-danger fs-4 fw-bold"><strong>Total Due:</strong> ${amount} BDT</p>
                    <label for="payment-method">Select Payment Method:</label>
                    <div class="payment-options d-flex flex-column gap-3">

                        <label class="option d-flex flex-column align-items-start">
                            <span class="option-text mb-1 small">Credit/Debit Card/Bkash/Nagad (Extra Charge 2%)</span>
                            <div class="d-flex align-items-center">
                                <input type="radio" name="payment_method" value="automatic" checked class="me-2">
                                <img src="/website/img/automatic.png" style="max-width: 95%">
                            </div>
                        </label>

                        <label class="option d-flex flex-column align-items-start">
                            <span class="option-text mb-1 small">Manual Payment</span>
                            <div class="d-flex align-items-center">
                                <input type="radio" name="payment_method" value="manual" class="me-2">
                                <img src="/website/img/manual.png" style="max-width: 95%">
                            </div>
                        </label>

                    </div>

                `,
                        showCancelButton: true,
                        confirmButtonText: 'Pay Now',
                        cancelButtonText: 'Cancel',
                        focusConfirm: false,
                        preConfirm: () => {
                            const method = Swal.getPopup().querySelector('input[name="payment_method"]:checked').value;
                            return { method };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Fill hidden form and submit

                            document.getElementById('bill-number').value = number;
                            document.getElementById('bill-amount').value = amount;
                            document.getElementById('bill-method').value = result.value.method;

                            document.getElementById('billPaymentForm').submit();
                        }
                    });
                });
            });
        });
    </script>

@endsection


