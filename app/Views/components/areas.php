<?php echo form_open(); ?>
<h2>Select areas to display</h2>
<ul>
  <?php foreach ($areas as $area): ?>
    <li>
      <?php include APPPATH.'/Views/components/area-form-element.php'; ?>
    </li>
  <?php endforeach; ?>
</ul>
<?php echo form_submit('update', 'Update dashboard');
echo form_close(); ?>
