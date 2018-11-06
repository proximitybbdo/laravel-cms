<div class="form-group">
      <label for="$field">
         {{ $title }}
      </label>
      <?= Form::select($field, $options ,null, array('class' => 'custom-select')); ?>
</div>