<!-- Start Gradient Funfacts Area -->
<div class="funfacts-area py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">

            <div class="col-md-4">
                <div class="counter-card">
                    <div class="counter-number">{{bn_number(number_format(getSetting('total_user')))}}</div>
                    <div class="counter-label">মোট গ্রাহক</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="counter-card">
                    <div class="counter-number">{{bn_number(number_format(getSetting('total_deposit')))}} টাকা</div>
                    <div class="counter-label">মোট ডিপোজিট</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="counter-card">
                    <div class="counter-number">{{bn_number(number_format(getSetting('total_withdraw')))}} টাকা</div>
                    <div class="counter-label">মোট উত্তলন</div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Gradient Funfacts Area -->
