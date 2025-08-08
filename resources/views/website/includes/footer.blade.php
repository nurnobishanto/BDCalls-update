<div class="footer-wrap-area pt-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
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
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="single-footer-widget pl-2">
                    <h3>Features</h3>
                    <ul class="links-list">
                        <li><a href="#">Mock Test</a></li>
                        <li><a href="#">Contest</a></li>
                        <li><a href="#">General Knowledge</a></li>
                        <li><a href="#">Leader Board</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="single-footer-widget">
                    <h3>Support</h3>
                    <ul class="links-list">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contest Us</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">FAQ's</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="single-footer-widget">
                    <h3>Useful Links</h3>
                    <ul class="links-list">
                        <li><a href="{{route('slug',['slug'=>'contest-policy'])}}">Contest Policy</a></li>
                        <li><a href="{{route('slug',['slug'=>'privacy-policy'])}}">Privacy Policy</a></li>
                        <li><a href="{{route('slug',['slug'=>'refund-policy'])}}">Refund Policy</a></li>
                        <li><a href="{{route('slug',['slug'=>'terms-and-conditions'])}}">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">

            </div>
        </div>
        <div class="copyright-area">
            <p>Copyright <script>document.write(new Date().getFullYear())</script> <strong>{{getSetting('site_title')}}</strong>. All Rights Reserved by <a href="https://soft-itbd.com/" target="_blank">SOFT-ITBD</a></p>

        </div>
    </div>
</div>
