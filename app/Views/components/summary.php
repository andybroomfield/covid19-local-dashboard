<div class="area-case-summary" data-area="<?php echo esc($case_area_id); ?>">
  <div class="area-case-summary--header">
    <h2 class="area-case-summary--header--area">
      <?php echo esc($case_data['area']); ?>
    </h2>
    <div class="area-case-summary--header--key-data">
      <h3 class="area-case-summary--header--key-data--header">7 Day Cases<sup>*</sup></h3>
      <div class="area-case-summary--header--key-data--row">
        <div class="area-case-summary--header--key-data--row--cell">
          <h4>Number</h4>
          <p><?php echo esc($case_data['rolling_cases']); ?></p>
        </div>
        <div class="area-case-summary--header--key-data--row--cell">
          <h4>Rate<sup>**</sup></h4>
          <p><?php echo esc($case_data['rolling_rate']); ?></p>
        </div>
      </div>
    </div>
  </div>
  <div class="area-case-summary--details">
    <div class="area-case-summary--details--totals">
      <div class="area-case-summary--details--totals--row">
        <div class="area-case-summary--details--totals-row--cell">
          <h3>Total cases</h3>
          <p><?php echo esc($case_data['total_cases']); ?></p>
        </div>
        <div class="area-case-summary--details--totals--row--cell">
          <h3>Total Rate per 100,000</h3>
          <p><?php echo esc($case_data['total_rate']); ?></p>
        </div>
      </div>
    </div>
    <div class="area-case-summary--details--cases">
      <h3>Cases in the last 10 days</h3>
      <ul class="area-case-summary--details--cases--list">
        <?php foreach($case_data['cases'] as $case): ?>
          <li class="area-case-summary--details--cases--list--item">
            <span class="area-case-summary--details--cases--list--item--header">
              <?php echo date('d/m/Y', strtotime(esc($case['date']))); ?>:
            </span>
            <span class="area-case-summary--details--cases--list--item--number">
              <?php echo esc($case['daily']); ?>
            </span>
          </li>
        <?php endforeach; ?>
    </div>
  </div>
</div>
