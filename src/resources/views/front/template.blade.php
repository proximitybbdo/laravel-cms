<!doctype html>
<!--[if IE 8]> <html class="no-js ie8" lang="<?= App::getLocale(); ?>"> <![endif]-->
<!--[if IE 9]> <html class="no-js ie9" lang="<?= App::getLocale(); ?>"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="{{ App::getLocale() }}"> <!--<![endif]-->
<head>
  <!-- Google Tag Manager -->
  
  
<!-- End Google Tag Manager -->

<?php
$title_tag = trans('site.meta.title');
if( isset($slug_title) )
$title_tag .= ' - ' .$slug_title;
elseif ( isset($route_title) )
$title_tag .=  ' - ' .trans($route_title);
?>

<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9,chrome=1" />
<meta name="description" content="<?= trans('site.meta.description'); ?>" />
<meta name="author" content="" />
<meta name="e" content="{{ App::environment() }}" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta property="og:site_name" content="<?= trans('site.meta.title'); ?>" />
<meta property="og:title" content="<?= $title_tag ?>">
<meta property="og:url" content="<?=  str_replace ( 'http://' , 'http://' , Request::url()); ?>">
<meta property="og:image" content="{{ asset('/img/og/facebook_share.jpg') }}">
<meta property="og:description" content="<?= trans('site.meta.description'); ?>">
<meta property="og:locale" content="<?= str_replace("fr_BE", "fr_FR", str_replace("-", "_", App::getLocale() ) ); ?>">
<meta property="og:type" content="website" />


<title>{{ $title_tag }}</title>

<!--[if lt IE 9]>
<script>
document.createElement('header');
document.createElement('nav');
document.createElement('section');
document.createElement('article');
document.createElement('aside');
document.createElement('footer');
</script>

<![endif]-->

<link rel="shortcut icon" href="{{ asset('/img/favicon/favicon-32x32.png') }}" />
<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/img/favicon/apple-icon-57x57.png') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/img/favicon/apple-icon-60x60.png') }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/img/favicon/apple-icon-72x72.png') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/img/favicon/apple-icon-76x76.png') }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/img/favicon/apple-icon-114x114.png') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/img/favicon/apple-icon-120x120.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/img/favicon/apple-icon-144x144.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/img/favicon/apple-icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/img/favicon/apple-icon-180x180.png') }}">
<link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('/img/favicon/android-icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/img/favicon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/img/favicon/favicon-96x96.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/img/favicon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('/img/favicon/manifest.json') }}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{ asset('/img/favicon/ms-icon-144x144.png') }}">
<meta name="theme-color" content="#ffffff">

<link rel="stylesheet" href="{{ asset('/css/style.css') }}" />
<link rel="stylesheet" href="{{ asset('/css/vendor.css') }}" />
<link href="<?= asset('admin/css/bootstrap.min.css',Config::get('app.secure_urls')) ?>" rel="stylesheet">
</head>
{{-- <body data-page="{{\Helpers::clean_segments()}}"> --}}
<body class="body--{{ Route::currentRouteName() }}" data-page="{{ Route::currentRouteName() }}" data-lang="{{App::getLocale()}}">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P6QC2HP"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <script type="text/javascript">
    window.site = {};
    window.site.lang = '{{App::getLocale()}}';
    window.site.base_url = '{{url('')}}';
    window.environment = '{{App::environment()}}'
    </script>


    <!--[if lt IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="wrapper container">
      @include('front.partials.nav')

      @yield('content')

      @section('footer')
        @include('front.partials.footer')
      @show
    </div>

    {{-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> --}}
    <!-- @if ( Config::get('app.debug') )
    <script type="text/javascript">
    document.write('<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
  </script>
@endif -->

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script src="<?= asset('admin/js/vendor/jquery.min.js'); ?>"></script>
<script src="<?= asset('admin/js/vendor/bootstrap.js',Config::get('app.secure_urls')); ?>"></script>
<script src="{{ asset('/js/app.js') }}"></script>
<script src="{{ asset('js/front.js') }}"></script> 

</body>
</html>
