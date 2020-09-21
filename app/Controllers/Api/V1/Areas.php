<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;

class Areas extends BaseController
{
	public function index()
	{
    $type = $this->request->getGet('type');
    $search = $this->request->getGet('search');

    // With type and search, try to retrive results from model.
    $results = $this->areasModel->search($type, $search);

    // If have results, echo JSON.
    if (empty($results))
    {
      return $this->response->setJSON(['error' => 'No results for that search.']);
    }
		return $this->response->setJSON($results);
  }
}
