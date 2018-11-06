<table id="posts" class="table table-striped <?= $sortable ? 'sortable':'' ?>">
    <thead>
        <th></th>
        <th>Sort</th>
        <th>Published</th>
        <th>Description</th>
        <th>Type</th>
        <th>Collection</th>
        <th>Languages</th>
        <th>Remove</th>
    </thead>

    <tbody>
        <?php if($items && count($items) > 0): ?>
            <?php foreach($items as $item): ?>
                <tr class="ui-state-default" data-id="<?= $item->id?>" data-cat="<?= $active_cat ?>">
                    <td style="width:<?= $sortable ? '35px':'1px' ?>">
                        <span class="hoverimg fa fa-fw fa-arrows-v"></span>
                    </td>

                    <td style="width:30px">
                        <span class="badge row-index"><?= $sortable ? $item->sort : '' ?></span>
                    </td>

                    <td style="width:30px">
                        <a href="#" title="Put offline" style="<?= ($item->status)?'':'display:none' ?>" class="publish" data-publish="false" data-id="<?= $item->id ?>"><span class="fa fa-fw fa-toggle-on"></span></a>
                        <a href="#" title="Put online" style="<?= (!$item->status)?'':'display:none' ?>" class="publish" data-publish="true" data-id="<?= $item->id ?>"><span class="fa fa-fw fa-toggle-off"></span></a>
                    </td>

                    <td><?= $item->description ?></td>

                    <td><?= $item->LinksFirstType('PRODUCT_TYPE')->description ?></td>

                    <td><?= $item->LinksFirstType('COLLECTION')->description ?></td>

                    <td class="languages-list">
                        <?php foreach($languages[$item->id] as $key => $value): ?>
                            <a href="<?= url()->to("icontrol/items/$module_type/update", array('lang'=>$key,'id' => $item->id)); ?>" title="update"><span class="label label-<?= ($value == 'online' ?'success' : ($value == 'edit' ? 'warning' : 'primary')) ?>"><?= $key ?></span></a>
                        <?php endforeach; ?>
                    </td>

                    <td style="width:30px">
                        <a href="#" class="delete" data-title="<?= $item->description ?>" data-id="<?= $item->id?>" title="delete"><span class="fa fa-fw fa-trash">&nbsp;</span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>