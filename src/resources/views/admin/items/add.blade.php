@extends('admin.template')

@section('content')
    <div class="span12" style="padding: 20px 0">
        <?php if($single_item == false) { ?>
            <p>
                @if (Sentinel::hasAccess(strtolower($module_type) . '.view'))
                    <a id="overview" href="<?= $back_link ?>" class="button">
                      <button type="button" class="btn btn-default"> &lt; Back to overview</button>
                    </a>
                @endif

                @if (isset($model->id))
                    @if (Sentinel::hasAccess(strtolower($module_type) . '.create'))
                        <a href="<?= URL::to("icontrol/items/$module_type/add"); ?>" class="btn btn-primary pull-right">
                            Add new {{ Config::get('cms.' . $module_type . '.description') }} <i class="fa fa-plus-circle"></i>
                        </a>
                    @endif
                @endif
            </p>

            <hr/>
        <?php } ?>

        @include('admin.partials.form')
    </div>

    @if (Sentinel::hasAccess(strtolower($module_type) . '.view'))
        <div class="form-group">
            <div class="controls">
                <a href="<?= $back_link ?>" class="button">
                    <button type="button" class="btn btn-default"> &lt; Back to overview</button>
                </a>
            </div>
        </div>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content load_modal"></div>
        </div>
    </div>
@endsection('content')
