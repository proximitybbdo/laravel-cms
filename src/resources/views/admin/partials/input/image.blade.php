<div class="row {{ "cnt_" . $asset_field_name }}" data-index="{{ $field_index }}"
     style="display:{{ $multiple_fields && (!$has_value && $multiple_index > 0) ? 'none' : '' }}">
    <div class="col-xs-12 col-md-6 col-sm-8">
        @if(!$multiple_fields)
            <label class="control-label" for="{{ $field }}">
                {{ $title }}
            </label>
        @endif
        <div class="input-group image-preview"
             data-content="{{ $has_value ? $model->fileContent($content[$type],$type) : '' }}"
             data-toggle="{{ $has_value ? 'popover' : '' }}">

            <input type="text" value="{{ $has_value ? $model->file($content[$type])->file : '' }}"
                   class="form-control image-preview-filename" disabled="disabled">
            <!-- don't give a name === doesn't send on POST/GET -->
            <span class="input-group-btn">
                <!-- image-preview-clear button -->
                <button type="button" class="btn btn-default image-preview-clear"
                        style="{{ $has_value ? "" : "display:none;" }}" data-input="<?= $asset_field_name ?>">
                    <span class="fa fa-times"></span>
                </button>
                <!-- image-preview-input -->
                <div class="btn btn-default image-preview-input">
                    
                    <a class="image-preview-input-title showmanager"
                       data-manager-type="image"
                       data-type="<?= $input_type ?>"
                       data-module="<?= $module_type ?>"
                       data-input="<?= $asset_field_name ?>"
                    >
                        <span class="fa fa-folder-open-o"></span>
                    </a>
                               
                </div>
            </span>
        </div>
    </div>
    <?= Form::hidden($field, null, array('class' => 'form-control form-image', 'id' => $asset_field_name)); ?>
</div>
