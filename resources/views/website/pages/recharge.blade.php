@extends('layouts.master')

@section('content')

    <!-- End Page Title Area -->
    <section class="py-md-5 py-3 bg-light">
        <div class="container">
            <h2 class="text-center">Recharge Your IP Number</h2>
            <div class="row  gy-4 justify-content-center align-items-center">
                <div class="col-12 col-md-5">
                    <form action="" method="GET" class="p-4 shadow rounded bg-white">
                        <div class="mb-3 text-center">
                            <h5 class="fw-bold mb-0">Search Number</h5>
                        </div>

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
                                <h6 class="card-title mb-2 text-primary fw-bold">
                                    IP Number: <span class="text-dark">{{ $number->number }}</span>
                                </h6>
                                <p class="mb-1"><strong>User:</strong> {{ $number->user?->name ?? '-' }}</p>
                                <p class="mb-3"><strong>Package:</strong> {{ $number->package?->name ?? '-' }}</p>
                                <a href="#" class="btn btn-sm btn-success w-100 btn-recharge"
                                   data-id="{{ $number->id }}"
                                   data-number="{{ $number->number }}"
                                >Recharge</a>
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
                    <div style="text-align:center; margin-top:15px;">
                        <label style="margin-right:15px;">
                            <input type="radio" name="payment_method" value="manual" checked> Manual
                        </label>
                        <label>
                            <input type="radio" name="payment_method" value="automatic"> Automatic
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
                        if (result.isConfirmed) {
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


