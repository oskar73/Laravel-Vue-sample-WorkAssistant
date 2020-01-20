<div class="form-group text-center">
    <div class="text-left">{{$label?? 'Thumbnail'}}</div>
    <label for="thumbnail" class="btn btn-success mt-2 w-100">Upload</label>
    <input type="file" accept="image/*"
           class="form-control m-input--square uploadImageBox d-none"
           id="thumbnail"
           name="origin_image"
           data-target="thumbnail_image"
    >
    <div class="form-control-feedback error-thumbnail" ></div>
    <img id="thumbnail_image" class="thumbnail-img" src="{{$slot}}"/>
</div>
