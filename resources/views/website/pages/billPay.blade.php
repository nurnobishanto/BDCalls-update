@extends('layouts.master')

@section('content')
    <!-- Start Page Title Area -->
    @include('website.partials.page_header',['title'=>'Pay Your IP Number Due Bill'])
    <!-- End Page Title Area -->
    <section class="ptb-100">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-md-6">
                    <form class="number-search-form" action="" method="GET">
                        <input type="text" name="number" value="" placeholder="096XXXXXXXX">
                        <button type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_css')
@endsection
@section('custom_js')
@endsection

