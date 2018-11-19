@extends(viewPrefixCmsNamespace('admin.layouts.template'))

@section('content')
 <!-- Page Content -->
 <div class="content">
    <div class="block block-rounded block-bordered">

           <div class="block-content">

        <a style="font-size:20px" href="<?=url()->to("icontrol/items/$module_type/add");?>" class="btn">
            <i class="fa fa-plus-circle"></i>
        </a>

        @if($cat_item != null)
            <h3><a href="<?=url()->to("icontrol/items/$cat_item->module_type/overview");?>">{{ $cat_item->description }}</a></h3>
        @endif

        <div class="form-line overview padding-bottom">
            <?=bbdoview('admin.partials.links_overview', array('links' => $links, 'category_id' => $active_cat))?>
        </div>

        <?=$overview_data?>

        <a style="font-size:20px" href="<?=url()->to("icontrol/items/$module_type/add");?>" class="btn">
            <i class="fa fa-plus-circle"></i>
        </a>
    </div>
</div>
@endsection('content')