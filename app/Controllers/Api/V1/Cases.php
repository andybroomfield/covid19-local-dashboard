<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;

class Cases extends BaseController
{
	public function index() {

		// Fetch area ids (required)
		$areas = $this->request->getGet('area_id');
		if ($areas)
		{
			$areas = explode(';', $areas);
		}

		// Get cases summary
		$cases = $this->casesModel->summary($areas);

		// If have results, echo JSON.
    if (empty($cases))
    {
      return $this->response->setJSON(['error' => 'No cases for that area.']);
    }
		return $this->response->setJSON($cases);
	}
}
