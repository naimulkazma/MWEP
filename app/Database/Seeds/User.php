<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class User extends Seeder
{
	public function run()
	{
		$datas = [
			[
			'fullname' 		=> 'admin',
			'username'    	=> 'admin@gmail.com',
			'password'    	=>  password_hash('123456', PASSWORD_DEFAULT),
			'role'    		=>  1,
			'created_at'    =>  date('Y-m-d h:i:s')
			],
			[
			'fullname' 		=> 'coordinator',
			'username'    	=> 'user@gmail.com',
			'password'    	=>  password_hash('123456', PASSWORD_DEFAULT),
			'role'    		=>  2,
			'created_at'    =>  date('Y-m-d h:i:s')
			]
		];
		foreach($datas as $data){
			$this->db->table('users')->insert($data);
		}
		$this->call('UserAccess');
		$this->call('UserMenu');
		$this->call('UserMenuCategory');
		$this->call('UserRole');
	}
}
