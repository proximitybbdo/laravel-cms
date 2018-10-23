<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Satellic Admin - BBDO</title>

  <!-- Bootstrap Core CSS -->
  <link href="<?= asset('assets/admin/css/bootstrap.min.css',config('app.secure_urls')) ?>" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="<?= asset('assets/admin/css/sb-admin.css',config('app.secure_urls'))?>" rel="stylesheet">
  <!-- Custom Fonts -->
  <link href="<?= asset('assets/admin/font-awesome/css/font-awesome.min.css',config('app.secure_urls')) ?>" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<?= asset('assets/admin/css/dropzone.css',config('app.secure_urls')); ?>">

  <style type="text/css">
    [class^="icon-"], [class*=" icon-"] {
      width: 24px;
      height: 24px;
    }
  </style>
  <script type="text/javascript">
    window.base_url = '<?= url(); ?>';
  </script>
</head>
<body style="margin-top:0;">
  <div>
    <div>
      <div class="container-fluid">

      <div class="row">
        <div class="col-lg-12">
          <?= view()->make('bbdocms::admin.partials.flashes', array('error' => isset($error) ? $error : null, 'errors' => isset($errors) ? $errors : null)); ?>
        </div>
      </div>
<form id="myDropzone" class="dropzone dz-clickable" method="POST" action="<?= url("icontrol/files/$manager_type/$module_type/upload") ?>" data-manager-type="<?= $manager_type ?>" data-mode="<?= $mode ?>" data-input="<?= $input_id ?>" data-module="<?= $module_type ?>" data-max-filesize="<?= $maxFileSize ?>" data-accepted-files="<?= $acceptedFiles ?>">
<div class="dz-message">
<h4>Drag files to Upload</h4>
<span>Or click to browse</span>
</div>
</form>
<div id="filelist" class="popup">
  <?= view()->make('bbdocms::admin.partials.filelist', array("manager_type"=>$manager_type,"module_type"=>$module_type,"files"=>$files,"categories"=>$categories,"mode"=>"popup","value"=>$value,"input_id"=>$input_id,"content_links"=>$content_links))->render() ?>
</div>
</div>
    </div>
  </div>

  <script src="<?= asset('assets/admin/js/vendor/jquery.min.js',config('app.secure_urls')); ?>"></script>
  <script src="<?= asset('assets/admin/js/vendor/jquery-ui.min.js',config('app.secure_urls')); ?>"></script>
  <script src="<?= asset('assets/admin/js/vendor/bootstrap.js',config('app.secure_urls')); ?>"></script>
  <script src="<?= asset('assets/admin/js/vendor/dropzone.js',config('app.secure_urls')); ?>"></script>
  <script src="<?= asset('assets/admin/js/vendor/chosen.jquery.min.js',config('app.secure_urls')); ?>"></script>
  <script src="<?= asset('assets/admin/js/admin.js',config('app.secure_urls')); ?>"></script>

  <script type="text/javascript">
    (function($) {
      $('a[data-method]').each(function() {
        var $link = $(this);

        if($link.data('method') === 'POST') {
          $link.on('click', function(e) {
            e.preventDefault();

            if(confirm("Are you sure you want to delete this?")) {
              $.ajax({
                type: "POST",
                url: $link.attr('href'),
                data: { id: $link.data('id') }
              }).done(function() {
                document.location.reload(true);
              });
            }

            return false;
          });
        }
      });
    })($);
  </script>
</body>
</html>
