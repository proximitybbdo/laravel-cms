
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

  {{ Form::model($model, array('enctype'=>"multipart/form-data",'id'=>'form-'.$module_type, "data-module-type"=> $module_type )) }}
  {{ Form::hidden('id',null) }}
  {{ Form::hidden('lang',null) }}
  {{ Form::hidden('sort', null) }}

  <input name="input_changes" id="input_changes" type="hidden" />

  <h1>{{ $module_title }}</h1>

  <hr/>

  <!-- basic data -->
  <div class="form-group">
      <label class="control-label col-sm-12" for="description">
        Description:
      </label>

      <div class="col-sm-12">
        {{ Form::text('description', null, array('class'=>'form-control')) }}
      </div>

      @if (!\Config::get('cms.' . $module_type . '.show_type'))
          <div class="col-sm-12">
              {{ Form::hidden('type', null, array('class'=>'form-control')) }}
          </div>
      @endif
  </div>

  <?php if (Sentinel::inRole('super_admin') || Sentinel::inRole('admin')) : ?>
      <div class="form-group" ?>
          @include('bbdocms::admin/partials/links',(linksArray($module_type,$model,$lang)))
      </div>
  <?php endif; ?>

  @if($single_item == false)
    @if($show_start_date == true)
      <div class="form-group">
          <label class="control-label col-sm-2" for="description">
            Start date:
          </label>
          <div class="col-sm-12">
            {{ Form::text('start_date', null, array('class'=>'datepicker form-control')) }}
          </div>
      </div>
    @endif

    @if($show_end_date == true)
      <div class="form-group">
          <label class="control-label col-sm-2" for="description">
            End date:
          </label>
          <div class="col-sm-12">
            {{ Form::text('end_date', null, array('class'=>'datepicker form-control')) }}
          </div>
      </div>
    @endif

    @if($show_type == true)
      <div class="form-group">
          <label class="control-label col-sm-2" for="description">
            Type:
          </label>
          <div class="col-sm-12">
            {{ Form::select('type', $types, null, array('class'=>'form-control')) }}
          </div>
      </div>
    @endif

    @if($show_version == true)
      <div class="form-group">
          <label class="control-label col-sm-2" for="description">
            Version:
          </label>
          <div class="col-sm-12">
            {{ Form::text('version', null, array('class'=>'form-control')) }}
          </div>
      </div>
    @endif

    @if($custom_view == 'bbdocms::admin.partials.form')
      <div class="form-group col-sm-12">
        <label class="control-label col-sm-1" for="status">
          Is active:
        </label>
        <div class="controls col-sm-2">
          @if($model->status)
             <span class="label label-success">online</span>
          @else
            <span class="label label-default">offline</span>
          @endif
        </div>
      </div>
    @endif
  @endif
  <!--end basic-->

  <div class="form-group nav-tabs-panel nav-tabs-panel-grey">
  <ul class="nav nav-tabs">
   @if($custom_view != 'bbdocms::admin.partials.form')
     @foreach($languages as $admin_lang)
      <?php 
      $url = ($admin_lang['short'] == $lang ? '#' : url()->to("icontrol/items/$module_type/$action", array('lang'=>$admin_lang['short'],'id' => $model->id)));
      $status = array_key_exists($admin_lang['short'], $item_languages)? $item_languages[$admin_lang['short']]:''; 
       ?>
      <li class="{{ $admin_lang['short'] == $lang ? 'active':'' }}"><a class="lang_content" href="{{ $url }}">{{ $admin_lang['short'] }} <span class="label 
      label-{{ ($status == 'online' ?'success' : ($status == 'edit' ? 'warning' : 'primary')) }}">{{ $status }}</span></a></li>
     @endforeach
  @else 
      <?php $default_lang = \Config::get('cms.default_locale'); ?>
      <li class="active"><a class="lang_content" href="#">{{ $default_lang }} <span class="label label-primary"></span></a></li>
  @endif
  </ul>
  </div>
  <div class="panel panel-grey">
    @if($action == 'update')
    <div class="panel-header">
      <div class="form-group">
        @include('bbdocms::admin.partials.form_draft_content')
        <div class="controls online_content" style="display:none">
          <a href="{{ url()->to("icontrol/items/$module_type/revert/$lang/$model->id") }}" class="button">
          <button type="button btn-primary" class="btn btn-default">Revert to this version</button>
          </a>
        </div>
      </div>
      <ul class="nav nav-tabs">
        <li role="presentation" id="show_draft" class="active"><a href="#">Draft</a></li>
        @if($model->my_content_online != null)
        <li role="presentation" id="show_online"><a href="#">online</a></li>
        @endif
      </ul>
    </div>
    @endif
    <div class="panel-body panel-default">
    @include('bbdocms::admin.partials.form_fields', array('model'=>$model,'error' => isset($error) ? $error : null, 'errors' => isset($errors) ? $errors : null, 'module_type'=>strtolower($module_type), 'action' => $action))
    </div>
    <hr/>
    <div class="form-group">
      @include('bbdocms::admin.partials.form_draft_content')

      <div class="controls online_content" style="display:none">
        <a href="{{ url()->to("icontrol/items/$module_type/revert/$lang/$model->id") }}" class="button">
        <button type="button btn-primary" class="btn btn-default">Revert to this version</button>
        </a>
      </div>
    </div>
    @if(!array_key_exists($lang, $item_languages))
      <div class="form-group">
          @foreach($languages as $admin_lang)
          <?php $curr_lang = $admin_lang['short']; ?>
          
            @if(array_key_exists($curr_lang, $item_languages) && $curr_lang != $lang)
              <a href="{{ url()->to("icontrol/items/$module_type/copylang/$model->id/$curr_lang/$lang") }}" class="btn btn-default">
                Copy from {{ $curr_lang }}
              </a>
            @endif
          
          @endforeach    
      </div>
    @endif
  <hr/>
  </div>
  
  {{ Form::close() }}