@if($userLogo->downloadable==1)
    <a href='{{route('user.logotypes.download.package', $userLogo->hash)}}' class='btn btn-outline-success btn-sm downloadPackageBtn' >Ready for Download <i class='fa fa-download'></i></a>
@elseif($userLogo->downloadable==2)
    <p>Progress: {{$userLogo->progress}} %</p>
    <div class='progress progress_el' data-id='{{$userLogo->id}}'>
        <div class="progress-bar" role="progressbar" style="width: {{$userLogo->progress}}%"
             aria-valuenow="{{$userLogo->progress}}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
@else
    <a href='{{route('user.logotypes.download.package', $userLogo->hash)}}' class='btn btn-outline-info btn-sm downloadPackageBtn' >Archive and Download <i class='fa fa-download'></i></a>
@endif
