
@extends('bbdocms::admin.layouts.auth')

@section('content')

<!-- Main Container -->
<main id="main-container">

    <!-- Page Content -->
    <div class="row no-gutters justify-content-center bg-body-dark">
        <div class="hero-static col-sm-5 col-md-4 col-xl-3 d-flex align-items-center p-2 px-sm-0">
            <!-- Sign In Block -->
            <div class="block block-rounded block-transparent block-fx-pop w-100 mb-0 overflow-hidden bg-image" style="background-image: url('assets/media/photos/photo20@2x.jpg');">
                <div class="row no-gutters">
                    <div class="col-md-12 order-md-1 bg-white">
                        <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6">
                            <!-- Header -->
                            <div class="mb-2 text-center">
                                <a class="link-fx font-w700 font-size-h1" href="index.html">
                                    <span class="text-dark">BBDO</span><span class="text-primary">cms</span>
                                </a>
                                <p class="text-uppercase font-w700 font-size-sm text-muted">Sign In</p>
                            </div>
                            <!-- END Header -->

                            <!-- Sign In Form -->
                            <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _es6/pages/op_auth_signin.js) -->
                            <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                            <form class="form-signin js-validation-signin" method="POST" action="<?=route('sentinel.postLogin')?>">
                                <div class="form-group">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <input type="text" id="login-username" class="form-control form-control-alt" name="email" placeholder="Username" value="<?=old('email')?>">
                                <div id="login-username-error" class="invalid-feedback animated fadeIn">Please enter a username</div>
                                </div>
                                <div class="form-group">
                                    <input type="password" id="login-password" class="form-control form-control-alt" name="password" placeholder="Password">
                                    <div id="login-password-error" class="invalid-feedback animated fadeIn">Please provide a password</div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block btn-hero-primary">
                                        <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Sign In
                                    </button>
                                </div>
                            </form>
                            <!-- END Sign In Form -->
                        </div>
                    </div>

                </div>
            </div>
            <!-- END Sign In Block -->
        </div>
    </div>
    <!-- END Page Content -->

</main>
<!-- END Main Container -->

@endsection