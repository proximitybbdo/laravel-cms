<div class="form-group form-check">
    <?= Form::radio($field, (isset($value) ? $value : $title), (isset($checked) ? $checked : null), array('class' => 'form-check-input', 'id' => $field, (isset($required) ? 'required' : '') => (isset($required) ? 'required' : ''))); ?>
    <label class="form-check-label" for="{{$field}}">{{$title}}</label>
</div>