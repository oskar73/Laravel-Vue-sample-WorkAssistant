<div class="form-group">
    <table class="table table-bordered table-item-center">
        <tbody id="video_area">
            {{$slot}}
            @foreach(($videos??[]) as $key2=>$video)
                <tr>
                    <td>
                        <input type="text" class="form-control m-input--square" value="{{$video->getUrl()}}" readonly>
                        <input type="hidden" name='oldItems[]' value="{{$video->id}}">
                    </td>
                    <td><button class='btn btn-danger btn-sm delBtn'>X</button></td>
                </tr>
            @endforeach
        </tbody>
        <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1 mb3" id="addVideo">+ Upload Video</a>
    </table>
</div>
