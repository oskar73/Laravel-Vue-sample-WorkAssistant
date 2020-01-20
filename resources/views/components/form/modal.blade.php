
<div class="modal fade" id="{{$id?? ''}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">{{$title?? ''}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                {{$slot}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn m-btn--square m-btn--custom btn-outline-primary" data-dismiss="modal">Back</button>
                <button type="submit" class="btn m-btn--square m-btn--custom btn-outline-info {{$smtBtnClass?? ''}}" >Submit</button>
            </div>
        </div>
    </div>
</div>
