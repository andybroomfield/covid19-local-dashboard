<div class="area-stats">
  <?php
    $stat_map = [
      'rolling_cases' => '7 day cases',
      'rolling_rate' => '7 day rate',
      'total_cases' => 'Total cases',
      'total_rate' => 'Total rate',
    ];
    foreach ($stat_map as $stat_key => $stat_title)
    {
      $stat = $summary[$stat_key];
      include APPPATH.'/Views/components/cases/stat.php';
    }
  ?>
</div>
