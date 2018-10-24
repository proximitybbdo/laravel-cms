<div class="form-group form-check">
    <input type="checkbox" class="form-check-input {{ isset($editor) ? $editor : '' }}"
           id="{{$field}}" {{ isset($required) ? 'required' : '' }}>
    <label class="form-check-label" for="{{$field}}">{{$title}}</label>
</div>