<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once ('/Staff.php');
class temporaryRightConfer_c extends MY_Controller {
	private $user;
	public function __construct()
	{
	    parent::__construct();
	    
	    $this->load->helper('url');
	    session_start();
	    header("Cache-control: private");
	    $this->load->model('staff_manage/TemporaryRight_m');
	    $this->load->model('staff_manage/Staff');
	    $this->load->model('staff_manage/Department');
	    $this->load->model('staff_manage/Right');
	    $this->load->model('staff_manage/Role');	    
	    $this->user     =     unserialize($_SESSION['user']);
	    $this->message="";  
	 }
	 public function getPeopleAjax(){
	 	//本函数用于缺陷处理审核阶段相关人员的信息获取
	 	//获取缺陷所处于的审核阶段
	 	$status  =$this->input->post("status");
	 	//确定前台要求传输的数据类型
	 	$data_type=$this->input->post("type");
	 	//echo $status;
	 	$user   =  $this->user;
	 	if($this->user->getGroup()!=0)
	 		$str=" and work_group=".$this->user->getGroup();
	 	else
	 		$str=" ";
	 	
	 	$sql    = "select * from tt_user where id!=".$user->getId()." and status<=".$user->getStatus()
	 	." and department_id=".$user->getDepartment()->getDepartmentId().$str;
	 	 
	 	//var_dump($this->user);die;
	 	//查找本部门、本值，职位低于或者等于当前用户的人员
	 	$staffInLowerStatus  = $this->TemporaryRight_m->getQuery($sql);
	 	 	
	 	//var_dump($staffInLowerStatus[0]);
	 	
	 	if($data_type=="single"){
	 		for($i=0;$i<count($staffInLowerStatus);$i=$i+1){
	 			echo "<option value='".$staffInLowerStatus[$i]['id']."'>"
	 					.$staffInLowerStatus[$i]['name']."</option>";
	 		}
	 	}else if($data_type=="multiple"){
	 		for($i=0;$i<count($staffInLowerStatus);$i=$i+1){
	 			if(($i)%6==0) echo"<br/>";
	 			echo "<input type='checkbox' name='operators[]' value='"
	 					.$staffInLowerStatus[$i]['name']."'>".$staffInLowerStatus[$i]['name']
	 						."</input>&nbsp&nbsp&nbsp&nbsp";
	 		}
	 	}
	 	 
	 	
	 }
	 public function index(){
	 	//用于将当前用户的角色权限授予低于或者等于当前用户的人员
	 	$user   =  $this->user;
	 	//判断session是否过期，过期则转到登录页面
	 	if(empty($user)) redirect('login/index');
	 	
	 	if($this->user->getGroup()!=0)
	 		$str=" and work_group=".$this->user->getGroup();
	 	else 
	 		$str=" ";
	 		 	
	 	$sql    = "select * from tt_user where id!=".$user->getId()." and staff_status<=".$user->getStatus()
	 	." and department_id=".$user->getDepartment()->getDepartmentId().$str;
	 	
	 	//var_dump($this->user);die;
	 	//查找本部门、本值，职位低于或者等于当前用户的人员
	 	$staffInLowerStatus  = $this->TemporaryRight_m->getQuery($sql);
	 	
	 	//获取当前用户的角色权限
	 	$rightList  =  $user->getRole()->getRoleRight();
	 	//获取当前用户的角色权限
	 	for($i=0;$i<count($rightList);$i=$i+1){
	 		$roleRight[$i]['right_code'] = $rightList[$i]->getRightCode();
	 		$roleRight[$i]['right_description'] = $rightList[$i]->getRightDescription();	 		
	 	}
	 	
	 	$this->data['lowerStaff']    =  $staffInLowerStatus;
	 	$this->data['selfRoleRight'] =   $roleRight; 
	 	$this->data['message']       =  $this->message;
	 	
	 	$this->ci_smarty->view("staff_manage/temporaryRightConfer_v",$this->data);
	 }
	public function rightConfer(){
		$reciever      =  $this->input->post('rightReciever')?$this->input->post('rightReciever'):$this->input->get('rightReciever');
		$right         =  $this->input->post('right')?$this->input->post('right'):$this->input->get('right');
		if(empty($reciever)){
			$this->message = "授权失败，请选择接收权限的人员";
			$this->index();
			die;
		}
		if(empty($right )){
			$this->message = "授权失败，请选择您要授出的权限";
			$this->index();
			die;
		} 
		$param['id']   =  $reciever;	
		foreach ($right as $key => $value){
			$param['right_code']    =  $value;
			$result=$this->TemporaryRight_m->addItem($param);
		}
		
		//echo $result;die;
		if($result==0){
			$this->message="授权操作成功";			
		}
		$this->index();
	}
}