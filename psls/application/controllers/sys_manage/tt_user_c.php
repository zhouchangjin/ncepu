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
		$count=$this ->tt_user_m->getCount($param);	
		$this->data['list'] = $this ->tt_user_m->get_list($param,'*',$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}

	public function detail($id){
		$param=array();
		$param['id']=$id;
		$this->data['obj']=$this->tt_user_m->getOne($param);
		$this->ci_smarty->view('sys_manage/tt_user_detail_v',$this->data);
	}

	public function index()
	{
		$this->data['dictionary']=$this ->tt_user_m->getDictionary();
		$this->ci_smarty->view('sys_manage/tt_user_v',$this->data);
	}
	
	public function readonly(){
		$this->data['dictionary']=$this ->tt_user_m->getDictionary();
		$this->ci_smarty->view('sys_manage/readonly_tt_user_v',$this->data);
	}
	
	public function query(){

	}
    public function add(){
    	$data=array();
$data["account"]=$this->input->post("account");
$data["password"]=$this->input->post("password");
$data["name"]=$this->input->post("name");
$data["role_id"]=$this->input->post("role_id");
$data["department_info_id"]=$this->input->post("department_info_id");
$data["gender"]=$this->input->post("gender");
$data["birthdate"]=$this->input->post("birthdate");
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
	
	public function update($id){
		$data=array();
$data["account"]=$this->input->post("account");
$data["password"]=$this->input->post("password");
$data["name"]=$this->input->post("name");
$data["role_id"]=$this->input->post("role_id");
$data["department_info_id"]=$this->input->post("department_info_id");
$data["gender"]=$this->input->post("gender");
$data["birthdate"]=$this->input->post("birthdate");
$data["status"]=$this->input->post("status");
$data["contactnumber"]=$this->input->post("contactnumber");
    	$this ->tt_user_m->update($data,$id);
	}
	    	 
}

