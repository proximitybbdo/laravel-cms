<div class="form-group">
      <label for="my_content[slug]">
        Slug:
      </label>
      <?= Form::text('my_content[slug]',null,array('class' => 'form-control slug','disabled'=>'disabled')); ?>
      <?= Form::hidden('my_content[slug]',null,array('class' => 'form-control slug')); ?>
</div>