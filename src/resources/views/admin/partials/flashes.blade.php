<?php if(isset($error)): ?>
<div class="alert alert-error">
    <?= $error; ?>
    <ul>
        <?php if(isset($errors)) : foreach($errors as $field => $error) : ?>
        <li><?= $error ?></li>
        <?php endforeach; endif; ?>
    </ul>
</div>
<?php endif; ?>

<?php if(Session::has('success')): ?>
<div class="alert alert-success">
    <?= Session::get('success'); ?>
</div>
<?php endif; ?>

<?php if(Session::has('publish')): ?>
<div class="alert alert-success">
    <?= Session::get('publish'); ?>
</div>
<?php endif; ?>


