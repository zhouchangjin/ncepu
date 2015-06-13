<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tt_user_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('sys_manage/tt_user_m');
		session_start();
	    header("Cache-control: private");
	    $this->load->model('staff_manage/Staff');
	    $this->load->model('staff_manage/Department');
	    $this->load->model('staff_manage/Right');
	    $this->load->model('staff_manage/TemporaryRight');
	    $this->load->model('staff_manage/Role');
	    $this->user=unserialize($_SESSION['user']);
	    $this->data['user']      =   $this->user;

	}

	public function grid(){
		$param = array();
		$like = array();
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$page_start = $rows*($page-1);
		$per_page = $rows;
		if($this->input->post('company_id')){
			$param['tt_user.department_id']=$this->input->post('company_id');
		}
		if($this->input->post('role_id')){
			$param['tt_user.role_id']=$this->input->post('role_id');
		}
		if($this->input->post('dept_id')){
			$param['tt_user.dept_id']=$this->input->post('dept_id');
		}
		if($this->input->post('powerunit_id')){
			$param['tt_user.powerunit_id']=$this->input->post('powerunit_id');
		}
		
		$fields="tt_user.id,tt_user.account,tt_user.name,tt_user.role_id,tt_user.department_id,tt_user.gender"
				.",tt_user.birthdate,tt_user.contactnumber,role_info.role_name,department_info.department_name";
		$jointcondition=array();
		$condition1=array();
		$condition1['table']="role_info";
		$condition1['condition']="role_info.role_id=tt_user.role_id";
		$condition1['type']='left';
		$condition2=array();
		$condition2['table']="department_info";
		$condition2['condition']="department_info.department_id=tt_user.department_id";
		$condition2['type']='left';
		array_push($jointcondition, $condition1);
		array_push($jointcondition, $condition2);
		$count=$this ->tt_user_m->get_list_count2($jointcondition,$param);	
		$this->data['list'] = $this ->tt_user_m->get_list2($jointcondition,$param,$fields,$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}

	public function detail()
	{

	}

	public function index()
	{
		$this->ci_smarty->view('sys_manage/tt_user_v',$this->data);
	}
	public function query(){

	}
    public function add(){
    	$data=array();
$data["account"]=$this->input->post("account");
$data["password"]=$this->input->post("password");
$data["name"]=$this->input->post("name");
$data["role_id"]=$this->input->post("role_id");
$data["department_id"]=$this->input->post("department_id");
$data["gender"]=$this->input->post("gender");
$data["birthdate"]=$this->input->post("birthdate");
$data["edate"]=$this->input->post("edate");
$data["status"]=$this->input->post("status");
$data["contactnumber"]=$this->input->post("contactnumber");
    	$this ->tt_user_m->add($data);

	}
	
	public function addPage(){
		$this->ci_smarty->view('sys_manage/tt_user_add_v',$this->data);
	}
	
	public function editPage($id){
	    $this->data['id']=$id;
	    $param=array();
	    $param['id']=$id;
	    $data=$this ->tt_user_m->get_one($param);
	    $this->data['obj']=$data;
		$this->ci_smarty->view('sys_manage/tt_user_edit_v',$this->data);
	}
	
	public function delete($id){
		 $this ->tt_user_m->delete($id);
	}
	public function deassign(){
		$id=$this->input->post('id');
		$data['dept_id']=NULL;
		$this->tt_user_m->update($data,$id);
	}
	
	public function removeFromPSU(){
		$id=$this->input->post('id');
		$data['powerunit_id']=NULL;
		$this->tt_user_m->update($data,$id);
	}

	public function update($id){
		$data=array();
	$data["account"]=$this->input->post("account");
	$data["password"]=$this->input->post("password");
	$data["name"]=$this->input->post("name");
	$data["role_id"]=$this->input->post("role_id");
	$data["department_id"]=$this->input->post("department_id");
	$data["gender"]=$this->input->post("gender");
	$data["birthdate"]=$this->input->post("birthdate");
	$data["edate"]=$this->input->post("edate");
	$data["status"]=$this->input->post("status");
	$data["contactnumber"]=$this->input->post("contactnumber");
    	$this ->tt_user_m->update($data,$id);
	}
	    	 
}

