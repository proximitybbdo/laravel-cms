<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }} - admin</title>

  <link href="<?= asset('admin/css/bootstrap.min.css',config('app.secure_urls')) ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('admin/css/login.css',config('app.secure_urls')); ?>">
  <!--<link rel="shortcut icon" href="<?= asset('img/icon/favicon.png',config('app.secure_urls')); ?>" />-->
  <!--<link rel="apple-touch-icon" href="<?= asset('img/icon/apple-touch-icon.png',config('app.secure_urls')); ?>" />-->

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body class="login">

  <div class="container">
  
    @yield('content')

  </div>

  <script src="<?= asset('admin/js/vendor/jquery.min.js',config('app.secure_urls')); ?>"></script>
  <script src="<?= asset('admin/js/vendor/bootstrap.js',config('app.secure_urls')); ?>"></script>
</body>
</html>
