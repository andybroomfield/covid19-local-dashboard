<table>
  <thead>
    <tr>
      <th>Date</th><th>Cases</th><th>Cumlitive</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cases as $case): ?>
      <tr>
        <td><?php echo esc($case['date']); ?></td>
        <td><?php echo esc($case['daily']); ?></td>
        <td><?php echo esc($case['cumlitive']); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
