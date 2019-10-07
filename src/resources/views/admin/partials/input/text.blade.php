<div class="form-group">
    <label for="{{$field}}">
        {{ $title }}
    </label>

    @if (isset($editor))
        <?= Form::text($field, null, array('class' => 'form-control js-summernote', 'id' => $field )); ?>
    @else
        <?= Form::text($field, null, array('class' => 'form-control', 'id' => $field)); ?>
    @endif

</div>