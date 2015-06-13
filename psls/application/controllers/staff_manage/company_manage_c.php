<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once ('/Staff.php');
class Staff_manage_c extends MY_Controller {
	private $user;
	public function __construct()
	{
	    parent::__construct();
	    $GLOBALS['department_name'] 	= array(0=>'财务资产部',3 => '筹备处',2 => '综合管理部',4 => '甸顶山风电场',5 => '计划工程部',6=>'安全生产部',10=>'公司领导');
	     
	    $this->load->helper('url');	    
	    session_start();	
	    header("Cache-control: private");
	    $this->load->model('staff_manage/Staff');
	    $this->load->model('sys_manage/user_model');
	    $this->load->model('staff_manage/Right');
	    $this->load->model('staff_manage/Role');
	    $this->load->model('staff_manage/roleToRight_m');
	    $this->user     =     unserialize($_SESSION['user']);
	    $this->data['user']=$this->user;
	    $this->data['message']="";  
	    
	 }
	 public function getDepartmentAjax(){
		$departmentId=$_POST['departmentId'];
		echo  $departmentId;
	 	$sql    = "select * from department_info";
	 	$result = $this->roleToRight_m->getQuery($sql);
	 	var_dump(count($result));
	 	for($i=0;$i<count($result);$i++){
	 		//echo $i;
	 		if($departmentId ==$result[$i]['department_id'])
		 		echo "<option value='".$result[$i]['department_id']."' selected>".$result[$i]['department_name']
		 		."</option>";
	 		else 
	 			echo "<option value='".$result[$i]['department_id']."'>".$result[$i]['department_name']."</option>";
	 	}
	 }
	 public function getRoleAjax(){
	 	$departmentId=$_POST['departmentId'];
	 	$roleId      =$_POST['roleId'];
	 	//echo "<script>alert('".$roleId."');</script>";
	 	$sql    = "select * from role_info where department_id='".$departmentId."'";
	 	$result = $this->roleToRight_m->getQuery($sql);	
	 	
	 	
	 	for($i=0;$i<count($result);$i++){
	 		if($roleId ==$result[$i]['role_id'])
	 		 echo "<option value='".$result[$i]['role_id']."' selected>".$result[$i]['role_name']."</option>";
	 		else
	 		 echo "<option value='".$result[$i]['role_id']."'>".$result[$i]['role_name']."</option>";
	 	}
	 }
	  public function getadminRoleAjax(){
	 	$departmentId=$_POST['departmentId'];
	 	$roleId      =$_POST['roleId'];
	 	//echo "<script>alert('".$roleId."');</script>";
	 	$sql    = "select * from role_info ";
	 	$result = $this->roleToRight_m->getQuery($sql);	
	 	
	 	
	 	for($i=0;$i<count($result);$i++){
	 		if($roleId ==$result[$i]['role_id'])
	 		 echo "<option value='".$result[$i]['role_id']."' selected>".$result[$i]['role_name']."</option>";
	 		else
	 		 echo "<option value='".$result[$i]['role_id']."'>".$result[$i]['role_name']."</option>";
	 	}
	 }
	 public function getRole()
	{
	 	$departmentID =$_POST['departmentID'];
	 	$sql    = "select * from role_info where work_type='".$work_type."'";
	 	$result = $this->critical_activity_model->getQuery($sql);	
	 	
	 	
	 	for($i=0;$i<count($result);$i++){
	 		//if($roleId ==$result[$i]['role_id'])
	 		 echo "<option value='".$result[$i]['id']."' selected>".$result[$i]['work_way']."</option>";
	 		//else
	 		// echo "<option value='".$result[$i]['role_id']."'>".$result[$i]['role_name']."</option>";
	 	}
	 }
	 public function addUser(){
	 	$this->ci_smarty->view('staff_manage/staff_add_v',$this->data);
	 }
	 public function administrator(){
	 	$this->ci_smarty->view('staff_manage/administrator_v',$this->data);
	 }
	 //璇ュ嚱鏁帮紝鐢ㄤ簬鏂板鍛樺伐
	 public function saveStaff(){
	 	$param['name']=$this->input->post('name');
	 	$param['account']=$this->input->post('name');
	 	$param['gender']=$this->input->post('gender');
	 	$param['department_id']=$this->input->post('department');
	 	$param['role_id']=$this->input->post('role');
	 	//$param['work_group']=$this->input->post('work_group');
	 	$param['birthdate']=$this->input->post('birth_date');
	 	$param['status']=$this->input->post('staff_status');
	 	//$param['educational_background']=$this->input->post('educational_background');
	 	//echo $this->input->get('id');die;
	 	//鏂板鍛樺伐
 		$param['password']='a';
 		$result=$this->user_model->addUser($param);
 		if($result)
 		$this->data['message']="新员工注册成功初始密码为a";
 		else $this->data['message']="鍛樺伐淇℃伅娉ㄥ唽澶辫触锛岃閲嶆柊娉ㄥ唽";
	 	
	 	$this->index();
	 }
	 public function delUser($id,$department_id){
	 	$name= $this->input->get('name');
	 	$result=$this->user_model->delUser($id);
	 	if($result)
	 			$this->data['message']=$name."已经成功删除";
	 		else $this->data['message']="删除失败";
	 		$param['department_id']=$department_id;
	 	$this->index();
	 }
	 public function updateUser($id){
	 	$sql="select * from tt_user NATURAL JOIN department_info NATURAL JOIN role_info where id=".$id;
	 	$user   = $this->user_model->getQuery($sql);
	 	$this->data['user']  =$user[0];
	 	$this->ci_smarty->view('staff_manage/staff_update_v',$this->data);
	 }
	 
