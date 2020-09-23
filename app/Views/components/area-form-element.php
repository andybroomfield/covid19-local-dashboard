<label>
  <?php echo form_checkbox('area_id[]', $area['id'], in_array($area['id'], $area_ids));
  echo esc($area['name']); ?>
</label>
