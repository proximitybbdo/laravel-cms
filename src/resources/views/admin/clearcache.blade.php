@extebds('bbdocms::admin.template')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                   <div class="span12" style="padding: 20px 0">
<h1>Clear website data cache</h1>
<?php if($cleared): ?>
   <div class="alert alert-success">
    Cache cleared successfully.
   </div>
<?php endif; ?>
<?php if(!$cleared): ?>
<p>
  Do you wish to clear all the cached data?
</p>
<p>
<?= Form::open(array('enctype'=>"multipart/form-data",'role'=>'form','id'=>'form')); ?>
<button type="submit" id="draft" name="draft" class="btn btn-primary">
  Clear cache
</button>
<?= Form::close(); ?>
</p>
<?php endif; ?>

</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection('content')

