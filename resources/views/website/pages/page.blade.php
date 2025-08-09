@extends('layouts.master')

@section('content')
    <!-- Start Page Title Area -->

    <!-- End Page Title Area -->
    <section class="ptb-100">
        <h2 class="text-center">{!! $page->title !!}</h2>
        <div class="container">
            {!! $page->body !!}
        </div>
    </section>
@endsection
@section('custom_css')
@endsection
@section('custom_js')
@endsection

