<div class="form-group">
    <table class="table table-bordered table-item-center">
        <tbody id="image_area">
            {{$slot}}
            @foreach(($images??[]) as $key=>$image)
                <tr>
                    <td>
                        <input type="text" class="form-control m-input--square" value="{{$image->getUrl()}}" readonly>
                        <input type="hidden" name='oldItems[]' value="{{$image->id}}">
                    </td>
                    <td class="text-center">
                        <figure data-href="{{$image->getUrl()}}" class="width-150 progressive replace m-auto">
                            <img class='width-150 preview' src="{{$image->getUrl('thumb')}}"/>
                        </figure>
                    </td>
                    <td><button class='btn btn-danger btn-sm delBtn'>X</button></td>
                </tr>
            @endforeach
        </tbody>
        <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1 mb-3" id="addImage">+ Add Image</a>
    </table>
</div>
