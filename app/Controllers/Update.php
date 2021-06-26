<?php namespace App\Controllers;

class Update extends BaseController
{
	public function index()
	{
		// Migrations controller enabled?
		$enabled = getenv('app.enableMigrationsController');
		if (!$enabled || $enabled == 'false')
		{
			return 'Database migrations not enabled!';
		}
		
		// Run databse updates.
		$migrate = \Config\Services::migrations();
    try
    {
      $migrate->latest();
    }
    catch (\Throwable $e)
    {
      // Do something with the error here...
    }

		// Show done!
		return 'done!';
	}

	//--------------------------------------------------------------------

}
