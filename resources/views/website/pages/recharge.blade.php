@extends('layouts.master')

@section('content')

    <!-- End Page Title Area -->
    <section class="py-md-4 py-3 bg-light">
        <div class="container">
            <h2 class="text-center">Recharge Your IP Number</h2>
            <div class="row  gy-4 justify-content-center align-items-center">
                <div class="col-12 col-md-5">
                    <form action="" method="GET" class="p-4 shadow rounded bg-white">
                        {{-- Display validation errors --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
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
                        <div class="card shadow border-0 h-100 mt-3">
                            <div class="card-body">
                                <h5 class="card-title mb-2 text-primary fw-bold text-center">
                                    IP Number: <span class="text-dark">{{ $number->number }}</span>
                                </h5>
                                <p class="mb-1 fs-6"><strong>User:</strong> {{ $number->user?->name ?? '-' }}</p>
                                <p class="mb-3"><strong>Package:</strong> {{ $number->package?->name ?? '-' }}</p>
                                @if(strlen($number->number) < 10)

                                    <a href="{{ route('bill_pay',['number'=>$number->number] ) }}"
                                       class="btn btn-sm btn-danger w-100 mb-2">
                                        Pay Due Bill
                                    </a>
                                @else
                                    <a href="#" class="btn btn-sm btn-success w-100 btn-recharge"
                                       data-id="{{ $number->id }}"
                                       data-number="{{ $number->number }}"
                                    >Recharge</a>
                                @endif


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
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-recharge').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const numberId = this.dataset.id;
                    const numberText = this.dataset.number;

                    Swal.fire({
                        title: `Recharge IP Number: ${numberText}`,
                        html: `
                    <input type="number" id="amount" class="swal2-input" placeholder="Enter Amount">
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
                        confirmButtonText: 'Recharge',
                        showCancelButton: true,
                        preConfirm: () => {
                            const amount = Swal.getPopup().querySelector('#amount').value;
                            const paymentMethod = Swal.getPopup().querySelector('input[name="payment_method"]:checked').value;

                            if (!amount || isNaN(amount) || Number(amount) <= 0) {
                                Swal.showValidationMessage(`Please enter a valid amount`);
                                return false;
                            }
                            return { amount: amount, payment_method: paymentMethod };
                        }
                    }).then((result) => {
                        if (result.value) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ route('recharge.ipnumber') }}";
                            form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="${numberId}">
                        <input type="hidden" name="amount" value="${result.value.amount}">
                        <input type="hidden" name="payment_method" value="${result.value.payment_method}">
                    `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>



@endsection


