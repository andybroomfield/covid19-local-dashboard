<table class="cases-table">
  <thead class="cases-table__header">
    <tr class="cases-table__row cases-table__row--header">
      <th class="cases-table__date cases-table__date--header">Date</th>
      <th class="cases-table__cases cases-table__cases--header">Cases</th>
      <th class="cases-table__cumlitive cases-table__cumlitive--header">Cumlitive</th>
    </tr>
  </thead>
  <tbody class="cases-table__body">
    <?php foreach ($cases as $case): ?>
      <tr class="cases-table__row">
        <td class="cases-table__date"><?php echo esc($case['date']); ?></td>
        <td class="cases-table__cases"><?php echo esc($case['daily']); ?></td>
        <td class="cases-table__cumlitive"><?php echo esc($case['cumlitive']); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
