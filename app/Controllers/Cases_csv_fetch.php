<?php namespace App\Controllers;

use Config\Services;

class Cases_csv_fetch extends BaseController
{
	public function index()
	{
		// Fetch csv file.
		// https://c19downloads.azureedge.net/downloads/csv/coronavirus-cases_latest.csv
		$csv_file_url = 'https://c19downloads.azureedge.net/downloads/csv/coronavirus-cases_latest.csv';
		$curlRequest = Services::curlrequest();
		$fileResponse = $curlRequest->request('GET', $csv_file_url);
		if ($fileResponse->getStatusCode() == '200')
		{
			// Get file, decode and set up loop.
			$file = $fileResponse->getBody();
			$file = gzdecode($file);
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
				if (count($headers) != count($data))
				{
      		// corrupt row
      		continue;
    	  }
				$row_data = array_combine($headers, $data);

				// Area.
				$area_key = $row_data['Area name'].$row_data['Area type'];
				if (isset($areas[$area_key]))
				{
					$area_id = $areas[$area_key];
				} else
				{
					$area_values =
					[
						'name' => $row_data['Area name'],
						'type' => $row_data['Area type'],
						'code' => $row_data['Area code'],
					];
					$area_id = $this->areasModel->updateOrInsert($area_values);
					$areas[$area_key] = $area_id;
				}

				// Case row.
				$case_row_values =
				[
		      'area_id' => $area_id,
		      'daily' => $row_data['Daily lab-confirmed cases'],
		      'cumlitive' => $row_data['Cumulative lab-confirmed cases'],
		      'rate' => $row_data['Cumulative lab-confirmed cases rate'],
		      'date' => $row_data['Specimen date'],
		    ];
				$case_row_id = $this->casesModel->updateOrInsert($case_row_values);

				$row_number++;
			}
		}

		$output_values =
		[
			'rows' => $row_number,
			'areas' => count($areas),
		];
		return $this->response->setJSON($output_values);
	}

	//--------------------------------------------------------------------

}
