<div class="draft_content">
    <div class="panel panel-default">
        <div class="panel-heading clearfix">Meta information</div>
        <div class="panel-body">
            @if (!Config('admin.' . strtoupper($module_type) . '.hide_mandatory_fields'))
                <!--Mandatory CMS fields-->
                @include('admin.partials.input.text', \InputHelper::inputArray(['title'=>'Page Title','type'=>'page_title','id'=>'title_page'],'content',$model))
                @include('admin.partials.input.text', \InputHelper::inputArray(['title'=>'Meta Title (seo & share)','type'=>'seo_title','id'=>'title_seo'],'content',$model))
                @include('admin.partials.input.slug', \InputHelper::inputArray(['title'=>'Slug','type'=>'slug'],'content',$model))
                @include('admin.partials.input.text', \InputHelper::inputArray(['title'=>'Meta Description','type'=>'seo_description','id'=>'description_seo'],'content',$model))
            @endif
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading clearfix">Content</div>
        <div class="panel-body">
            <!--Content type item fields-->
            @foreach (Config('admin.' . strtoupper($module_type) . '.fields') as $field_arr)
                @include('admin.partials.input.' . $field_arr['form'], \InputHelper::inputArray($field_arr, 'content', $model))
            @endforeach
        </div>
    </div>
</div>

@if($block_list != null)
    <div id="blocks">
        @foreach( $model->blocks_lang($lang,$version)->get() as $block )
            @include( 'admin.partials.form_block', ['type'=>\InputHelper::formatBlockType($block->type),'data'=>Config('admin.'.strtoupper($module_type).'.blocks.' . \InputHelper::formatBlockType($block->type) ), 'index'=>\InputHelper::indexBlockType($block->type)])
        @endforeach
    </div>

    <div class="well">
        <div class="input-group">
            <select class="form-control col-sm-10" id="ddl_add_block">
                @foreach($block_list as $block_type)
                    <option value="{{ $block_type['type'] }}" data-amount="{{ $block_type['amount'] }}">{{ $block_type['description'] }}</option>
                @endforeach
            </select>

            <span class="input-group-btn">
                <button id="btn_add_block" class="btn btn-primary" data-version="{{ $version }}" data-id="{{ $model->id }}" data-lang="{{ $lang }}" data-action="{{ $action }}">Add Block</button>
            </span>
        </div>
    </div>
@endif

</div> <!-- end draft_content -->

<?php if($model->my_content_online != null) { ?>
    <div class="online_content" style="display:none">
        <!--Mandatory CMS fields-->
        @include('admin.partials.online.text', ['title'=>'Meta Title','type'=>'seo_title'])
        @include('admin.partials.online.text', ['title'=>'Slug','type'=>'slug'])
        @include('admin.partials.online.text', ['title'=>'Meta Description','type'=>'seo_description'])

        <!--Content type item fields-->
        @foreach( Config('admin.'.strtoupper($module_type).'.fields') as $field_arr )
            @if( $field_arr['form'] == 'select' || $field_arr['form'] == 'file' )
                @include( 'admin.partials.online.text', $field_arr )
            @else
                @include( 'admin.partials.online.'.$field_arr['form'] , $field_arr )
            @endif
        @endforeach
    </div>
<?php } ?>
