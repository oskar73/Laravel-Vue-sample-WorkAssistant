<div class="modal fade" id="addColor_modal" tabindex="-1" role="dialog" data-backdrop="static"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add/Edit Color</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <form id="addColorForm" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="color_id" id="color_id" value="1">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="start">Name</label>
                                <input class="form-control name" name="name" id="name" placeholder="Name" type="text">
                                <div class="form-control-feedback error-name"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="color_code">Color Code</label>
                                <input type="color" class="form-control color_code" name="color_code" id="color_code" placeholder="color Code">
                                <div class="form-control-feedback error-color_code"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input class="form-control slug" name="slug" id="slug" placeholder="Slug" type="text">
                                <div class="form-control-feedback error-slug"></div>
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
