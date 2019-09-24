@if(Session::has('confirm'))
    <div class="alert alert-success">{{ Session::get('confirm') }}</div>
@endif

@if(Session::has('publish'))
    <div class="alert alert-success">{{ Session::get('publish') }}</div>
@endif

@if(!$errors->isEmpty())
    <div class="alert alert-danger">
        <h2>{{ trans('site.forms.default_fields.error') }}</h2>
        {{ Html::ul($errors->all()) }}
    </div>
@endif

{{ Form::model($model, array('enctype'=>"multipart/form-data",'id'=>'form-'.$module_type, "data-module-type"=> $module_type, "class"=>"m-t-30" )) }}

<h2 class="content-heading pt-0">General content</h2>
<div class="card card-body">


    {{ Form::hidden('id',null) }}
    {{ Form::hidden('lang',null) }}
    {{ Form::hidden('sort', null) }}

    <input name="input_changes" id="input_changes" type="hidden"/>

    <!-- basic data -->
    <div class="form-group">
        <label for="description">
            Description:
        </label>
        {{ Form::text('description', null, array('class'=>'form-control')) }}

        @if (!config('cms.' . $module_type . '.show_type'))
            <div class="col-sm-12">
                {{ Form::hidden('type', null, array('class'=>'form-control')) }}
            </div>
        @endif
    </div>

    <?php if (Sentinel::inRole('admin') || Sentinel::inRole('admin')): ?>
    @include(viewPrefixCmsNamespace('admin/partials/links'),(linksArray($module_type,$model,$lang)))
    <?php endif;?>

    @if($single_item == false)
        @if($show_start_date == true)
            <div class="form-group">
                <label for="start_date">
                    Start date:
                </label>
                {{ Form::text('start_date', null, array('class'=>'js-flatpickr form-control')) }}

            </div>
        @endif

        @if($show_end_date == true)
            <div class="form-group">
                <label for="end_date">
                    End date:
                </label>
                {{ Form::text('end_date', null, array('class'=>'js-flatpickr form-control')) }}

            </div>
        @endif

        @if($show_type == true)
            <div class="form-group">
                <label for="type">
                    Type:
                </label>
                {{ Form::select('type', $types, null, array('class'=>'form-control')) }}

            </div>
        @endif

        @if($show_version == true)
            <div class="form-group">
                <label for="version">
                    Version:
                </label>
                {{ Form::text('version', null, array('class'=>'form-control')) }}

            </div>
        @endif


        <div class="form-group">
            <label for="status">
                Is active:
            </label>
            @if($model->status)
                <span class="badge badge-pill badge-success">online</span>
            @else
                <span class="badge badge-pill badge-info">offline</span>
            @endif

        </div>
@endif
<!--end basic-->
</div>

<div class="card card-body">
    <h2 class="content-heading pt-0">Translated content</h2>
    <div class="block block-rounded block-bordered">
        <ul class="nav nav-tabs nav-tabs-block" role="tablist">
            @if($custom_view != viewPrefixCmsNamespace('admin.partials.form'))
                @foreach($languages as $admin_lang)
                    <?php
                    $url = ($admin_lang['short'] == $lang ? '#' : url()->to("icontrol/items/$module_type/$action", array('lang' => $admin_lang['short'], 'id' => $model->id)));
                    $status = array_key_exists($admin_lang['short'], $item_languages) ? $item_languages[$admin_lang['short']] : '';
                    ?>
                    <li class="nav-item">
                        <a class="nav-link lang_content {{ $admin_lang['short'] == $lang ? 'active':'' }}"
                           href="{{ $url }}">{{ $admin_lang['short'] }}
                            <span class="badge badge-pill badge-{{ ($status == 'online' ?'success' : ($status == 'edit' ? 'warning' : 'primary')) }}">{{ $status != "" ? $status : "offline" }}</span>
                        </a>
                    </li>
                @endforeach
            @else
                <?php $default_lang = config('cms.default_locale');?>
                <li class="nav-item active">
                    <a class="nav-link lang_content" href="#">{{ $default_lang }}
                        <span class="label label-primary"></span>
                    </a>
                </li>
            @endif
        </ul>
        <div class="tab-content tabcontent-border">
            @if($action == 'update')


                @include(viewPrefixCmsNamespace('admin.partials.form_draft_content'))

                @if($model->my_content_online != null)
                    <div class="block">
                        <div id="accordion2" class="accordion" role="tablist" aria-multiselectable="true">
                            <div class="block block-rounded mb-1">
                                <div class="block-header block-header-default" role="tab" id="headingOne">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse1"
                                           aria-expanded="true" aria-controls="collapseOne">
                                            Online content
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapse1" class="collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="block-content">
                                        <a href="{{ url()->to("icontrol/items/$module_type/revert/$lang/$model->id") }}"
                                           class="button">
                                            <button type="button btn-primary" class="btn btn-default">Revert to this
                                                version
                                            </button>
                                            <!--Mandatory CMS fields-->
                                        @include(viewPrefixCmsNamespace('admin.partials.online.text'), ['title'=>'Meta Title','type'=>'seo_title'])
                                        @include(viewPrefixCmsNamespace('admin.partials.online.text'), ['title'=>'Slug','type'=>'slug'])
                                        @include(viewPrefixCmsNamespace('admin.partials.online.text'), ['title'=>'Meta Description','type'=>'seo_description'])

                                        <!--Content type item fields-->
                                            @foreach( config('cms.'.strtoupper($module_type).'.fields') as $field_arr )
                                                @if( viewExists('admin.partials.online.'.$field_arr['form']) )
                                                    @include( viewPrefixCmsNamespace('admin.partials.online.'.$field_arr['form']) , $field_arr )
                                                @else
                                                    @include( viewPrefixCmsNamespace('admin.partials.online.text'), $field_arr )
                                                @endif
                                            @endforeach
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endif
            <div class="panel-body panel-default">
                @include(viewPrefixCmsNamespace('admin.partials.form_fields'), array('model'=>$model,'error' => isset($error) ? $error : null, 'errors' => isset($errors) ? $errors : null, 'module_type'=>strtolower($module_type), 'action' => $action))
            </div>
            <hr/>
            <div class="form-group">
                @include(viewPrefixCmsNamespace('admin.partials.form_draft_content'))
            </div>
            @if(!array_key_exists($lang, $item_languages))
                <div class="form-group">
                    @foreach($languages as $admin_lang)
                        <?php $curr_lang = $admin_lang['short'];?>

                        @if(array_key_exists($curr_lang, $item_languages) && $curr_lang != $lang)
                            <a href="{{ url()->to("icontrol/items/$module_type/copylang/$model->id/$curr_lang/$lang") }}"
                               class="btn btn-default">
                                Copy from {{ $curr_lang }}
                            </a>
                        @endif

                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>

{{ Form::close() }}

