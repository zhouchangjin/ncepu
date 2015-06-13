<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model {

	function __construct(){
		parent::__construct();	
		$this->table_name = 'tt_user';
	}
	public function getUser($param)
	{
		return $this->getOne($param);
	}
	public function addUser($param)
	{
		return $this->insert($param);
	}
	public function delUser($id)
	{
		return $this->delete($id);
	}
	public function updateUser($param,$id)
	{
		return $this->update($param,$id);
	}
	public function get_user_list($params = array(), $fields = '*', $start = 0, $perpage = 0, $order 	= '', $sort = '', $like = array(), $group = array())
	{
		return $this->getList($params , $fields, $start, $perpage, $order, $sort, $like , $group);
	}
	public function get_user_count($params,$like)
	{
		return $this->getCount($params,$like);
	}
}