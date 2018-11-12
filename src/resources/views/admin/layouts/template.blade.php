<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="<?=trans('site.meta.description');?>" />
    <meta name="author" content="" />
    <meta name="e" content="{{ App::environment() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


  <!-- Stylesheets -->
  <!-- Page JS Plugins CSS -->
  <link rel="stylesheet" href="<?=asset('admin/js/plugins/chosen/chosen.css')?>">
  <link rel="stylesheet" href="<?=asset('admin/js/plugins/dropzone/dist/min/dropzone.min.css')?>">

  <!-- <link rel="stylesheet" href="assets/js/plugins/summernote/summernote-bs4.css"> -->

  <!-- Fonts and Dashmix framework -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,400i,600,700">
  <link rel="stylesheet" id="css-main" href="<?=asset('admin/css/dashmix.min.css')?>">

  <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
  <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/xwork.min.css"> -->
  <!-- END Stylesheets -->


  <script type="text/javascript">
    window.base_url = '{{ url("/")  }}';
    window.module_type = '{{ $module_type  }}';
    window.default_lang = '{{ \Lang::getLocale()  }}';
  </script>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()])  !!}
    </script>
</head>
<body>
<!-- Page Container -->
  <!--
      Available classes for #page-container:

  GENERIC

      'enable-cookies'                            Remembers active color theme between pages (when set through color theme helper Template._uiHandleTheme())

  SIDEBAR & SIDE OVERLAY

      'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
      'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
      'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
      'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
      'sidebar-dark'                              Dark themed sidebar

      'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
      'side-overlay-o'                            Visible Side Overlay by default

      'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

      'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

  HEADER

      ''                                          Static Header if no class is added
      'page-header-fixed'                         Fixed Header


  Footer

      ''                                          Static Footer if no class is added
      'page-footer-fixed'                         Fixed Footer (please have in mind that the footer has a specific height when is fixed)

  HEADER STYLE

      ''                                          Classic Header style if no class is added
      'page-header-dark'                          Dark themed Header
      'page-header-glass'                         Light themed Header with transparency by default
                                                  (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
      'page-header-glass page-header-dark'         Dark themed Header with transparency by default
                                                  (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

  MAIN CONTENT LAYOUT

      ''                                          Full width Main Content if no class is added
      'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
      'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
  -->

<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-fixed page-header-dark main-content-narrow">

    @include('bbdocms::admin.partials.nav')


    <!-- Main Container -->
    <main id="main-container">


              @if( session('sentinel'))
                <div class="alert alert-danger">
                  <h4>
                    {{ session('sentinel') }}
                  </h4>
                </div>
              @endif

         <!-- Hero -->
         <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">{{ $module_title }}</h1>
                    <br />
                    <small>{{ isset($module_subtitle) ? $module_subtitle : '' }}</small>

                </div>
            </div>
        </div>
        <!-- END Hero -->

         @yield('content')

    </main>
  </div>
  <!--
            Dashmix JS Core

            Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
            to handle those dependencies through webpack. Please check out assets/_es6/main/bootstrap.js for more info.

            If you like, you could also include them separately directly from the assets/js/core folder in the following
            order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

            assets/js/core/jquery.min.js
            assets/js/core/bootstrap.bundle.min.js
            assets/js/core/simplebar.min.js
            assets/js/core/jquery-scrollLock.min.js
            assets/js/core/jquery.appear.min.js
            assets/js/core/js.cookie.min.js
        -->
        <script src="<?=asset('admin/js/dashmix.core.min.js')?>"></script>

        <!--
            Dashmix JS

            Custom functionality including Blocks/Layout API as well as other vital and optional helpers
            webpack is putting everything together at assets/_es6/main/app.js
        -->
        <script src="<?=asset('admin/js/dashmix.app.min.js')?>"></script>

        <!-- Page JS Plugins -->
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script src="<?=asset('admin/js/plugins/chosen/chosen.jquery.min.js')?>"></script>
        <script src="<?=asset('admin/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
        <script src="<?=asset('admin/js/plugins/jquery-ui/jquery-ui.min.js')?>"></script>
        <script src="<?=asset('admin/js/plugins/dropzone/dropzone.min.js')?>"></script>

        <!-- Page JS Helpers (Summernote + SimpleMDE + CKEditor plugins) -->
        <script>jQuery(function(){ Dashmix.helpers(['ckeditor']); });</script>

</body>
</html>
