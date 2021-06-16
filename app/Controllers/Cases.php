<?php namespace App\Controllers;

class Cases extends BaseController
{
	public function index(string $area_type, string $area_slug)
  {
		// Get area ID.
		$area = $this->areasModel->getBySlug($area_type, $area_slug);

		if (empty($area))
		{
			exit;
		}

		// Get case summary.
		$summaries = $this->casesModel->summary([$area['id']]);
		$summary = reset($summaries);

		// Get full case history.
		$history = $this->casesModel->history([$area['id']]);
		$cases = reset($history);

		// Set data.
		$data = [
			'html_title' => 'Covid 19 cases in ' . $area['name'],
			'meta_description' => 'Case history of covid 19 in ' . $area['name'] ,
			'page_title' => 'Covid 19 cases in ' . $area['name'],
			'area' => $area,
			'summary' => $summary,
			'cases' => $cases,
		];

		// Show dashboard view.
		return view('pages/cases', $data);
  }
}
