<?php include APPPATH.'/Views/includes/header.php';

include APPPATH.'/Views/components/cases/area-stats.php';
?>

<div class="area-cases">
  <h2 class="area-cases__title">Case history</h2>
  <?php
    include APPPATH.'/Views/components/cases/chart.php';
    include APPPATH.'/Views/components/cases/table.php';
  ?>
  <p class="area-cases__footer">
    <a class="button" role="button" href="/?area_id=<?php echo esc($area['id']); ?>">View in dashboard</a>
  </p>
</div>

<?php include APPPATH.'/Views/includes/footer.php'; ?>
