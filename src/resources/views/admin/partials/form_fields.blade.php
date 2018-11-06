<div class="draft_content">
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Meta information</h3>
        </div>
        <div class="block-content">
            @if (!config('cms.' . strtoupper($module_type) . '.hide_mandatory_fields'))
                <!--Mandatory CMS fields-->
                @include('bbdocms::admin.partials.input.text', inputArray(['title'=>'Page Title','type'=>'page_title','id'=>'title_page'],'content',$model))
                @include('bbdocms::admin.partials.input.text', inputArray(['title'=>'Meta Title (seo & share)','type'=>'seo_title','id'=>'title_seo'],'content',$model))
                @include('bbdocms::admin.partials.input.slug', inputArray(['title'=>'Slug','type'=>'slug'],'content',$model))
                @include('bbdocms::admin.partials.input.text', inputArray(['title'=>'Meta Description','type'=>'seo_description','id'=>'description_seo'],'content',$model))
            @endif
        </div>
    </div>

    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Content</h3>
        </div>
        <div class="block-content">
            <!--Content type item fields-->
            @foreach (config('cms.' . strtoupper($module_type) . '.fields') as $field_arr)
                @include('bbdocms::admin.partials.input.' . $field_arr['form'], inputArray($field_arr, 'content', $model))
            @endforeach
        </div>
    </div>
</div>
@if($block_list != null)
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Blocks</h3>
    </div>
    <div class="block-content">
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
    </div>
    <div class="block-content">

        <div id="blocks">
            @foreach( $model->blocksLang($lang,$version)->get() as $block )
                @include( 'bbdocms::admin.partials.form_block', ['type'=>formatBlockType($block->type),'data'=>config('cms.'.strtoupper($module_type).'.blocks.' . formatBlockType($block->type) ), 'index'=>\InputHelper::indexBlockType($block->type)])
            @endforeach
        </div>

    </div>
</div>
@endif

</div> <!-- end draft_content -->
