<div class="form-group col-sm-12">
      <label class="control-label col-sm-12" for="my_content['{{$type}}']">
      {{ $title }}
      </label>
      <div class="controls col-sm-12">
        {{ $model->my_content_online->keys()->contains($type) ? $model->my_content_online[$type] : '' }}
      </div>
</div>