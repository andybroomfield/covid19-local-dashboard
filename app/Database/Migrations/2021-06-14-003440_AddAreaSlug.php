<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAreaSlug extends Migration
{
	public function up()
	{
		$fields = [
			'slug' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
				'after' => 'name',
			],
		];
		$this->forge->addColumn('areas', $fields);
	}

	public function down()
	{
		$this->forge->dropColumn('areas', 'slug'); // to drop one single column
	}
}
