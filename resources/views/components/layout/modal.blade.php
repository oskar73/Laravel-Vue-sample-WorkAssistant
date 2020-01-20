<div class="modal fade" id="{{$id}}" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-{{$size??'md'}}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <form id="{{$id}}_form" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    {{$slot}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-info  m-btn--custom m-btn m-btn--square" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn m-btn--square m-btn--custom m-btn btn-outline-success" id="btnSubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
