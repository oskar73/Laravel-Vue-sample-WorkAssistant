<div class="form-group">
    <table class="table table-bordered table-item-center">
        <tbody id="link_area">
            {{$slot}}
            @foreach(($links??[]) as $key1=>$link)
                <tr>
                    <td><input type="url" name='links[]' class="form-control m-input--square" value="{{$link}}"></td>
                    <td><button class='btn btn-danger btn-sm delBtn'>X</button></td>
                </tr>
            @endforeach
        </tbody>
        <a href="javascript:void(0);" class="btn m-btn--square m-btn m-btn--custom btn-outline-info p-1 mb-3" id="addLink">+ Add External Video Link</a>
    </table>
</div>
