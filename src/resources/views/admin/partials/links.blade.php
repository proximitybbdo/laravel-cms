@foreach ($links as $key => $link)
    <div class="form-group " data-link-type="{{ $key }}">
        <label for="{{ $link['field'] }}">{{ $link['description'] }}: </label>

        @if ($link['type'] == "single")
            <div class="form-group">
                <select class="form-control links" name="{{ $link['group_field'] }}">
                    @foreach ($link['items'] as $item)
                        <option value="{{ $item->id }}" {{ $item->item_id != null ? 'selected' : '' }}>{{ $item->description }}</option>
                    @endforeach
                </select>
            </div>
        @else
            @if ($link['input_type'] != "chosen")
                <div class="row">
                    <div class="form-group col-sm-8">
                        <ul class="col-sm-12 selected-links" id="selected-{{ $link['field'] }}">
                            @foreach ($link['items'] as $item)
                                @php
                                    $checked = $item->item_id != null ? true : false;
                                @endphp
                                @if(array_key_exists($item->id, old('linked_items_' . $key, [])))
                                    @php
                                        $checked = true;
                                    @endphp
                                @endif
                                <li id="li-link-{{$key}}-{{ $item->id }}"
                                    class="label label-primary" {{ $checked == true ? '' : "style=display:none" }}>
                                    {{ $item->description }}
                                    {!! Form::checkbox($link['field'].'['.$item->id.']', $item->id, $checked, array( 'id' => 'link-'.$key.'-'.$item->id, 'class' => 'hide')) !!}
                                    <div class="remove-link" style="display: inline-block;"><i
                                                class="fa fa-times-circle"
                                                aria-hidden="true"></i></div>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                    <div class="col-sm-4">
                        <button class="link-add-button btn btn-success"
                                data-linked-module-type="{{ $key }}"
                                {{ $custom_view == 'admin.partials.form' ? 'disabled' : '' }} data-item-id="{{ $model != null ? $model->id : null }}">
                            Add {{ config('cms.' . $key . '.description') }}
                        </button>
                    </div>
                </div>
            @else
                <div class="form-group">
                    <select id="{{ $link['group_field'] }}" class="form-control links chosen_links"
                            name="{{ $link['group_field'] }}" multiple>
                        @foreach ($link['items'] as $item)
                            <option value="{{ $item->id }}" {{ $item->item_id != null ? 'selected' : '' }}>{{ $item->description }}</option>
                            @if( $item->item_id == null )
                                {{ print_r($item) }}
                            @endif
                        @endforeach
                    </select>
                </div>
            @endif
        @endif
    </div>

@endforeach
