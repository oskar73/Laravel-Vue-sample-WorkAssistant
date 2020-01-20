@if(Session::has('cart'))
    @forelse(Session::get('cart')->items as $key=>$item)
        <tr>
            <td class="product-thumbnail text-left py-4">
                <div class="w-100px overflow-hidden">
                    <x-global.aspect-view>
                        <div class="w-100 h-100 d-flex align-items-center">
                            <a href="#!" class="display-inline-block">
                                <img src="{{$item['image']}}" alt="" class="img img-fill bordered" />
                            </a>
                        </div>
                    </x-global.aspect-view>
                </div>
            </td>
            <td class="text-left vertical-middle">
                <a href="javascript:void(0);">({{moduleName($item['type']?? '')}})</a>
                <br/>
                <span class="text-uppercase display-block">{{$item['front']?? ''}}</span>
                <br/>
                <a href="{{$item['url']?? ''}}" class="font-size13"><i class="fas fa-eye"></i> View</a>
            </td>
            <td class="text-center text-nowrap">
                @if($item['recurrent']==1)
                    ${{$item['price']}} / {{periodName($item['parameter']['period'], $item['parameter']['period_unit'])}}
                    <br>
                    <span class="btn btn-sm btn-outline-info p-1 border-radius-0 mt-2 recurrent_badge">Subscription</span>
                @else
                    <span class="btn btn-sm btn-outline-info p-1 border-radius-0 recurrent_badge">Onetime</span>
                @endif
            </td>
            <td class="product-quantity">
                @if($item['type']=='blogAds'&&$item['item']['price']['type']=='period')
                    @foreach($item['parameter']['start'] as $key2=>$start)
                        {{$start}} ~ {{$item['parameter']['end'][$key2]}} <br>
                    @endforeach
                    <input type="hidden" name="items[{{$key}}]" class="fcustom-input" value="1"/>
                @else
                    {{$item['quantity']?? 0}}
                @endif
            </td>
            <td class="product-price">
                ${{formatNumber($item['price'])}}
            </td>
            <td class="product-subtotal">
                ${{formatNumber($item['price'] * $item['quantity'])}}
            </td>
            <td class="product-remove text-center">
                <a href="javascript:void(0);" data-id="{{$key}}" class="c_rm_btn text-danger font-size20 c_rm_btn">x</a>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center">No items</td></tr>
    @endforelse
@else
    <tr><td colspan="6" class="text-center">No items</td></tr>
@endif






































































