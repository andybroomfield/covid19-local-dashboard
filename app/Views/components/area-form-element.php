<label class="area-form-element">
  <?php echo form_checkbox('area_id[]', $area['id'], in_array($area['id'], $area_ids)); ?>
  <span><?php echo esc($area['name']); ?></span>
</label>
