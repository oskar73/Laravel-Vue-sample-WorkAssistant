<div class="m-radio-inline text-center d-inline-block ml-4 product_item_area">
    <a  href="{{route('admin.purchase.package.index')}}" class="m-radio hover-none">
        <input type="radio" name="type" value="package" @if($item=='package') checked @endif> Package
        <span></span>
    </a>
    <a  href="{{route('admin.purchase.readymade.index')}}" class="m-radio hover-none">
        <input type="radio" name="type" value="readymade" @if($item=='readymade') checked @endif> Ready Made Biz
        <span></span>
    </a>
    <a  href="{{route('admin.purchase.blog.index')}}" class="m-radio hover-none">
        <input type="radio" name="type" value="blog" @if($item=='blog') checked @endif> Blog Package
        <span></span>
    </a>
{{--    <a  href="{{route('admin.purchase.module.index')}}" class="m-radio hover-none">--}}
{{--        <input type="radio" name="type" value="module" @if($item=='module') checked @endif> Module--}}
{{--        <span></span>--}}
{{--    </a>--}}
    <a  href="{{route('admin.purchase.plugin.index')}}" class="m-radio hover-none">
        <input type="radio" name="type" value="plugin" @if($item=='plugin') checked @endif> Plugin
        <span></span>
    </a>
    <a  href="{{route('admin.purchase.service.index')}}" class="m-radio hover-none">
        <input type="radio" name="type" value="service" @if($item=='service') checked @endif> Service
        <span></span>
    </a>
    <a  href="{{route('admin.purchase.lacarte.index')}}" class="m-radio hover-none">
        <input type="radio" name="type" value="lacarte" @if($item=='lacarte') checked @endif> Lacarte
        <span></span>
    </a>
</div>
