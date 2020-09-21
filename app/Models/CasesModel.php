<?php namespace App\Models;

use CodeIgniter\Model;

class CasesModel extends Model
{
  protected $table = 'cases';

  protected $allowedFields = ['area_id', 'daily', 'cumlitive', 'rate', 'date'];

  protected function mostRecentCaseDate() {
    $last_case = $this->asArray()
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
    } else
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
   * Use a date range 10 most recent days.
   * Remove the most recent 3 from summary calculations.
   * @param  Array|NULL  $areas
   *   Area of area IDs
   * @return Array
   *   Array keyed by area ID.
   *   Will contain:
   *    - Total number of cases.
   *    - Total case rate (per 100,000).
   *    - Cases over last 7 days (last cases minus 3 days ago).
   *    - Cases rolling 7 days (last cases minus 3 days ago).
   *    - Rate per 100,000 over 7 days (last cases minus 3 days ago).
   *    - Case data for last 10 days.
   *  Removing the last 3 days is to match Public Health England.
   */
  public function summary($areas)
  {
    // Query as array.
    $this->asArray();

    // Add date filter.
    $date_to = $this->mostRecentCaseDate();
    $date_from = date('Y-m-d', strtotime($date_to . " -9 days"));
    $this->where('date >=', $date_from);

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

      // Use last row for the totals.
      $last_area_case_row = end($area_cases);

      // Calculate the rolling averages and rate, do not use most recent 3 days.
      $cases_for_calculations = array_slice($area_cases, 0, -3);

      // The weekly case total.
      $rolling_total = array_sum(array_column($cases_for_calculations, 'daily'));

      // The average cases per day (use count as might not have a weeks data).
      $rolling_avg = $rolling_total / count($cases_for_calculations);

      // The rate, difference between most recent rate and earliest in the week.
      $rolling_rate = end($cases_for_calculations)['rate'] - reset($cases_for_calculations)['rate'];

      // Build a summary for the area.
      $summary[$area_id] = [
        'total_cases'   => $last_area_case_row['cumlitive'],
        'total_rate'    => $last_area_case_row['rate'],
        'rolling_cases' => (string) $rolling_total,
        'rolling_avg'   => number_format($rolling_avg, 2),
        'rolling_rate'  => (string) $rolling_rate,
        'cases'         => $area_cases,
      ];
    }

    return $summary;
  }
}
