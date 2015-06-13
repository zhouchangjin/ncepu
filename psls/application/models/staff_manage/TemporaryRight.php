<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



require_once ('application/core/MY_Model.php');
class TemporaryRight extends \MY_Model {
	function __construct(){
		parent::__construct();
		$this->table_name = 'temporary_right';
	}
	
	public function temporaryRightCheck($tempRight,$userId){
		//查看指定用户是否具有指定的临时权限（$tempRight：权限，$userId：用户）
		$sql="select * from tt_user NATURAL JOIN temporary_right NATURAL JOIN right_info".
				" where id=".$userId." and right_code='".$tempRight."' ";
		$right =$this->getQuery($sql);
		if(!empty($right))
			return true;
		else 
			return false;
	}
	public function temporaryRightBuilt($tempRight,$userId){
		//为指定用户增加指定的临时权限（$tempRight：权限，$userId：用户）
		$data= array(
				'id'  => $userId,
				'right_code'=>$tempRight
				);
		$this->db->insert($this->table_name, $data);
		$id = $this->db->insert_id();
		
		if ($id) {
			return $id;
		} else {
			return $this->db->affected_rows();
		}
	}
	public function temporaryRightRelease($tempRight,$userId){
		//删除指定用户的某个临时权限（$tempRight：权限，$userId：用户）		
		$data = array(
				'right_code' => $tempRight,
				'id'       => $userId
				);
		$this->db->where($data, null, false);
		return $bool = $this->db->delete($this->table_name);
	}
}

?>