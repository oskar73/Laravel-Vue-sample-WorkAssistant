<div class="position-relative search_result_tr
    @if($Available=="false"||$IsPremiumName=="true") disabled @else enabled @endif
    " >
    <div class="d_name">
        {{$Domain}}  
    </div>
    <div class="d_available">
        @if($Available=="true")
            <div class="c-badge ml-1 c-badge-success"> Available</div>

            @if($IsPremiumName=="true")<div class="c-badge c-badge-success ">Premium</div> @endif

            @if(\App\Models\DomainTld::where("Name", getDomainTld($Domain))->first()->WhoisVerification=="true")
                <div class="c-badge c-badge-info ml-1">Free WhoisGuard</div>
            @endif

        @else 

            <div class="c-badge ml-1 c-badge-danger"> Unavailable</div>

        @endif
        
    </div>
    <div class="ml-auto">
        <div class="d_price">
            @if($IsPremiumName=="true") ${{formatNumber($PremiumRegistrationPrice)}}
            @else ${{formatNumber(\App\Models\DomainPrice::where("tld", getDomainTld($Domain))->where('Action', "register")->where('Duration', 1)->first()->totalPrice?? 0)}}
            @endif
        </div>
        <div class="d_purchase">
            @if($Available=="false"||$IsPremiumName=="true")
            <a href="javascript:void(0);" class=" btn btn-info p-2 hover-prevent"  disabled>
              <i class="fa fa-cart-plus"></i>  Purchase
            </a>
            @else
                <a href="#/duration" class="tab-link btn btn-success purchase_btn p-2" data-domain="{{$Domain}}">
                    <i class="fa fa-cart-plus"></i>  Purchase
                </a>
            @endif
        </div>
    </div>
</div>