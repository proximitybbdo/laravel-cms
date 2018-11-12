@foreach ($links as $key => $link)
  <div class="form-group">
    <label for="link_{{ $key }}" class="control-label col-sm-12"><h2>{{ $link['description'] }} </h2></label>
      <div class="form-group">

        @if ($link['type'] == "single")
        <select class="form-control links" name="linked_items_{{ $key }}">
          @foreach ($link['items'] as $item)
            <option value="{{ $item->id }}" {{ $item->item_id != null ? 'selected' : '' }}>{{ $item->description }}</option>
          @endforeach
        </select>
        @else
          <select id="linked_items" class="form-control links chosen_links" name="linked_items_{{ $key }}[]" multiple>
            @foreach ($link['items'] as $item)
              <option value="{{ $item->id }}" {{ $item->item_id != null ? 'selected' : '' }}>{{ $item->description }}</option>
            @endforeach
          </select>
        @endif
        
      </div>
  </div>
@endforeach