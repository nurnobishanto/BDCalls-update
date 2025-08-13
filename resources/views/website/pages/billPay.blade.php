@extends('layouts.master')

@section('content')

    <!-- End Page Title Area -->
    <section class="py-4">
        <div class="container">
            <h2 class="text-center">Pay Your IP Number Due Bill</h2>
            <div class="row gy-4 justify-content-center align-items-center">
                <div class="col-12 col-md-7">
                    <form action="" method="GET" class="p-4 shadow rounded bg-white">
                        <div class="mb-3 text-center">
                            <h5 class="fw-bold mb-0">Search Number</h5>
                            <small class="text-muted">Enter the number in the format: 096XXXXXXXX</small>
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
                </div>
                <div class="col-md-7">
                    @forelse($numbers as $number)
                        <div class="card shadow border-0 mb-3">
                            <div class="card-body">
                                <div class="row gy-3">
                                    <!-- Left Side Info -->
                                    <div class="col-md-4 border-end">
                                        <h6 class="card-title mb-2 text-primary fw-bold">
                                            IP Number: <span class="text-dark">{{ $number->number }}</span>
                                        </h6>
                                        <p class="mb-0"><strong>User:</strong> {{ $number->user?->name ?? '-' }}</p>
                                        <p class="mb-0"><strong>Package:</strong> {{ $number->package?->name ?? '-' }}
                                        </p>
                                        <p class="mb-0 fw-bold">Total
                                            Due: {{ $number->dueBills->where('payment_status','unpaid')->sum('total') }}</p>
                                        <a href="#" class="btn btn-sm btn-success w-100 mt-2">Pay Bill</a>
                                    </div>

                                    <!-- Right Side Breakdown -->
                                    <div class="col-md-8">
                                        <h6 class="text-secondary mb-2 fw-semibold">Due Bills Breakdown</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Service Charge</th>
                                                    <th>Minutes</th>
                                                    <th>Call Rate</th>
                                                    <th>Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($number->dueBills->where('payment_status', 'unpaid')->sortBy(fn($bill) => $bill->month) as $bill)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($bill->month)->format('M Y') }}</td>
                                                        <td>{{ $bill->service_charge }}</td>
                                                        <td>{{ $bill->minutes }}</td>
                                                        <td>{{ $bill->call_rate }}</td>
                                                        <td>{{ $bill->total }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">No unpaid due
                                                            bills available.
                                                        </td>
                                                    </tr>
                                                @endforelse

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
@endsection

