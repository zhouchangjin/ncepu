<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once ('application/core/MY_Model.php');
class StaffControl extends \MY_Model {
	//人员管理，用于人员的增加、删除和修改
	function __construct(){
		parent::__construct();
		$this->table_name = 'tt_user';
	}
	
	public function staffAdd($data){
		return $bool = $this->db->insert_batch($this->table_name, $data);
	}
	
	public function staffDel($user_id){
		
	}
	
	public function staffUpdate($data){
		
	}
	
	public function getStaffList($params = array(), $fields = '*', $start = 0, $perpage = 0, 
							$order 	= '', $sort = '', $like = array(), $group = array()){
		if ($perpage) {
			$this->db->limit($perpage, $start);
		}
		if ($order && $sort) {
			$this->db->order_by($order, $sort);
		}
		if (!empty($group) && count($group) > 0) {
			$this->db->group_by($group);
		}
		$q = $this->db->select($fields, false)->where($params)->or_like($like)->get($this->table_name);
		
		return $list = $q->result_array();
		
	}
}

?>