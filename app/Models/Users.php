<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
	public function getUser($username = false, $userID = false)
	{
		if ($username) {
			return $this->db->table('users')
				->select('*,users.id AS userID,user_role.id AS role_id')
				->join('user_role', 'users.role = user_role.id')
				->where(['username' => $username])
				->get()->getRowArray();
		} elseif ($userID) {
			return $this->db->table('users')
				->select('*,users.id AS userID,user_role.id AS role_id')
				->join('user_role', 'users.role = user_role.id')
				->where(['users.id' => $userID])
				->get()->getRowArray();
		} else {
			return $this->db->table('users')
				->select('*,users.id AS userID,user_role.id AS role_id')
				->join('user_role', 'users.role = user_role.id')
				->get()->getResultArray();
		}
	}

	public function getAccessMenuCategory($role)
	{
		return $this->db->table('user_menu_category')
			->join('user_access', 'user_menu_category.id = user_access.menu_category_id')
			->where(['user_access.role_id' => $role])
			->get()->getResultArray();
	}
	public function getAccessMenu($role)
	{
		return $this->db->table('user_menu')
			->join('user_access', 'user_menu.id = user_access.menu_id')
			->where(['user_access.role_id' => $role])
			->get()->getResultArray();
	}
	public function getMenu()
	{
		return $this->db->table('user_menu')
			->get()->getResultArray();
	}
	public function getMenuCategory()
	{
		return $this->db->table('user_menu_category')
			->get()->getResultArray();
	}
	public function getUserRole($role = false)
	{
		if ($role) {
			return $this->db->table('user_role')
				->where(['id' => $role])
				->get()->getRowArray();
		}

		return $this->db->table('user_role')
			->get()->getResultArray();
	}

	public function createRole($dataRole)
	{
		return $this->db->table('user_role')->insert(['role_name' => $dataRole['inputRoleName']]);
	}
	public function deleteRole($role)
	{
		return $this->db->table('user_role')->delete(['id' => $role]);
	}
	public function createMenu($dataMenu)
	{
		return $this->db->table('user_menu')->insert([
			'menu_category'	=> $dataMenu['inputMenuCategory'],
			'title'			=> $dataMenu['inputMenuTitle'],
			'url' 			=> $dataMenu['inputMenuURL'],
			'icon' 			=> $dataMenu['inputMenuIcon'],
		]);
	}
	public function createMenuCategory($dataMenuCategory)
	{
		return $this->db->table('user_menu_category')->insert([
			'menu_category'		=> $dataMenuCategory['inputMenuCategory']
		]);
	}
	public function getMenuByUrl($menuUrl)
	{
		return $this->db->table('user_menu')
			->where(['url' => $menuUrl])
			->get()->getRowArray();
	}
	public function createUser($dataUser)
	{
		return $this->db->table('users')->insert([
			'fullname'		=> $dataUser['inputFullname'],
			'username' 		=> $dataUser['inputUsername'],
			'password' 		=> password_hash($dataUser['inputPassword'], PASSWORD_DEFAULT),
			'role' 			=> $dataUser['inputRole'],
			'created_at'    => date('Y-m-d h:i:s')
		]);
	}
	public function updateUser($dataUser)
	{
		if ($dataUser['inputPassword']) {
			$password = password_hash($dataUser['inputPassword'], PASSWORD_DEFAULT);
		} else {
			$user 		= $this->getUser(false,$dataUser['userID']);
			$password 	= $user['password'];
		}
		return $this->db->table('users')->update([
			'fullname'		=> $dataUser['inputFullname'],
			'username' 		=> $dataUser['inputUsername'],
			'password' 		=> $password,
			'role' 			=> $dataUser['inputRole'],
		], ['id' => $dataUser['userID']]);
	}
	public function deleteUser($userID)
	{
		return $this->db->table('users')->delete(['id' => $userID]);
	}
	public function checkUserAccess($dataAccess)
	{
		return  $this->db->table('user_access')
			->where([
				'role_id' => $dataAccess['roleID'],
				'menu_id' => $dataAccess['menuID']
			])
			->countAllResults();
	}
	public function checkUserMenuCategoryAccess($dataAccess)
	{
		return  $this->db->table('user_access')
			->where([
				'role_id' => $dataAccess['roleID'],
				'menu_category_id' => $dataAccess['menuCategoryID']
			])
			->countAllResults();
	}
	public function insertMenuPermission($dataAccess)
	{
		return $this->db->table('user_access')->insert(['role_id' => $dataAccess['roleID'], 'menu_id' => $dataAccess['menuID']]);
	}
	public function deleteMenuPermission($dataAccess)
	{
		return $this->db->table('user_access')->delete(['role_id' => $dataAccess['roleID'], 'menu_id' => $dataAccess['menuID']]);
	}
	public function insertMenuCategoryPermission($dataAccess)
	{
		return $this->db->table('user_access')->insert(['role_id' => $dataAccess['roleID'], 'menu_category_id' => $dataAccess['menuCategoryID']]);
	}
	public function deleteMenuCategoryPermission($dataAccess)
	{
		return $this->db->table('user_access')->delete(['role_id' => $dataAccess['roleID'], 'menu_category_id' => $dataAccess['menuCategoryID']]);
	}
}
