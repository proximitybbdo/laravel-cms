<div class="panel panel-info blockcontent_block sortable" data-type="{{ $type }}" id="block_{{ $type }}_{{ $index }}">
  <div class="panel-heading clearfix">
  <div class="col-md-6"><?= $type ?></div>
  <div class="col-md-6"><a class="pull-right delete_block"><i class="fa fa-trash"></i></a><span class="pull-right"><i class="fa fa-arrows"></i></span></div>
  </div>
  <div class="panel-body">
    @foreach( $data['fields'] as $field_arr )
      @include( 'admin.partials.input.'.$field_arr['form'], \InputHelper::inputArray($field_arr,'block',$model,null,$type,$index))
    @endforeach
    
    @if(array_key_exists('links',$data))
    @foreach( $data['links'] as $links_arr )
      @include('admin/partials/links',array('links'=> (\InputHelper::linksArray($module_type,$model,$lang,$type,$version,$index)), 'item_id' => $model->id, 'action' => $action))
    @endforeach
    @endif
  </div>
</div>
