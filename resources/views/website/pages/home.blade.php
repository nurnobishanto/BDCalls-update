@extends('layouts.master')

@section('content')
    @if(sliders()->count())
        <div class="splide slider" role="group" aria-label="Homepage banners">
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach (sliders() as $slide)
                        <li class="splide__slide">
                            @if ($slide->link)
                                <a href="{{ $slide->link }}" class="d-block w-100 h-100">
                                    @endif
                                    {{-- Fallback when no SD image exists --}}
                                    <img class="img-fluid w-100" loading="lazy" style="max-height: 450px"
                                         src="{{ $slide->image }}" alt="Slide image">
                                    @if ($slide->link)
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <section class="animated-section">
        <div class="particle-canvas"></div>

        <div class="container animated-content">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-8">
                    <h2 class="fw-bold">সাধারণ প্রশ্ন ও সম্ভাব্য উত্তর ❓</h2>
                </div>
            </div>

            <div class="row gx-4 gy-0 gy-md-4">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="accordion" id="accordionLeft">
                        @foreach($firstHalf as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingLeft{{ $faq->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseLeft{{ $faq->id }}" aria-expanded="false"
                                            aria-controls="collapseLeft{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapseLeft{{ $faq->id }}" class="accordion-collapse collapse"
                                     aria-labelledby="headingLeft{{ $faq->id }}" data-bs-parent="#accordionLeft">
                                    <div class="accordion-body">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="accordion" id="accordionRight">
                        @foreach($secondHalf  as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingRight{{ $faq->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseRight{{ $faq->id }}" aria-expanded="false"
                                            aria-controls="collapseRight{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapseRight{{ $faq->id }}" class="accordion-collapse collapse"
                                     aria-labelledby="headingRight{{ $faq->id }}" data-bs-parent="#accordionRight">
                                    <div class="accordion-body">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@section('custom_css')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
    <!-- jParticles -->
    <script src="https://unpkg.com/jparticles/browser/jparticles.base.js"></script>
    <script src="https://unpkg.com/jparticles/browser/particle.js"></script>
    <style>
        /* Container for FAQ and particles */
        .animated-section {
            position: relative;
            background-color: #f0f8ff;
            overflow: hidden;
        }

        /* Particle canvas wrapper */
        .particle-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none; /* particles don’t block clicks */
        }

        /* Optional overlay to soften particles */
        .faq-animated::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            z-index: 1;
            pointer-events: none;
        }

        /* FAQ content on top */
        .animated-content {
            position: relative;
            z-index: 2;
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        /* Ensure accordion button text is dark */
        .accordion-button {
            color: #212529;
            font-weight: 500;
        }

        .scroll-offset {
            scroll-margin-top: 80px; /* Adjust this to your needed top space */
        }
    </style>

    <style>
        .counter-section {
            background: #f8f9fa;
            padding: 60px 15px;
            border-radius: 12px;
        }

        .counter-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgb(0 0 0 / 0.1);
            text-align: center;
            padding: 40px 20px;
            transition: transform 0.3s ease;
            cursor: default;
        }

        .counter-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgb(0 123 255 / 0.25);
        }

        .counter-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d6efd; /* Bootstrap primary blue */
            margin-bottom: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .counter-label {
            font-size: 1.25rem;
            font-weight: 600;
            color: #212529;
        }

        .total-label {
            font-size: 1.5rem;
            font-weight: 700;
            color: #198754;
        }
    </style>
@endsection

@section('custom_js')
    <!-- jParticles init -->
    <script>
        new JParticles.Particle('.particle-canvas', {
            color: '#007bff',
            range: 800,
            maxParticles: 120,
            proximity: 100,
            parallax: true,
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var splide = new Splide('.slider', {
                type: 'loop',
                autoplay: 'play',
                perPage: 1,
                breakpoints: {
                    700: {
                        perPage: 1,
                    },
                },
            });
            splide.mount();

        });

    </script>

@endsection
