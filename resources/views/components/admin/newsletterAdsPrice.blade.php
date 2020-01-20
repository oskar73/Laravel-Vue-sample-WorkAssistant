@forelse($prices as $price)
    <tr >
        <td>${{$price->price}}</td>
        <td>
            @if($price->slashed_price)
                <span class="slashed_price_text">${{$price->slashed_price}}</span>
            @endif
        </td>
        <td>
            {{ucfirst($price->type)}}
        </td>
        <td>
            @if($price->type=='period')
              {{$price->period_name}}
            @else
                {{$price->impression}}
            @endif
        </td>
        <td>
            @if($price->standard===1)
                <span class="c-badge c-badge-success">Standard</span>
            @endif
        </td>
        <td>
            @if($price->status===1)
                <span class="c-badge c-badge-success">Active</span>
            @else
                <span class="c-badge c-badge-danger" >InActive</span>
            @endif
        </td>
        <td>
            <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm m-1	p-2 m-btn m-btn--icon editPriceBtn" data-price="{{$price}}">
                Edit Price
            </a>
            <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon delPriceBtn" data-id="{{$price->id}}">
                Delete Price
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7">No record</td>
    </tr>
@endforelse
