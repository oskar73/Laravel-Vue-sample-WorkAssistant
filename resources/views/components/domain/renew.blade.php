
<h3>Domain : <b>{{$domain->name}}</b>, Choose Renewal Duration</h3>
@foreach($domainPrices as $item)
    @if($item->Duration>=$domainTld->MinRenewYears&&$item->Duration<=$domainTld->MaxRenewYears)
    <div class="position-relative search_result_tr enabled" >
        <div class="d_name">
            {{$item->Duration}} {{ucfirst($item->DurationType)}}
        </div>
        <div class="ml-auto">
            <div class="d_price">
                ${{formatNumber($item->sumPrice)}} <span class="font-small">(+${{formatNumber($item->totalPrice)}})</span>
            </div>
            <div class="d_purchase">
                <a href="#/confirm" class="tab-link btn btn-success tw-bg-green-600 renew_confirm_btn p-2" data-area="confirm" data-duration="{{$item->Duration}}" >
                    <i class="fa fa-cart-plus"></i>  Renew Now
                </a>
            </div>
        </div>
    </div>
    @endif
@endforeach