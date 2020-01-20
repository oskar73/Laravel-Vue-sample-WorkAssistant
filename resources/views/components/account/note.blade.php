@foreach($notes as $note)
    <div class="m-list-timeline__item">
        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
        <a href="javascript:void(0);" class="m-list-timeline__text @if($note->checked) slashed_text @endif check_note" data-id="{{$note->id}}">{{$note->text}}</a>
        <span class="m-list-timeline__time">
            <span class="remove_note" data-id="{{$note->id}}" style="color: red;font-size: 18px;cursor: pointer;">x</span>
        </span>
    </div>
@endforeach
