
@extends('bbdocms::layouts.auth')

@section('content')

<form class="form-signin" method="POST" action="<?= route('sentinel.postLogin') ?>">
  <!--<img src="<?= asset('assets/img/logo@2x.png',config('app.secure_urls')); ?>" alt="">-->
  <h2 class="form-signin-heading">Please sign in</h2>

  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="text" class="input-block-level" name="email" placeholder="Username" value="<?= old('email') ?>">
  <input type="password" class="input-block-level" name="password" placeholder="Password">
  <div>
    <input type="checkbox" name="remember"> Remember Me
  </div>
  <input class="btn btn-large btn-primary" type="submit" value="Sign in">
  <div class="alert alert-error">
    <?= isset($errors) ? $errors->first('login') : ''; ?>
  </div>
  <hr>

  <footer>
    <p>&copy; BBDO {{date("Y")}}</p>
  </footer>
</form>

@endsection