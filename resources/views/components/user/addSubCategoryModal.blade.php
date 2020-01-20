<div class="modal fade" id="addSubCategory_modal" tabindex="-1" role="dialog" data-backdrop="static"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add/Edit SubCategory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <form id="addSubCategoryForm" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="sub_category_id" id="sub_category_id" value="1">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_category_id">Category</label>
                                <select class="form-control selectpicker" id="product_category_id" name="product_category_id">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback error-product_category_id"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="start">Name</label>
                                <input class="form-control name" name="name" id="name" placeholder="Name" type="text">
                                <div class="form-control-feedback error-name"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status">Status</label> <br/>
                                <span class="m-switch m-switch--outline m-switch--icon m-switch--success">
                                    <label>
                                        <input type="checkbox" checked="checked" name="status" id="status">
                                        <span></span>
                                    </label>
                                </span>
                                <div class="form-control-feedback error-status"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square" data-dismiss="modal">Back</button>
                    <button type="submit" class="btn m-btn--square m-btn m-btn--custom btn-outline-success smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
