<div class="form-group {{ "cnt_" . $asset_field_name }}" 
  data-index="{{ $field_index }}"
  style="display:{{ $multiple_fields && (!$has_value && $multiple_index > 0) ? 'none' : '' }}">

    @if(!$multiple_fields)
    <label class="control-label col-sm-12" for="{{ $field }}">
      {{ $title }}:
    </label>
    @endif
    <div class="input-group">
      <div class="input-group-prepend" style="{{ $has_value ? "" : "display:none;" }}">
        <span class="input-group-text input-group-text-alt image-preview-clear" data-input="<?= $asset_field_name ?>"><i class="fa fa-unlink"></i></span>
      </div>
      <div class="custom-file">
        <input type="text" class="form-control form-control-alt custom-file-input image-preview-filename" value="{{ $has_value ? $model->file($content[$type])->file : '' }}" disabled="disabled">
      </div>
      <div class="input-group-append">
        <span class="input-group-text input-group-text-alt image-preview-input-title showmanager"
            data-manager-type="image"
            data-type="<?=$input_type?>"
            data-module="<?=$module_type?>"
            data-input="<?=$asset_field_name?>">
          <i class="fa fa-file"></i>
          <!-- <button c>Choose file</button> -->
        </span>

      </div>
    </div>

  <?= Form::hidden($field, null, array('class' => 'form-control','id'=>$asset_field_name)); ?>
</div>