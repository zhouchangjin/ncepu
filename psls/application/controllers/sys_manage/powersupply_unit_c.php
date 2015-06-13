<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Powersupply_unit_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('sys_manage/powersupply_unit_m');
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
		$company_id=$this->input->post("company_id");
		if($company_id){
			$param['department_info_id']=$company_id;
		}
		$fields="department_info.department_name,id,name,powersupply_unit.address,powersupply_unit.description,powersupply_unit.cdate";
		$jointcondition=array();
		$condition1=array();
		$condition1['table']="department_info";
		$condition1['condition']="department_info.department_id=department_info_id";
		$condition1['type']='left';
		array_push($jointcondition, $condition1);
		$count=$this ->powersupply_unit_m->get_list_count2($jointcondition,$param);	
		$this->data['list'] = $this ->powersupply_unit_m->get_list2($jointcondition,$param,$fields,$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}

	public function detail($id){
		$param=array();
		$param['id']=$id;
		$this->data['obj']=$this->powersupply_unit_m->getOne($param);
		$this->ci_smarty->view('sys_manage/powersupply_unit_detail_v',$this->data);
	}

	public function index()
	{
		$this->ci_smarty->view('sys_manage/powersupply_unit_v',$this->data);
	}
	public function filterPage($company_id){
		$this->data['company_id']=$company_id;
		$this->ci_smarty->view('sys_manage/company_powersupply_unit_v',$this->data);
	}
	public function query(){

	}
    public function add(){
    	$data=array();
		$data["name"]=$this->input->post("name");
		$data["address"]=$this->input->post("address");
		$data["description"]=$this->input->post("description");
		$data["department_info_id"]=$this->input->post("department_info_id");
    	$this ->powersupply_unit_m->add($data);

	}
	
	public function addcompanypage($company_id){
		$this->data['department_info_id']=$company_id;
		$this->ci_smarty->view('sys_manage/powersupply_unit_add2_v',$this->data);
	}
	public function addPage(){
		$this->ci_smarty->view('sys_manage/powersupply_unit_add_v',$this->data);
	}
	
	public function editPage($id){
	    $this->data['id']=$id;
	    $param=array();
	    $param['id']=$id;
	    $data=$this ->powersupply_unit_m->get_one($param);
	    $this->data['obj']=$data;
		$this->ci_smarty->view('sys_manage/powersupply_unit_edit_v',$this->data);
	}
	
	public function delete($id){
		 $this ->powersupply_unit_m->delete($id);
	}
	
	public function update($id){
		$data=array();
		$data["name"]=$this->input->post("name");
		$data["address"]=$this->input->post("address");
		$data["description"]=$this->input->post("description");
		$data["department_info_id"]=$this->input->post("department_info_id");
    	$this ->powersupply_unit_m->update($data,$id);
	}
	
	public function viewuser($id){
		$this->data['powersupply_id']=$id;
		$this->ci_smarty->view('sys_manage/powersupply_user_v',$this->data);
	}
	
	public function assignuserPage($id){
		$this->data['powersupply_id']=$id;
		$this->ci_smarty->view('sys_manage/powersupply_assign_user_v',$this->data);
	}
	
	public function assignUser(){
		$ids=$this->input->post('ids');
		$powersupply_id=$this->input->post('id');

		$idList=explode(";", $ids);
		$data=array();
		$data['powerunit_id']=$powersupply_id;
		$data['dept_id']='7';
		for($i=0;$i<sizeof($idList);$i++){
			$id=$idList[$i];
			if($id!=""){
				$this->tt_user_m->update($data,$id);
			}
		}
	}
	
	public function usergrid($id){
		$param = array();
		$like = array();
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$page_start = $rows*($page-1);
		$per_page = $rows;
		$param['b.id']=NULL;
		$fields="tt_user.name,tt_user.id,department_name";
		$jointcondition=array();
		$condition1=array();
		$condition1['table']="department_info";
		$condition1['condition']="tt_user.department_id=department_info.department_id";
		$condition1['type']='left';
		$condition2=array();
		$condition2['table']="(SELECT * FROM tt_user WHERE powerunit_id=".$id.") b";
		$condition2['condition']="tt_user.id=b.id";
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
	    	 
}

