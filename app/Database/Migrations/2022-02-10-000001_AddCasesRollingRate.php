<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCasesRollingRate extends Migration
{
	public function up()
	{
		$fields = [
			'rolling_rate' => [
				'type' => 'DECIMAL',
				'constraint' => '8,1',
				'after' => 'rate',
			],
		];
		$this->forge->addColumn('cases', $fields);
	}

	public function down()
	{
		$this->forge->dropColumn('cases', 'slug'); // to drop one single column
	}
}
