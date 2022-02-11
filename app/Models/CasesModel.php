<?php namespace App\Models;

use CodeIgniter\Model;

class CasesModel extends Model
{
  protected $table = 'cases';

  protected $allowedFields = ['area_id', 'daily', 'cumlitive', 'rate', 'rolling_rate', 'date'];

  public function mostRecentCaseDate() {
    $last_case = $this->asArray()
                      ->select('date')
                      ->orderBy('date', 'DESC')
                      ->first();
    return $last_case['date'];
  }

  public function updateOrInsert($values)
  {
    $area_id = $values['area_id'];
    $date = $values['date'];
    $existing_case_row = $this->asArray()
                              ->where('area_id', $area_id)
                              ->where('date', $date)
                              ->first();
    if (empty($existing_case_row))
    {
      // Insert
      $this->insert($values);
      $id = $this->db->insertId();
    } elseif ($values !== $existing_case_row)
    {
      // Update
      $id = $existing_case_row['id'];
      $this->update($id, $values);
    }
    return $id;
  }

  /**
   * Summary of cases by area.
   *
   * Use a date range 14 most recent days.
   * Remove the most recent 4 from summary calculations.
   * @param  Array|NULL  $areas
   *   Area of area IDs
   * @return Array
   *   Array keyed by area ID.
   *   Will contain:
   *    - Total number of cases.
   *    - Total case rate (per 100,000).
   *    - Cases over last 7 days (last cases minus 4 days ago).
   *    - Cases rolling 7 days (last cases minus 4 days ago).
   *    - Rate per 100,000 over 7 days (last cases minus 4 days ago).
   *    - Case data for last 14 days.
   *  Removing the last 4 days is to match Public Health England.
   */
  public function summary($areas)
  {
    // Query as array.
    $this->asArray();

    // Add date filter.
    $date_to = $this->mostRecentCaseDate();
    $date_from = date('Y-m-d', strtotime($date_to . ' -14 days'));
    $this->where('date >=', $date_from);

    // Tables to select.
    $this->select('cases.area_id, areas.name, areas.type, areas.slug, cases.daily, cases.cumlitive, cases.rate, cases.rolling_rate, cases.date');

    // Join areas table to get the area name and type.
    $this->join('areas', 'areas.id = cases.area_id', 'left');

    // Set areas filter.
    if (!empty($areas))
    {
      $this->whereIn('area_id', $areas);
    }

    // Do query.
    $cases = $this->findAll();

    // If no cases found, return false at this point.
    if (empty($cases))
    {
      return false;
    }

    // Build array of cases.
    $case_array = [];
    foreach ($cases as $case)
    {
      $case_array[$case['area_id']][$case['date']] = $case;
    }

    // Build summaries.
    $summary = [];
    foreach($case_array as $area_id => $area_cases)
    {
      // Sort the area cases by date (will be the key).
      ksort($area_cases);

      // Use the first case row for initial rate calculation.
      $first_area_case_row = reset($area_cases);

      // Use last row for the totals.
      $last_area_case_row = end($area_cases);

      // Third row for rate calculation.
      $third_area_case_slice = array_slice($area_cases, 3, 1);
      $third_area_case_row = reset($third_area_case_slice);

      // The case rows to use for calculations, this should be 7 days.
      // Do not use the first 3 rows, or the most recent 4 days.
      // See #9 for reasons.
      // Changed length to 7 from -4, as sometimes not a full 14 day set has been fetched.
      // This led to strange rates being displayed or not found.
      $cases_for_calculations = array_slice($area_cases, 4, 7);

      // The weekly case total.
      $rolling_total = array_sum(array_column($cases_for_calculations, 'daily'));

      // The average cases per day (use count as might not have a weeks data).
      $rolling_avg = $rolling_total / count($cases_for_calculations);

      // The rate, difference between most recent rate and earliest in the week.
      $rolling_rate = end($cases_for_calculations)['rolling_rate'];

      // Remove name and type from cases array.
      foreach($area_cases as &$case_row) {
        unset($case_row['name']);
        unset($case_row['type']);
      }

      // Build a summary for the area.
      $summary[$area_id] = [
        'area'          => $last_area_case_row['name'],
        'type'          => $last_area_case_row['type'],
        'slug'          => $last_area_case_row['slug'],
        'total_cases'   => $last_area_case_row['cumlitive'],
        'total_rate'    => $last_area_case_row['rate'],
        'rolling_cases' => (string) $rolling_total,
        'rolling_avg'   => number_format($rolling_avg, 2),
        'rolling_rate'  => number_format($rolling_rate, 1),
        // Remove the first, thats just for calculng the rolling rate.
        'cases'         => array_slice($area_cases, 1),
      ];
    }

    return $summary;
  }

  public function history($areas)
  {
    // Query as array.
    $this->asArray();

    // Query those in areas.
    $this->whereIn('area_id', $areas);

    // Order by date asc.
    $this->orderBy('date', 'ASC');

    // Find all.
    $cases = $this->findAll();

    // Sort into array by area_id;
    $history = [];
    foreach($cases as $case)
    {
      $history[$case['area_id']][] = $case;
    }
    return $history;
  }
}
