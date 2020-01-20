<div class="form-group">
    <label for="{{$name}}" class="form-control-label">
        {{$label}}: <span class="text-danger">{{ (isset($required) && $required) ? "*" : '' }}</span>

        {{$slot}}

    </label>
    <input type="{{$type?? 'text'}}"
           class="form-control m-input--square"
           name="{{$name}}"
           id="{{$name}}"
           value="{{$value?? ''}}"
           {{$readonly?? ''}}
           placeholder="{{$placeholder?? ''}}"
    >
    <div class="form-control-feedback error-{{$name}}"></div>
</div>
