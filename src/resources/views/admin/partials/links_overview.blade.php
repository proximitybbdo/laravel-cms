<div>
    <?php foreach($links as $key => $link): ?>
        <select name="link_<?= $key ?>" class="links">
            <option value="all" <?= $category_id == null ? "selected='selected'" : '' ?>>
                All {{ Config::get('cms.' . $key . '.description') }}
            </option>

            <?php foreach($link['items'] as $item): ?>
                <option value="<?= $item->id ?>" <?= $item->id == $category_id ? "selected='selected'" : '' ?>>
                    <?= $item->description ?>
                </option>
            <?php endforeach; ?>
        </select>

      </br></br>
  <?php endforeach; ?>
</div>
