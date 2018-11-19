<div class="form-group form-check">
    @if (isset($editor))
        <?= Form::checkbox($field, 1, null, array('class' => 'form-check-input ' . $editor, 'id' => $field, (isset($required) ? 'required' : '') => (isset($required) ? 'required' : '')); ?>
    @else
        <?= Form::checkbox($field, 1, null, array('class' => 'form-check-input', 'id' => $field, (isset($required) ? 'required' : '') => (isset($required) ? 'required' : ''))); ?>
    @endif

    <label class="form-check-label" for="{{$field}}">{{$title}}</label>
</div>