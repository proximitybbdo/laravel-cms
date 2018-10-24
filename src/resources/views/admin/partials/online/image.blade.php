<div class="form-group col-sm-12">
    <label class="control-label col-sm-12" for="my_content[{{$type}}]">
        {{$title}}
    </label>
    <?php $showImage = $model->my_content_online->keys()->contains($type) && $model->my_content_online[$type] != '' ?>
    <img src="<?= url('/uploads/image/' . ($showImage ? $model->file($model->my_content_online[$type])->file : '')) ?>"
         style="display:<?= $showImage ? "block" : "none" ?>" width="150" id="img_content_image"/>
</div>