<?php include APPPATH.'/Views/includes/header.php';

include APPPATH.'/Views/components/cases/area-stats.php';
?>

<div class="cases">
  <?php
    include APPPATH.'/Views/components/cases/chart.php';
    include APPPATH.'/Views/components/cases/table.php';
  ?>
</div>

<?php include APPPATH.'/Views/includes/footer.php'; ?>
