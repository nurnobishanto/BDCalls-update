<div class="navbar-area ">
    <div class="pakap-responsive-nav">
        <div class="container">
            <div class="pakap-responsive-menu">
                <div class="logo">
                    <a href="{{route('home')}}">
                        <img src="{{asset('uploads/'.getSetting('site_logo'))}}" alt="{{getSetting('site_title')}}" style="max-height: 40px">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="pakap-nav">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="{{route('home')}}">
                    <img src="{{asset('uploads/'.getSetting('site_logo'))}}" alt="{{getSetting('site_title')}}" style="max-height: 40px">
                </a>
                <div class="collapse navbar-collapse mean-menu">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{route('home')}}" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('business_idea')}}" class="nav-link @if(request()->routeIs('business_idea')) active @endif">Business Idea</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('apply')}}" class="nav-link @if(request()->routeIs('apply')) active @endif">Apply Now</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('tutorial')}}" class="nav-link @if(request()->routeIs('tutorial')) active @endif">Tutorial</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('support')}}" class="nav-link @if(request()->routeIs('support')) active @endif">Support</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('contact')}}" class="nav-link @if(request()->routeIs('contact')) active @endif">Contact</a>
                        </li>
                    </ul>
                    <div class="others-option">
                        <a href="{{route('apply')}}" type="button"  class="default-btn">Apply Now</a>
                        <a href="{{route('support')}}" type="button"  class="default-btn">Support</a>

                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
