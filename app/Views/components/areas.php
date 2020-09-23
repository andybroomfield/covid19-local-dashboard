<?php echo form_open('home/update'); ?>
<h2>Select areas to display</h2>
<ul>
  <?php foreach ($areas as $area): ?>
    <li>
      <?php include APPPATH.'/Views/components/area-form-element.php'; ?>
    </li>
  <?php endforeach; ?>
</ul>
<div class="area-form--actions">
  <?php echo form_submit('update', 'Update dashboard'); ?>
</div>
<?php echo form_close(); ?>
