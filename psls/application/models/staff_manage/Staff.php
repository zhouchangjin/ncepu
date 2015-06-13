<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ('/TemporaryRight.php');
class Staff extends CI_Model{
	private  $id;
	private  $name;
	private  $password;
	private  $status;
	private  $gender;
	private  $birthdate;
	private  $department;
	private  $role;
	private  $group;
	/**
	 * @return the $id
	 */
	
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @return the $state
	 */
	public function getStatus() {
		return $this->state;
	}

	/**
	 * @return the $gender
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @return the $birthdate
	 */
	public function getBirthdate() {
		return $this->birthdate;
	}

	/**
	 * @return the $department
	 */
	public function getDepartment() {
		return $this->department;
	}

	/**
	 * @return the $role
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param field_type $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @param field_type $state
	 */
	public function setStatus($state) {
		$this->state = $state;
	}

	/**
	 * @param field_type $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * @param field_type $birthdate
	 */
	public function setBirthdate($birthdate) {
		$this->birthdate = $birthdate;
	}

	/**
	 * @param field_type $department
	 */
	public function setDepartment($department) {
		$this->department = $department;
	}

	/**
	 * @param field_type $role
	 */
	public function setRole($role) {
		$this->role = $role;
	}
	
	
	/**
	 * @return the $group
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @param field_type $group
	 */
	public function setGroup($group) {
		$this->group = $group;
	}

	public function roleRightCheck($right){
		//角色权限检查
		$rightTem=$this->role->getRoleRight();
		$tag=false;
		for($i=0;$i<count($rightTem);$i++){
			if($right==$rightTem[$i]->getRightCode()){
				$tag=true;
				break;
			}
		}
		return $tag;
	}

	public function checkRight($right){
		//权限检查：包括临时权限检查和角色权限检查
		$tag = $this->roleRightCheck($right);
		if($tag==false){
			$temp=new TemporaryRight();
			$tag = $temp->temporaryRightCheck($right,$this->id);
		}
		return $tag;
	}
	
	public function roleRightConfer($right,$staffId){
		//将自身角色权限授予他人（right：指定权限，staff_id：指定人）
		if($this->roleRightCheck($right)){
			$temp=new TemporaryRight();
			if($temp->temporaryRightBuilt($right,$staffId))
			$message="授权成功";
			else $message="授权失败，请重新操作";
		}else{
			$message="您不具有该角色权限，无法完成授权操作";
		}
		echo $message;die;
		return $message;
	}
	
	public function temporaryRightRelease($right){
		$temp=new TemporaryRight();
		if($temp->temporaryRightRelease($right, $this->id))
			$message="临时权限成功释放";
		else
			$message="临时权限释放失败";
		//echo $message;die;
		return $message;
	}
	
}

?>