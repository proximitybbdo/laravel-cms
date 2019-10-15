<div class="content-block block block-info block-rounded block-bordered blockcontent_block" data-type="{{ $type }}" id="block_{{ $type }}_{{ $index }}">
  <div class="block-header block-header-default bg-gray">
    <h3 class="block-title"><?=$type?></h3>
    <div class="block-options">
        <button data-action="move" data-sort="up" data-id="block_{{ $type }}_{{ $index }}" type="button" class="btn btn-sm btn-primary" ><i class="si si-arrow-up"></i></button>
        <button data-action="move" data-sort="down" data-id="block_{{ $type }}_{{ $index }}" type="button" class="btn btn-sm btn-primary" ><i class="si si-arrow-down"></i></button>
      <button type="button" class="btn-block-option delete_block"><i class="si si-trash"></i></button>
    </div>
  </div>
  <div class="block-content">
    @foreach( $data['fields'] as $field_arr )
      @include( viewPrefixCmsNamespace('admin.partials.input.'.$field_arr['form']), inputArray($field_arr,'block',$model,null,$type,$index))
    @endforeach

    @if(array_key_exists('links',$data))
    @foreach( $data['links'] as $links_arr )
      @include(viewPrefixCmsNamespace('admin/partials/links'),array('links'=> (linksArray($module_type,$model,$lang,$type,$version,$index)), 'item_id' => $model->id, 'action' => $action))
    @endforeach
    @endif
  </div>
</div>