<?php namespace App\Controllers;

use Config\Services;

class Cases_csv_fetch extends BaseController
{
	public function index($area_type = NULL, $force = NULL)
	{
		// Increase max execution time to prevent timeout.
		// This is due to the size of the imports.
		set_time_limit(600);

		// Check most recent case.
		$most_recent_case_date = $this->casesModel->mostRecentCaseDate($area_type);

		// Unix timestamp for when to download next cases (5pm next day).
		$next_download_timestamp = strtotime(($most_recent_case_date ?? '2020-01-01') . ' + 2 Days') + 61200;

		// If not yet time to download new cases.
		if (time() < $next_download_timestamp && $force != 'force')
		{
			// Set a message not yet time to download.
			$output_values =
			[
				'message' => 'Not time to download yet!',
				'most_recent_case_date' => $most_recent_case_date,
				'next_download' => date('Y-m-d G:i', $next_download_timestamp),
			];
		}
		else
		{

			// Fetch csv file.
			// https://c19downloads.azureedge.net/downloads/csv/coronavirus-cases_latest.csv
			// $csv_file_url = 'https://coronavirus.data.gov.uk/downloads/csv/coronavirus-cases_latest.csv';
			
			// Define csv file url.
			$csv_file_url = 'https://api.coronavirus.data.gov.uk/v2/data?metric=cumCasesBySpecimenDate&metric=newCasesBySpecimenDate&metric=cumCasesBySpecimenDateRate&metric=newCasesBySpecimenDateRollingRate&format=csv';
			
			// If an area type defined and is known, add it to the url.
			if (in_array($area_type, ['ltla', 'utla', 'region', 'nation']))
			{
				$csv_file_url .= '&areaType=' . $area_type;
			}

			$total_rows = 0;
			$total_areas = 0;
  			$curlRequest = Services::curlrequest();
  			$fileResponse = $curlRequest->request('GET', $csv_file_url);
  			if ($fileResponse->getStatusCode() == '200')
  			{
  				// Get file, decode and set up loop.
  				$file = $fileResponse->getBody();
  				// $file = gzdecode($file);
  				$rows = explode("\n", $file);
  				$row_number = 0;
  				$headers = [];
  				$areas = [];
  				// Loop through each
  				foreach ($rows as $row)
  				{
  					$data = str_getcsv($row);
  					// If 0 row - get headers
  					if ($row_number == 0)
  					{
  						$headers = $data;
  						$row_number++;
  						continue;
  					}
  					// corrupt row
  					if (count($headers) != count($data))
  					{
  	      				continue;
  	    	 		}
  
  					// Make row into associative array
  					$row_data = array_combine($headers, $data);
  
  					// If this is todays date, skip it as data is unreliable.
  					if ($row_data['date'] == date('Y-m-d'))
  					{
  						continue;
  					}

					// Skip over anything older than 15 days unless forced.
  					if (strtotime($row_data['date']) < strtotime('-15 days') && empty($force))
  					{
  						continue;
  					}
  
  					// Area.
  					$area_key = $row_data['areaName'].$row_data['areaType'];
  					if (isset($areas[$area_key]))
  					{
  						$area_id = $areas[$area_key];
  					} else
  					{
  						$area_values =
  						[
  							'name' => $row_data['areaName'],
  							'type' => $row_data['areaType'],
  							'code' => $row_data['areaCode'],
  						];
  						$area_id = $this->areasModel->updateOrInsert($area_values);
  						$areas[$area_key] = $area_id;
  					}
  
  					// Case row.
  					$case_row_values =
  					[
						'area_id' => $area_id,
						'daily' => $row_data['newCasesBySpecimenDate'],
						'cumlitive' => $row_data['cumCasesBySpecimenDate'],
						'rate' => $row_data['cumCasesBySpecimenDateRate'],
						'rolling_rate' => $row_data['newCasesBySpecimenDateRollingRate'],
						'date' => $row_data['date'],
					];
  					$case_row_id = $this->casesModel->updateOrInsert($case_row_values);
  
  					$row_number++;
  				}
  			}
  			$total_rows += $row_number;
  			$total_areas += count($areas);

			// Output rows and areas fethced.
			$output_values =
			[
				'rows' => $total_rows,
				'areas' => $total_areas,
			];
		}

		// Output response.
		return $this->response->setJSON($output_values);
	}

	//--------------------------------------------------------------------

}
