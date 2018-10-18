<div class="form-group">
      <label class="control-label col-sm-12" for="my_content[slug]">
        Slug:
      </label>
      <div class="controls col-sm-12">
        <?= Form::text('my_content[slug]',null,array('class' => 'form-control slug','disabled'=>'disabled')); ?>
        <?= Form::hidden('my_content[slug]',null,array('class' => 'form-control slug')); ?>
      </div>
</div>