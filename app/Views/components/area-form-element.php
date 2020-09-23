<label>
  <?php echo form_checkbox('area', $area['id'], in_array($area['id'], $area_ids));
  echo esc($area['name']); ?>
</label>
