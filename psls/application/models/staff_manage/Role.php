<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role  extends MY_Model {
	private $roleId;
	private $roleName;
	private  $roleRight=array();
	
	
	function __construct(){
		parent::__construct();
		$this->table_name = 'role_info';
	}
	
	/**
	 * @return the $roleId
	 */
	public function getRoleId() {
		return $this->roleId;
	}

	/**
	 * @return the $roleName
	 */
	public function getRoleName() {
		return $this->roleName;
	}
	
	public function getRoleAlias(){
		return $this->roleAlias;
	}
	
	public function setRoleAlias($name){
		$this->roleAlias = $name;
	}

	/**
	 * @return the $roleRight
	 */
	public function getRoleRight() {
		return $this->roleRight;
	}

	/**
	 * @param field_type $roleId
	 */
	public function setRoleId($roleId) {
		$this->roleId = $roleId;
	}

	/**
	 * @param field_type $roleName
	 */
	public function setRoleName($roleName) {
		$this->roleName = $roleName;
	}

	/**
	 * @param field_type $roleRight
	 */
	public function setRoleRight() {
		$sql="select right_code,right_description from right_info natural join role_info ".
		  		"natural join role_to_right where role_id=".$this->roleId;
		$right =$this->getQuery($sql);
		for($i=0;$i<count($right);$i++){
			$this->roleRight[$i]=new Right();
			//$this->roleRight[$i]->setRightId($right[$i]['right_id']);
			$this->roleRight[$i]->setRightCode($right[$i]['right_code']);
			$this->roleRight[$i]->setRightDescription($right[$i]['right_description']);
		}
		
		//var_dump($this->roleRight);die;
	}

	
}

?>