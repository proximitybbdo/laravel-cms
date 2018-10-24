<?php foreach($links as $key => $link): ?>
<div class="form-group " data-link-type="{{ $key }}">
    <label for="{{ $link['field'] }}" class="control-label col-sm-12"><?= $link['description'] ?>: </label>

    <?php if($link['type'] == "single") : ?>
    <div class="form-group">
        <select class="form-control links" name="{{ $link['group_field'] }}">
            <?php foreach($link['items'] as $item): ?>
            <option value="<?= $item->id ?>" <?= $item->item_id != null ? 'selected' : '' ?>><?= $item->description ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php else: ?>
    <?php if($link['input_type'] != "chosen") : ?>
    <div class="row">
        <div class="form-group col-sm-8">
            <ul class="col-sm-12 selected-links" id="selected-{{ $link['field'] }}">
                <?php foreach($link['items'] as $item): ?>
                <?php
                $checked = $item->item_id != null ? true : false;
                if (array_key_exists($item->id, old('linked_items_' . $key, []))) {
                    $checked = true;
                }
                ?>
                <li id="li-link-{{$key}}-{{ $item->id }}"
                    class="label label-primary" <?= $checked == true ? '' : "style=display:none" ?>>
                    {{ $item->description }}
                    {!! Form::checkbox($link['field'].'['.$item->id.']', $item->id, $checked, array( 'id' => 'link-'.$key.'-'.$item->id, 'class' => 'hide')) !!}
                    <div class="remove-link" style="display: inline-block;"><i class="fa fa-times-circle"
                                                                               aria-hidden="true"></i></div>
                </li>
                <?php endforeach; ?>
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
    <?php else: ?>
    <div class="form-group">
        <select id="{{ $link['group_field'] }}" class="form-control links chosen_links"
                name="{{ $link['group_field'] }}" multiple>
            <?php foreach($link['items'] as $item): ?>
            <option value="<?= $item->id ?>" <?= $item->item_id != null ? 'selected' : '' ?>><?= $item->description ?></option>
            @if( $item->item_id == null )
                {{ print_r($item) }}
            @endif
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!--Removed, overview all links with checkboxes-->
<!--Removed, condition for checkboxes to be single or multiple choice-->
<!--Removed, add new $module_type button-->

<?php endforeach; ?>
