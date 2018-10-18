<div class="form-group">
    <label class="control-label col-sm-12" for="{{$field}}">
        {{ $title }}
    </label>
      
    <div class="controls col-sm-12">
        @if (isset($editor))
            <?= Form::text($field, null, array('class' => 'form-control ' . $editor, 'id' => $field )); ?>
        @else
            <?= Form::text($field, null, array('class' => 'form-control', 'id' => $field)); ?>
        @endif
    </div>
</div>