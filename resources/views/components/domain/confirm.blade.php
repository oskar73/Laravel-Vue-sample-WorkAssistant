<div class="row">
    @php
        $domain = Session::get("pickDomain");
        $duration = Session::get("duration");
        $tldRecord = Session::get("tldRecord");
    @endphp
    <div class="col-md-6 offset-md-3">
        <div class="confirm_detail">
            <h4>Domain Name: <b>{{Session::get("pickDomain")}}</b> </h4><br>
            <h4>Duration: <b>{{Session::get("duration")}} Years</b></h4><br>

            @if($tldRecord->WhoisVerification=='true')
                <span class="c-badge c-badge-success">Free WhoisGuard</span>  <br><br>
            @endif

            <h4>Total Price: <b>${{formatNumber(\App\Models\DomainPrice::where('Action', 'register')
            ->where('tld', getDomainTld($domain))
            ->where('Duration', $duration)
            ->where('status', 1)
            ->first()->sumPrice?? '0')}}</b></h4><br>

            <label class="m-checkbox m-checkbox--state-success mt-2">
                <input type="checkbox" name="registerBiz" id="registerBiz" checked /> register this domain for Bizinabox website.
{{--                <i class="fa fa-info-circle tooltip_3" title="If you check this, default cname of domain will point to Bizinabox server. --}}
{{--                <br>If you don't check this field, you will be able to use this domain for other purpose."></i>--}}
                <span></span>
            </label>
            <br>
            <br>
            <button type="button" class="btn btn-success tw-bg-green-600 getNowBtn">Get Now</button>
        </div>
    </div>
</div>