	 //鍛樺伐淇℃伅鏇存柊
	 public function updateExc($id,$department_id){ 	
	    
	 	$param['name']=$this->input->post('name');
	 	$param['account']=$this->input->post('name');
	 	$param['gender']=$this->input->post('gender');
	 	$param['department_id']=$this->input->post('department');
	 	$param['role_id']=$this->input->post('role');
	 		$param['pow_id']=$this->input->post('powid');
	 	//$param['work_group']=$this->input->post('work_group');
	 	$param['birthdate']=$this->input->post('birth_date');
	 	//$param['status']=$this->input->post('staff_status');
	 	//$param['educational_background']=$this->input->post('educational_background');
	 	
	 	$result=$this->user_model->updateUser($param,$id);
	 	if($result)
	 		$this->data['message']="员工信息更新成功";
	 	else $this->data['message']="员工信息更新失败";
	 	$param['department_id']=$department_id;
	 	$this->index();
	 }
	 public function staffStruct($type){
	 	//echo $type;
	 	$param = array();
	 	$this->staffWorkStatusDivision();
	 	$this->staffDepartmentDivision();
	 	$this->staffEducationDivision();
	 	$this->queryStaff2();
	 	$config['base_url']   = '/psls/staff_manage/Staff_manage_c/staffStruct'.$type;
	 	//var_dump($this->data);
	 	
	 	$this->ci_smarty->view('staff_manage/staffStructure_v',$this->data);
	 }
	 public function staffWorkStatusDivision(){
	 	//echo "hello";
	 	global $department;
	 	$condition="";
	 if(isset($_GET['department'])){
	 		if($_GET['department']){
	 			$condition=$condition." and department_id=".$_GET['department'];
	 			$this->data['dep']=$department[$_GET['department']];
	 		}
	 	}
	 if(isset($_GET['education'])){
	 		if($_GET['education']){
	 			$condition=$condition." and educational_background='".$_GET['education']."'";
	 			$this->data['edu']=$_GET['education'];
	 		}
	 	}
	 	//echo $condition."hello";
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where staff_status='助理级' ".$condition;
	 	
	 	$this->data['zlj']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where staff_status='中级' ".$condition;
	 	
	 	$this->data['zj']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where staff_status='高级' ".$condition;
	 	$this->data['gj']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where staff_status='正高级' ".$condition;
	 	$this->data['zgj']   = $this->user_model->getQuery($sql);
	 	
	 	
	 }
	 public function staffEducationDivision(){
	 	global $department_name;
	 	$condition="";
	 	if(isset($_GET['department'])){
	 		if($_GET['department']){
	 			$condition=$condition." and department_id=".$_GET['department'];
	 			$this->data['dep']=$department_name[$_GET['department']];
	 			//echo $this->data['dep'];
	 		}
	 	}
	 if(isset($_GET['status'])){
	 		if($_GET['status']){
	 			$condition=$condition." and staff_status='".$_GET['status']."'";
	 			$this->data['sta']=$_GET['status'];
	 		}
	 	}
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where educational_background='高中及以下' ".$condition;
	 	$this->data['gzjyx']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where educational_background='中专' ".$condition;
	 	$this->data['zz']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where educational_background='大专' ".$condition;
	 	$this->data['dz']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where educational_background='本科' ".$condition;
	 	$this->data['bk']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where educational_background='硕士' ".$condition;
	 	$this->data['ss']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where educational_background='博士' ".$condition;
	 	$this->data['bs']   = $this->user_model->getQuery($sql);
	 }
	 public function staffDepartmentDivision(){
	 	$condition="";
	 	if(isset($_GET['education'])){
	 		if($_GET['education']){
	 			$condition=$condition." and educational_background='".$_GET['education']."'";
	 			$this->data['edu']=$_GET['education'];
	 		}
	 	}
	 	if(isset($_GET['status'])){
	 		if($_GET['status']){
	 			$condition=$condition." and staff_status='".$_GET['status']."'";
	 			$this->data['sta']=$_GET['status'];
	 		}
	 	}
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where
	 			 department_id=0".$condition;
	 	$this->data['cwzc']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where
	 			 department_id=2".$condition;
	 	$this->data['zhgl']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where
	 			 department_id=4".$condition;
	 	$this->data['ddsfc']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where
	 			 department_id=5".$condition;
	 	$this->data['jhgc']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where
	 			 department_id=6".$condition;
	 	$this->data['aqsc']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where
	 			 department_id=10".$condition;
	 	$this->data['gs']   = $this->user_model->getQuery($sql);
	 	$sql="select count(*) as num from tt_user NATURAL JOIN department_info where
	 			 department_id=3".$condition;
	 	$this->data['cb']   = $this->user_model->getQuery($sql);
	 	//var_dump($this->data);
	 }
    public function index(){
    	
    	$this->queryStaff();
    	
    	$this->ci_smarty->view('staff_manage/staff_add_v',$this->data);
    }
    public function queryStaff(){
    	//$param 鏁扮粍 浼犲埌鏁版嵁搴撶殑 鏌ヨ鍙橀噺
    	$param                   = array();
    	// echo $this->input->post("department");
    	$condition      ="";
    	$department ="";
    	if($this->input->post("department")){
    		$department =$this->input->post("department");
    		$condition=" where department_id=".$this->input->post("department");
    		$param['department_id']=$department;
    	}else if($this->input->get("department")){
    		$condition=" where department_id=".$this->input->get("department");
    		$department =$this->input->get("department");
    		$param['department_id']=$department;
    	}

    	
    	
    	$page_start 				= $this->input->get('per_page');
    	// $per_page 姣忛〉鏄剧ず鍒楄〃鏁扮洰
    	$per_page 					= 13;
    	if (!$page_start) {
    		$page_start = 0;
    	}
    
    	$sql="select * from tt_user NATURAL JOIN department_info NATURAL JOIN role_info".$condition." ORDER BY role_info.status desc limit "
    			.$page_start.",".$per_page;
    	//echo $sql;//die;
    	$this->data['userList']   = $this->user_model->getQuery($sql);
    	foreach ($this->data['userList'] as $key => $value){
    		$this->data['userList'][$key]['age']  = date("Y-m-d")-substr($this->data['userList'][$key]['birthdate'], 0,10);
    		 
    	}
    	//$this->data['userList']['age']  = cdate("Y-m-d")-
    	//var_dump($this->data['userList'][0]);die;
    	// CI 妗嗘灦閲岀殑鍒嗛〉鏈哄埗
    	// base_url 浣滀负鍒嗛〉 閾炬帴  鍩哄湴鍧�
    	if($department){
    		$this->data['totle']  =$this->user_model->get_user_count($param,$like=array());
    		$this->data['department']=$this->user_model->getQuery("select department_name,department_id from department_info"
    				." where department_id=".$department);
    	}
    
    
    	$this->load->library('pagination');
    	$config['base_url']   = '/psls/staff_manage/Staff_manage_c/?page=true&department='.$department;
    
    	$config['total_rows'] = $this->user_model->get_user_count($param,$like=array());
    	//echo $config['total_rows'];die;
    	$config['per_page']   = $per_page ;
    	$config['page_query_string'] = TRUE;
    	$this->pagination->initialize($config);
    	$this->data['page_link'] = $this->pagination->create_links();
    }
    public function queryStaff2(){
    	//$param 鏁扮粍 浼犲埌鏁版嵁搴撶殑 鏌ヨ鍙橀噺
    	$param                   = array();
    	$condition  = " where department_id";
    	$condition2 ="";
    	if(isset($_GET['type'])){
    		$condition2="&type=".$_GET['type'];
    		$this->data['type']=$_GET['type'];
    	}
    		
    	if(isset($_GET['department'])){
    		if($_GET['department']){
    			$condition=$condition." and department_id=".$_GET['department'];
    			$condition2 =$condition2."&department=".$_GET['department'];
    			$param['department_id']=$_GET['department'];
    		}
    	}
    	if(isset($_GET['status'])){
    		if($_GET['status']){
    			$condition=$condition." and staff_status='".$_GET['status']."'";
    			$condition2 =$condition2."&status=".$_GET['status'];
    			$param['staff_status']=$_GET['status'];
    		}
    	}
    	if(isset($_GET['education'])){
    		if($_GET['education']){
    			$condition=$condition." and educational_background='".$_GET['education']."'";
    			$condition2 =$condition2."&education=".$_GET['education'];
    			$param['educational_background']=$_GET['education'];
    		}
    	}
    	/*$department ="";
    	if($this->input->post("department")){
    		$department =$this->input->post("department");
    		$condition=" where department_id=".$this->input->post("department");
    		$param['department_id']=$department;
    	}else if($this->input->get("department")){
    		$condition=" where department_id=".$this->input->get("department");
    		$department =$this->input->get("department");
    		$param['department_id']=$department;    	
    	}*/
    	
    	///echo $department;
    	//$page_start 璧峰鏌ヨ浣嶇疆
    	$page_start 				= $this->input->get('per_page');
    	// $per_page 姣忛〉鏄剧ず鍒楄〃鏁扮洰
    	$per_page 					= 13;
    	if (!$page_start) {
    		$page_start = 0;
    	}
    	 
    	$sql="select * from tt_user NATURAL JOIN department_info NATURAL JOIN role_info".$condition." ORDER BY role_info.status desc limit "
    			.$page_start.",".$per_page;
    	//echo $sql;//die;
    	$this->data['userList']   = $this->user_model->getQuery($sql);
    	foreach ($this->data['userList'] as $key => $value){
    		$this->data['userList'][$key]['age']  = date("Y-m-d")-substr($this->data['userList'][$key]['birthdate'], 0,10);
    	
    	}  	 
    	 
    	$this->load->library('pagination');
    	$config['base_url']   = '/psls/staff_manage/Staff_manage_c/staffStruct/0/?page=true'.$condition2;
    	 
    	$config['total_rows'] = $this->user_model->get_user_count($param,$like=array());
    	//echo $config['total_rows'];die;
    	$config['per_page']   = $per_page ;
    	$config['page_query_string'] = TRUE;
    	$this->pagination->initialize($config);
    	$this->data['page_link'] = $this->pagination->create_links();
    }

    public function deleteUser()
	{
		//echo "string";
		$way               = $this->input->post('del');
		//echo "11111";
		//print_r($way);die;
		if (!empty($way))
		{
			$i=0;
			while (@$way[$i])
			{
				$this->user_model->delUser(@$way[$i]);
				$i++;
			}
			$msg = "删除成功！";
		}
		else
			$msg = "没有选中记录";
		$this->data['msg']= $msg;
		$this->index();
	}
}