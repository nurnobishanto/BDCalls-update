@extends('layouts.master')

@section('content')
    <!-- Start Page Title Area -->
    @include('website.partials.page_header',['title'=>$page->title])
    <!-- End Page Title Area -->
    <section class="ptb-100">
        <div class="container">
            {!! $page->body !!}
        </div>
    </section>
@endsection
@section('custom_css')
@endsection
@section('custom_js')
@endsection

