<form id="myDropzone" class="dropzone dz-clickable" method="POST" action="<?=url("icontrol/files/$manager_type/" . ($module_type != null ? $module_type . '/' : '') . ($input_type != null ? $input_type . '/' : '') . 'upload')?>"
  data-manager-type="<?=$manager_type?>"
  data-mode="<?=$mode?>"
  data-input="<?=$input_id?>"
  data-module="<?=$module_type?>"
  data-max-filesize="<?=$maxFileSize?>"
  data-accepted-files="<?=$acceptedFiles?>"
  data-input-type="<?=$input_type?>"
  <?=$image_config != null ? "data-width=" . $image_config['width'] . " data-height=" . $image_config['height'] : ""?>
>
<div class="dz-message">
<h1><i class="fa fa-upload"></i></h1>
<h4>Drag files to Upload</h4>
<span>Or click to browse</span>
</div>
</form>
<div id="filelist" class="row items-push">
  <?=view()->make(viewPrefixCmsNamespace('admin.partials.filelist'), array("manager_type" => $manager_type, "module_type" => $module_type, "files" => $files, "categories" => $categories, "mode" => $mode, "value" => $value, "input_id" => $input_id, "content_links" => $content_links))->render()?>
</div>
