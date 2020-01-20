<form action="{{route('admin.template.item.updateTemplate', $template->id)}}" id="submit_form" method="post" enctype="multipart/form-data" >
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="category" class="form-control-label">Choose Category:</label>
                <select name="category_id" id="category" class="selectpicker" data-live-search="true" data-width="100%">
                    <option selected disabled hidden>Choose Category</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}"
                                @if($template->category_id===$category->id) selected @endif>{{$category->name}}</option>
                        @foreach($category->approvedSubCategories as $subcategory)
                            <option value="{{$subcategory->id}}"
                                    @if($template->category_id===$subcategory->id) selected @endif>
                                {{$category->name}} &#8594; {{$subcategory->name}}
                            </option>
                        @endforeach
                    @endforeach
                </select>
                <div class="form-control-feedback error-category_id"></div>
            </div>

            <div class="form-group">
                <label for="name" class="form-control-label">Name: </label>
                <input type="text" class="form-control m-input--square" name="name" id="name"
                       value="{{$template->name}}"
                >
                <div class="form-control-feedback error-name"></div>
            </div>

            <div class="form-group">
                <label for="description" class="form-control-label">Description:
                </label>
                <textarea class="form-control m-input--square minh-100" name="description" id="description"
                >{{$template->description}}</textarea>
                <div class="form-control-feedback error-description"></div>
            </div>


            <div class="row mt-4">
                <div class="col-md-4">
                    <x-form.switch id="featured" label="Featured" name="featured" :checked="$template->featured" />
                </div>
                <div class="col-md-4">
                    <x-form.switch id="new" label="New" name="new" :checked="$template->new" />
                </div>
                <div class="col-md-4">
                    <x-form.switch id="status" label="Active" name="status" :checked="$template->status" />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group slimdiv">
                <label for="preview-image" class="form-control-label">Preview Image</label>
                <input type="file"  id="preview-image" name="image" data-target="preview" data-url="{{$template->getFirstMediaUrl('preview')}}" >
            </div>
        </div>
    </div>
</form>
<script> var template_id = "{{$template->id}}" </script>
<script src="{{asset('assets/js/admin/template/editItem.js')}}"></script>
