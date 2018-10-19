@extends('bbdocms::admin.template')

@section('content')
    <div class="span12">
        <h1><?= $module_title ?></h1>

        @if($cat_item != null)
            <h3><a href="<?= URL::to("icontrol/items/$cat_item->module_type/overview"); ?>">{{ $cat_item->description }}</a></h3>
        @endif

        <div class="form-line overview padding-bottom">
            <?= view('bbdocms::admin.partials.links_overview',array('links' => $links,'category_id' => $active_cat)) ?>
        </div>

        <?= $overview_data ?>

        <a style="font-size:20px" href="<?= URL::to("icontrol/items/$module_type/add"); ?>" class="btn">
            <i class="fa fa-plus-circle"></i>
        </a>
    </div>
@endsection('content')