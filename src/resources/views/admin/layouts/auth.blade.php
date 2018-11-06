<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }} - admin</title>

  <!-- Stylesheets -->

  <!-- Fonts and Dashmix framework -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700">
  <link rel="stylesheet" href="<?=asset('admin/css/dashmix.min.css')?>">
  <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
  <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/xwork.min.css"> -->
  <!-- END Stylesheets -->
</head>
<body class="login">

  <div id="page-container">

    @yield('content')

  </div>


  <script src="<?=asset('admin/js/dashmix.core.min.js')?>"></script>
  <script src="<?=asset('admin/js/dashmix.app.min.js')?>"></script>
  <!-- Page JS Plugins -->
  <script src="<?=asset('admin/js/plugins/jquery-validation/jquery.validate.min.js')?>"></script>
  <script src="<?=asset('admin/js/pages/op_auth_signin.min.js')?>"></script>


</body>
</html>
