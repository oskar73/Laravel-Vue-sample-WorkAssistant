<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">My Chosen Apps</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">X</span>
    </button>
</div>
<div class="modal-body">
    @include('components.user.selectedModulesTable', compact('items'))
</div>
