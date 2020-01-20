<a href="{{route('cart.index')}}" class="dropdown-toggle cart_fa">
    <i class="fas fa-shopping-cart"></i>
    <span class="badge bg-theme">{{Session::get("cart")->totalQty?? 0}}</span>
</a>
<ul class="dropdown-menu cart-list" style="max-height:none;">
    @if(Session::has("cart"))
        @foreach(Session::get("cart")->items as $key=>$item)
        <li>
            <a href="#" class="photo overflow-hidden">
                <img src="{{$item['image']}}" class="cart-thumb" style="width:100%;height:auto;"/>
            </a>
            <h6><a href="#">{{$item['front']}} </a></h6>
            <p>{{$item['quantity']}}x - <span class="price">${{$item['price']}}</span></p>
{{--            <span class="position-absolute h-cursor header-cart-item-remove h_rm_btn" data-id="{{$key}}">×</span>--}}
        </li>
        @endforeach
    @endif
    <li class="total bg-theme">
        <span class="pull-left"><strong>Total</strong>: ${{Session::get("cart")->totalPrice?? 0}}</span>
        <a href="{{route('cart.index')}}" class="butn small btn-cart white"><span>View Cart</span></a>
    </li>
</ul>
