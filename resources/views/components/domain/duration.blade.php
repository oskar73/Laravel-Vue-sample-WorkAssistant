@php $totalPrice = 0; @endphp
@foreach($duration as $item)
<div class="position-relative search_result_tr enabled" >
    <div class="d_name">
        {{$item->Duration}} {{ucfirst($item->DurationType)}}
    </div>
    <div class="ml-auto">
        <div class="d_price">
            ${{formatNumber($item->sumPrice)}} <span class="font-small">(+${{formatNumber($item->totalPrice)}})</span>
        </div>
        <div class="d_purchase">
            <a href="#/contacts" class="tab-link btn btn-success duration_btn p-2" data-duration="{{$item->Duration}}" >
                <i class="fa fa-cart-plus"></i>  Purchase
            </a>
        </div>
    </div>
</div>
@endforeach