<!-- <div style="height: 50px;">
<a href="#" class="purge" >[purge]</a>
</div> -->
<?php foreach($files as $file) { ?>

  <?php if($manager_type == 'file'){  ?>
  <div class="radio file-item file-item--file text-center <?= $file->id == $value ? "active" : ""  ?>">
  <?php }else{ ?>
  <div class="radio file-item text-center <?= $file->id == $value ? "active" : ""  ?>">
  <?php } ?>


      <?php if($manager_type == 'image'){  ?>
      <img src="<?= url("uploads/$manager_type/thumbs/" . $file->file) ?>">
       <?php } ?>

      <?php if($manager_type == 'file'){  ?>
      <h2><i class="fa fa-file-pdf-o"></i></h2><a href="<?= url("uploads/$manager_type/" . $file->file) ?>" target="_blank"><?= $file->file ?></a>
      <?php } ?>

      <div class="file-item__hover">
      <?php if($mode != "popup"){  ?>
      <a href="#" class="remove_image" data-id="<?= $file->id ?>"><i class="fa fa-trash-o"></i></a>
        <?php if(in_array($file->id, [])) { //if(in_array($file->id, [$content_links])) ?>
          <i class="fa fa-link" title="linked"></i>
        <?php } ?>
      <?php } ?>

      <?php if($mode === "popup"){  ?>
          <a href="#" class="select_image" data-manager-type="<?= $manager_type ?>" data-module="<?= $module_type ?>" data-id="<?= $file->id ?>" data-file="<?= $file->file ?>" data-input="<?= $input_id ?>"><i class="fa fa-check"></i><span><?= $file->file ?></span></a>
          <?php if($file->id == $value){  ?>
               <a href="#" class="detach_image" data-module="<?= $module_type ?>" data-id="<?= $file->id ?>" data-input="<?= $input_id ?>">detach</a>
          <?php } ?>
        <?php } ?>
        <?php if($mode != "popup"){  ?>
          <?php foreach($categories as $category){ ?>
          <div>
            <input id="<?= $file->id . '-' . $category ?>" type="checkbox" name="<?= $file->id . '-' . $category ?>" value="<?= $category ?>" data-id="<?= $file->id ?>" <?= in_array($category, $file->modules()->pluck('module_type')->all())?"checked":"" ?>/>
            <label for="<?= $file->id . '-' . $category ?>"><?= $category ?></label>
          </div>
          <?php } ?>
        <?php }  ?>
      </div>
  </div>
<?php } ?>
