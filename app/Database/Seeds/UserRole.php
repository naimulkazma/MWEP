<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserRole extends Seeder
{
	public function run()
	{
		$datas = [
			[
			'id'    			=>  1,
			'role_name'    		=>  'Admin'
			],
			[
			'id'    			=>  2,
			'role_name'    		=>  'Coordinator'
			]
		];
		foreach($datas as $data){
		$this->db->table('user_role')->insert($data);
		}
	}
}
