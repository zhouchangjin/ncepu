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

	}

	
}

?>