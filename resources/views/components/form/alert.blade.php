<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-{{$type?? 'success'}}">
    <div class="m-alert__icon">
        <i class="flaticon-exclamation-1"></i>
        <span></span>
    </div>
    <div class="m-alert__text">
        <strong>{{$title }}</strong> {{$slot}}
    </div>
</div>
