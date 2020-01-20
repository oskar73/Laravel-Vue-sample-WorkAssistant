@if($userFavicon->downloadable==1)
    <a href='{{route('user.favicon.download.package', $userFavicon->hash)}}' class='btn btn-outline-success btn-sm downloadPackageBtn' >Ready for Download <i class='fa fa-download'></i></a>
@elseif($userFavicon->downloadable==2)
    <p>Progress: {{$userFavicon->progress}} %</p>
    <div class='progress progress_el' data-id='{{$userFavicon->id}}'>
        <div class="progress-bar" role="progressbar" style="width: {{$userFavicon->progress}}%"
             aria-valuenow="{{$userFavicon->progress}}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
@else
    <a href='{{route('user.favicon.download.package', $userFavicon->hash)}}' class='btn btn-outline-info btn-sm downloadPackageBtn' >Archive and Download <i class='fa fa-download'></i></a>
@endif
