 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Department{
	private $departmentId;
	private $departmentName;
	private $deptId;
	private $deptName;
	private $powersupplyId;
	private $powersupplyName;
	
	public function getDeptId(){
		return $this->deptId;	
	}
	public function getPowersupplyId(){
		return $this->powersupplyId;
	}
	
	public function setDeptId($deptId){
		$this->deptId=$deptId;
	}
	
	public function setPowersupplyId($powersupplyId){
		$this->powersupplyId=$powersupplyId;
	}
	
	public function getDeptName(){
		return $this->deptName;
	}
	
	public function getPowersupplyName(){
		return $this->powersupplyName;
	}
	
	public function setDeptName($deptName){
		$this->deptName=$deptName;
	}
	
	public function setPowersupplyName($powersupplyName){
		$this->powersupplyName=$powersupplyName;
	}

	/**
	 * @return the $departmentName
	 */
	public function getDepartmentName() {
		return $this->departmentName;
	}



	/**
	 * @param field_type $departmentName
	 */
	public function setDepartmentName($departmentName) {
		$this->departmentName = $departmentName;
	}

	/**
	 * @return the $departmentId
	 */
	public function getDepartmentId() {
		return $this->departmentId;
	}


	/**
	 * @param field_type $departmentId
	 */
	public function setDepartmentId($departmentId) {
		$this->departmentId = $departmentId;
	}


}

?>