<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="description" content="<?= trans('site.meta.description'); ?>"/>
    <meta name="author" content=""/>
    <meta name="e" content="{{ App::environment() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Bootstrap Core CSS -->

    <link href="<?= asset('admin/css/bootstrap.min.css', config('app.secure_urls')) ?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= asset('admin/font-awesome/css/font-awesome.min.css', config('app.secure_urls')) ?>" rel="stylesheet"
          type="text/css">
    <link rel="stylesheet" href="<?= asset('admin/css/chosen.css'); ?>">
    <link rel="stylesheet" href="<?= asset('admin/css/dropzone.css'); ?>">
    <!-- Custom CSS -->
    <link href="<?= asset('admin/css/sb-admin.css', config('app.secure_urls'))?>" rel="stylesheet">
    <link href="<?= asset('admin/css/jquery-ui.min.css', config('app.secure_urls')) ?>" rel="stylesheet">
    <style type="text/css">
        [class^="icon-"], [class*=" icon-"] {
            width: 24px;
            height: 24px;
        }

        .fade:not(.in) {
            visibility: hidden;
        }
    </style>
    <script type="text/javascript">
      window.base_url = '<?= url("/"); ?>';
      window.module_type = '<?= $module_type; ?>';
      window.default_lang = '<?= \Lang::getLocale(); ?>';
    </script>

    <!-- Scripts -->
    <script>
      window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div id="wrapper">
    @include('bbdocms::admin.partials.nav')

    <div id="page-wrapper">
        <div class="container-fluid">

            @if( session('sentinel'))
                <div class="alert alert-danger">
                    <h4>
                        {{ session('sentinel') }}
                    </h4>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="<?= asset('admin/js/vendor/jquery.min.js', config('app.secure_urls')); ?>"></script>
<script src="<?= asset('admin/js/vendor/jquery-ui.min.js', config('app.secure_urls')); ?>"></script>
<script src="<?= asset('admin/js/vendor/bootstrap.js', config('app.secure_urls')); ?>"></script>
<script src="<?= asset('admin/js/vendor/dropzone.js', config('app.secure_urls')); ?>"></script>
<script src="<?= asset('admin/js/vendor/chosen.jquery.min.js', config('app.secure_urls')); ?>"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script src="<?= asset('admin/js/admin.js', config('app.secure_urls')); ?>"></script>
</body>
</html>
