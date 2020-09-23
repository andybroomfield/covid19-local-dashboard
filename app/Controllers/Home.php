<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		// Get selected areas.
		$area_ids = $this->request->getVar('area_id');
		if ($area_ids)
		{
			$area_ids = explode('+', $area_ids);
		}

		// Get areas.
		$areas = $this->areasModel->search('ltla');

		// Get cases.
		$cases = $this->casesModel->summary($area_ids);

		// Enable form helper.
		helper('form');

		// Set data.
		$data = [
			'html_title' => 'Covid 19 Area Dashboard',
			'meta_description' => 'View UK Local area Covid cases data.',
			'page_title' => 'Covid 19 Area Dashboard',
			'areas' => $areas,
			'area_ids' => $area_ids ?? [],
			'cases' => $cases,
		];

		// Show dashboard view.
		return view('pages/home', $data);
	}

	public function update() {

		$area_ids = $this->request->getPost('area_id');
		$area_ids = implode('+', $area_ids);
		return redirect()->to('/?area_id=' . $area_ids);
	}

	//--------------------------------------------------------------------

}
