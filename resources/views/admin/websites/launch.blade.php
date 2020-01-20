<div class="text-center pt-5">
    <h1 class="fs-3-5"><b>Your website is successfully launched!</b></h1>

    <p class="mt-5 mb-3">Status: {{ucfirst($website->status)}}</p>
    @if($website->status==='active')
        <a href="//{{$website->domain}}" target="_blank">{{$website->domain}} <i class="fa fa-external-link-alt"></i></a>
    @endif
</div>
