<div class="footer-wrap-area pt-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class="single-footer-widget">
                    <a href="{{route('home')}}" class="logo">
                        <img src="{{asset('uploads/'.getSetting('site_footer_logo'))}}" alt="logo" style="max-height: 60px">
                    </a>
                    <p>
                      {!! getSetting('site_description') !!}
                    </p>
                    <ul class="social-links">
                        <li><a href="https://www.facebook.com/" target="_blank"><i class="ri-facebook-fill"></i></a></li>
                        <li><a href="https://www.twitter.com/" target="_blank"><i class="ri-twitter-fill"></i></a></li>
                        <li><a href="https://www.linkedin.com/" target="_blank"><i class="ri-linkedin-fill"></i></a></li>
                        <li><a href="https://www.messenger.com/" target="_blank"><i class="ri-messenger-fill"></i></a></li>
                        <li><a href="https://www.github.com/" target="_blank"><i class="ri-github-fill"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-6">
                <div class="single-footer-widget pl-2">
                    <h3>Information</h3>
                    <ul class="links-list">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Bill Pay</a></li>
                        <li><a href="{{url('/user')}}">My Account</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-6">
                <div class="single-footer-widget">
                    <h3>Policies</h3>
                    <ul class="links-list">
                        <li><a href="{{route('slug',['slug'=>'terms-and-conditions'])}}">Terms & Conditions</a></li>
                        <li><a href="{{route('slug',['slug'=>'refund-policy'])}}">Refund Policy</a></li>
                        <li><a href="{{route('slug',['slug'=>'privacy-policy'])}}">Privacy Policy</a></li>
                        <li><a href="{{route('slug',['slug'=>'support'])}}">Help & Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                <div class="single-footer-widget">
                    <h3>Useful Links</h3>
                    <ul class="links-list">
                        <li>
                            <a href="">
                                <i class="fas fa-home me-3"></i>
                                {!! getSetting('site_address') !!}
                            </a>
                        </li>
                        <li>
                            <a href="tel:{{getSetting('site_phone')}}">
                                <i class="fas fa-phone me-3"></i>
                                {{getSetting('site_phone')}}
                            </a>
                        </li>
                        <li>
                            <a href="https://wa.me/{{ preg_replace('/\D+/', '', getSetting('site_whatsapp')) }}" target="_blank">
                                <i class="fa-brands fa-whatsapp me-3"></i>
                                {{getSetting('site_whatsapp')}}
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{getSetting('site_email')}}">
                                <i class="fas fa-envelope me-3"></i>
                                {{getSetting('site_email')}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <p>Copyright <script>document.write(new Date().getFullYear())</script> <strong>{{getSetting('site_title')}}</strong>. All Rights Reserved by <a href="https://soft-itbd.com/" target="_blank">SOFT-ITBD</a></p>

        </div>
    </div>
</div>
