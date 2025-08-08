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
                            <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) active @endif">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('search_number') }}" class="nav-link @if(request()->routeIs('search.number')) active @endif">Search Number</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('apply_number') }}" class="nav-link @if(request()->routeIs('apply.number')) active @endif">Apply Number</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('package') }}" class="nav-link @if(request()->routeIs('package')) active @endif">Package</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('recharge') }}" class="nav-link @if(request()->routeIs('recharge')) active @endif">Recharge</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bill_pay') }}" class="nav-link @if(request()->routeIs('bill.pay')) active @endif">Bill Pay</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('minute_bundle') }}" class="nav-link @if(request()->routeIs('minute.bundle')) active @endif">Minute Bundle</a>
                        </li>
                    </ul>

                    <div class="others-option d-none">
                        <a href="{{route('apply_number')}}" type="button"  class="default-btn">Apply Number</a>
                        <a href="{{route('bill_pay')}}" type="button"  class="default-btn">Bill Pay</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
