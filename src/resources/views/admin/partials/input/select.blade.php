<div class="form-group">
    <label class="control-label col-sm-12" for="$field">
        {{ $title }}
    </label>
    <div class="controls col-sm-12">
        <?= Form::select($field, $options, null, array('class' => 'form-control')); ?>
    </div>
</div>