@props([
    'label' => null,
    'id' => 'switchId',
    'name' => 'name',
    'checked' => false
])

<div class="form-group">
    @if($label)
        <label for="{{$id}}" class="form-control-label">{{$label}}</label>
    @endif
    <div>
        <span class="m-switch m-switch--icon ml-1 mr-1 m-switch--info">
            <label>
                <input type="checkbox" id="{{$id}}" name="{{$name}}" @if($checked) checked @endif >
                <span></span>
            </label>
        </span>
    </div>
</div>
