@extends('layouts.master')

@section('content')

    <!-- End Page Title Area -->
    <section class="py-4">
        <div class="container">
            <h2 class="text-center">Recharge Your IP Number</h2>
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-md-6">
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
                <div class="col-md-6">
                    @foreach($numbers as $number)
                        <div class="card mb-2">
                            <div class="card-body">
                                <p><strong>IP Number : </strong> {{$number->number}}</p>
                                <p><strong>User : </strong> {{$number->user?->name}}</p>
                                <p><strong>Package : </strong> {{$number->package?->name}}</p>
                                <a href="#" class="btn btn-sm btn-success ms-auto">Recharge</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_css')
@endsection
@section('custom_js')
@endsection

