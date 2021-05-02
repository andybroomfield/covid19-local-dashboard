<div class="summaries-wrap">
  <?php if (!empty($cases)): ?>
    <div class="summaries">
      <?php foreach ($cases as $case_area_id => $case_data):
        include APPPATH.'/Views/components/summary.php';
      endforeach; ?>
    </div>
    <div class="summaries-info">
      <ul>
        <li><sup>*</sup> 7 day cases exclude the most recent 4 days.</li>
        <li><sup>**</sup> Per 100,000 people.</li>
      </ul>
      <p>Data from Public Health England / <a href="https://coronavirus.data.gov.uk/about-data">Coronavirus Dashboard</a>.</p>
    </div>
  <?php else:
    include APPPATH.'/Views/components/no-summaries.php';
  endif; ?>
</div>
