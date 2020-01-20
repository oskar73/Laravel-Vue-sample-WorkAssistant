@props([
    'id' => 'modal',
    'title' => 'Modal',
    'no' => 'Close',
    'yes' => 'Submit'
])

<div class="modal fade" id="{{$id}}" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! $slot !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{$no}}</button>
                    <button type="submit" class="btn btn-success tw-bg-green-600 smtBtn">{{$yes}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
