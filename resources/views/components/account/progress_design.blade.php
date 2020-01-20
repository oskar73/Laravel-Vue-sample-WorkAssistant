@if($userDesign->downloadable==1)
    <a href='{{route('user.graphics.download.package', $userDesign->hash)}}' class='btn btn-outline-success btn-sm downloadPackageBtn' >Ready for Download <i class='fa fa-download'></i></a>
@elseif($userDesign->downloadable==2)
    <p>Progress: {{number_format($userDesign->progress, 2)}} %</p>
    <div class='progress progress_el' data-id='{{$userDesign->id}}'>
        <div class="progress-bar" role="progressbar" style="width: {{$userDesign->progress}}%"
             aria-valuenow="{{number_format($userDesign->progress, 2)}}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
@else
    <a href='{{route('user.graphics.download.package', $userDesign->hash)}}' class='btn btn-outline-info btn-sm downloadPackageBtn' >Archive and Download <i class='fa fa-download'></i></a>
@endif
