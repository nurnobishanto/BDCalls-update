@extends('layouts.master')

@section('content')

    <!-- End Page Title Area -->
    <section class="py-4">
        <div class="container">
            <h2 class="text-center">Pay Your IP Number Due Bill</h2>
            <div class="row gy-4 justify-content-center align-items-center">
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
                        <div class="card shadow border-0 mt-3">
                            <div class="card-body">
                                <h6 class="card-title mb-2 text-primary fw-bold">
                                    IP Number: <span class="text-dark">{{ $number->number }}</span>
                                </h6>
                                <p class="mb-0"><strong>User:</strong> {{ $number->user?->name ?? '-' }}</p>
                                <p class="mb-0"><strong>Package:</strong> {{ $number->package?->name ?? '-' }}
                                </p>
                                <p class="mb-0 fw-bold fs-4 text-danger">Total
                                    Due: {{ $number->dueBills->where('payment_status','unpaid')->sum('total') }}</p>
                                <a href="#" class="btn btn-sm btn-success w-100 mt-2">Pay Bill</a>
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

