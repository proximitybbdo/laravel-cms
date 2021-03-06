@foreach( $back_module_link as $key => $link )
<div class="modal-header">
  <h4 class="modal-title"> {{ config('cms.' . $key . '.description') }}</h4>
</div>
<div class="modal-body container-fluid">
  <ul id="linked_items" class=" list-unstyled input-group links chosen_links" multiple>
    @foreach($link["items"] as $item)
      <li>
        <input id="input-link-<?= $key ?>-<?= $item->id ?>" type="checkbox" 
        name="linked_items_<?= $key ?>[]" value="<?= $item->id ?>"
        data-link-id="link-{{ $key }}-{{ $item->id }}"
        <?= $item->item_id != null ? 'checked' : '' ?>>
        <label for="input-link-<?= $key ?>-<?= $item->id ?>"><?= $item->description ?></label>
      </li>
    @endforeach
  </ul>
  @if($link['add_item'] && (Sentinel::hasAccess( strtolower($key) . '.create') || Sentinel::inRole('admin') ))
    <a class="btn btn-success create-link-button"
            data-linked-module-type="{{ $key }}">
              Add new {{ config('cms.' . $key . '.description') }} <i class="fa fa-plus-circle"></i>
    </a>
  @endif
</div>
@endforeach
