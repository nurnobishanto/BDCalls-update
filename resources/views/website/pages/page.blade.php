@extends('layouts.master')

@section('content')
    <!-- Start Page Title Area -->

    <!-- End Page Title Area -->
    <section class="ptb-100">

        <div class="container">
            <h2 class="text-center">{!! $page->title !!}</h2>
            {!! $page->body !!}
        </div>
    </section>
@endsection
@section('custom_css')
@endsection
@section('custom_js')
@endsection

