<div class="modal fade" id="title_edit_modal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <form action="{{route('admin.saveTitle')}}" method="POST" id="title_edit_form">
        @csrf
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control minh-100px" name="title_edit" id="title_edit_textarea"></textarea>
                        <div class="form-control-feedback error-title_edit"></div>
                    </div>
                    <input type="hidden" id="title_edit_page" name="title_edit_page">
                    <input type="hidden" id="title_edit_id" name="title_edit_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="title_update_btn">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>
