<?php
  $case_str = '';
  $date_str = '';
  foreach ($cases as $case)
  {
    $case_str .= esc($case['daily']) . ', ';
    $date_str .= '"' . date('d/m/Y', strtotime(esc($case['date']))) . '", ';
  }
  $case_str = rtrim($case_str, ', ');
  $date_str = rtrim($date_str, ', ');
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js" integrity="sha256-qoN08nWXsFH+S9CtIq99e5yzYHioRHtNB9t2qy1MSmc=" crossorigin="anonymous"></script>
<canvas id="cases-chart" class="cases-chart" width="400" height="400"></canvas>
<script>
var ctx = document.getElementById('cases-chart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $date_str; ?>],
        datasets: [{
            label: '# of Cases',
            data: [<?php echo $case_str; ?>],
            backgroundColor: ['#000000']
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
