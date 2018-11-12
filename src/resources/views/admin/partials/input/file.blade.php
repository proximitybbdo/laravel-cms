<div class="row {{ "cnt_" . $asset_field_name }}" style="display:{{ $multiple_fields && (!$has_value && $multiple_index > 0) ? 'none' : '' }}">
  <div class="col-xs-12 col-md-6 col-sm-8">  
    @if(!$multiple_fields)
    <label class="control-label col-sm-12" for="{{ $field }}">
      {{ $title }}:
    </label>
    @endif
    <div class="input-group">
      <?= Form::hidden($field, null, array('class' => 'form-control','id'=>$asset_field_name)); ?>
      <?= Form::text('', ($has_value ? $model->file($content[$type])->file : ''), array('class' => 'form-control image-preview-filename','id'=>$asset_field_name,'disabled'=>'disabled')); ?>
      <span class="input-group-btn">          
          <button type="button" class="btn btn-default image-preview-clear" style="{{ $has_value ? "" : "display:none;" }}" data-input="<?= $asset_field_name ?>">
              <span class="fa fa-times"></span>
          </button> 
          <div class="btn btn-default">              
              <a class="showmanager" data-manager-type="file" data-module="<?= $module_type ?>" data-input="<?= $asset_field_name ?>"><span class="fa fa-folder-open-o"></span></a>             
          </div>
          <div class="btn btn-default" style="display:<?= $has_value ? "" : "none" ?>">
              <a style="display:<?= $has_value ? "inline" : "none" ?>"  href="<?= url('/uploads/file/' . ($has_value ? $model->file($content[$type])->file : '')) ?>" id="file_content_<?= $type ?>"> <span class="fa fa-download"></span></a>            
          </div>
      </span>
    </div>
  </div>
</div>