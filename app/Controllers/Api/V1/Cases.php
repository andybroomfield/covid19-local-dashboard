<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;

class Cases extends BaseController
{
	public function index() {

		// Fetch area ids (required)
		$area_ids = $this->request->getGet('area_id');
		if ($area_ids)
		{
			$area_ids = explode('+', $area_ids);
		}

		// Get cases summary
		$cases = $this->casesModel->summary($area_ids);

		// If have results, echo JSON.
    if (empty($cases))
    {
      return $this->response->setJSON(['error' => 'No cases for that area.']);
    }
		return $this->response->setJSON($cases);
	}
}
