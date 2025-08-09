<div class="navbar-area shadow">
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
                            <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) active @endif"><i class="fa fa-home me-2 d-inline-block d-lg-none"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('search_number') }}" class="nav-link @if(request()->routeIs('search.number')) active @endif"><i class="fa fa-search me-2 d-inline-block d-lg-none"></i> Search Number</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('apply_number') }}" class="nav-link @if(request()->routeIs('apply.number')) active @endif"><i class="fa fa-edit me-2 d-inline-block d-lg-none"></i> Apply Number</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('package') }}" class="nav-link @if(request()->routeIs('package')) active @endif"><i class="fa fa-boxes-packing me-2 d-inline-block d-lg-none"></i> Package</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('recharge') }}" class="nav-link @if(request()->routeIs('recharge')) active @endif"><i class="fa fa-bolt me-2 d-inline-block d-lg-none"></i> Recharge</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bill_pay') }}" class="nav-link @if(request()->routeIs('bill.pay')) active @endif"><i class="fa fa-file-invoice-dollar me-2 d-inline-block d-lg-none"></i> Bill Pay</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('minute_bundle') }}" class="nav-link @if(request()->routeIs('minute.bundle')) active @endif"><i class="fa fa-phone-volume me-2 d-inline-block d-lg-none"></i> Minute Bundle</a>
                        </li>
                    </ul>

                    <div class="others-option">
                        <a href="{{route('apply_number')}}" type="button"  class="default-btn py-1 px-3 d-inline-block d-lg-none">Apply Number</a>
                        <a href="{{route('bill_pay')}}" type="button"  class="default-btn py-1 px-3 d-inline-block d-lg-none">Bill Pay</a>
                        <a href="{{url('user')}}" type="button"  class="default-btn py-1 px-3 d-none d-sm-inline-block">{{auth()->check() ? "Profile":"Login"}}</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
