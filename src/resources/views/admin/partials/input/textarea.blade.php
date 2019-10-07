<div class="form-group">
    <label for="{{$field}}">
        {{ $title }}
    </label>

    @if (isset($editor))
        <?= Form::textarea($field, null, array('class' => 'form-control js-summernote', 'id' => $field )); ?>
    @else
        <?= Form::textarea($field, null, array('class' => 'form-control', 'id' => $field, 'rows' => 10)); ?>
    @endif

</div>