@extends('bbdocms::admin.layouts.template')

@section('content')

    <!-- Page Content -->
    <div class="content">
    <div class="block block-rounded block-bordered">

           <div class="block-content">


                <?php if ($single_item == false) {?>
                   <block class="block-content">
                        @if (Sentinel::hasAccess(strtolower($module_type) . '.view'))
                            <a id="overview" href="<?=$back_link?>" class="button">
                              <button type="button" class="btn btn-default"> &lt; Back to overview</button>
                            </a>
                        @endif

                        @if (isset($model->id))
                            @if (Sentinel::hasAccess(strtolower($module_type) . '.create'))
                                <a href="<?=url()->to("icontrol/items/$module_type/add");?>" class="btn btn-primary pull-right">
                                    Add new {{ config('cms.' . $module_type . '.description') }} <i class="fa fa-plus-circle"></i>
                                </a>
                            @endif
                        @endif
                    </block>
                <?php }?>

                @include('bbdocms::admin.partials.form')


            @if (Sentinel::hasAccess(strtolower($module_type) . '.view'))
                <div class="form-group">
                    <div class="controls">
                        <a href="<?=$back_link?>" class="button">
                            <button type="button" class="btn btn-default"> &lt; Back to overview</button>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Modal -->
            <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content load_modal"></div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection('content')
