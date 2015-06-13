<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dept_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('sys_manage/dept_m');
	    $this->load->model('sys_manage/dept_role_m');
	    $this->load->model('sys_manage/role_info_m');
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
	
	public function deptrolegrid($id){
		$param = array();
		$like = array();
		$param['dept.id']=$id;
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$page_start = $rows*($page-1);
		$per_page = $rows;
		$fields="dept_role.id as rel_id,dept_role.dept_id,dept_role.role_id,role_info.role_name";
		$jointcondition=array();
		$condition1=array();
		$condition1['table']="dept";
		$condition1['condition']="dept.id=dept_role.dept_id";
		$condition1['type']='left';
		$condition2=array();
		$condition2['table']="role_info";
		$condition2['condition']="role_info.role_id=dept_role.role_id";
		$condition2['type']='left';
		array_push($jointcondition, $condition1);
		array_push($jointcondition, $condition2);
		$count=$this ->dept_role_m->get_list_count2($jointcondition,$param);
		$this->data['list'] = $this ->dept_role_m->get_list2($jointcondition,$param,$fields,$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
		echo json_encode($tGrid);
	}

	public function grid(){
		$param = array();
		$like = array();
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$page_start = $rows*($page-1);
		$per_page = $rows;
		$count=$this ->dept_m->getCount($param);	
		$this->data['list'] = $this ->dept_m->get_list($param,'*',$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}
	
	public function addRolePage($id){
		$this->data['dept_id']=$id;
		$this->ci_smarty->view('sys_manage/dept_addrole_v',$this->data);
	}
	
	public function assignRole(){
		$ids=$this->input->post('ids');
		$dept_id=$this->input->post('dept_id');
		$idList=explode(";", $ids);
		

		for($i=0;$i<sizeof($idList);$i++){
			$id=$idList[$i];
			$data=array();
			if($id!=""){
				$data['dept_id']=$dept_id;
				$data['role_id']=$id;
				$this->dept_role_m->add($data);
			}
			
		}
	}
	
	public function usergrid($id){
		$param = array();
		$like = array();
		$param['b.role_id']=NULL;
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$page_start = $rows*($page-1);
		$per_page = $rows;
		$fields="role_info.role_alias,role_info.role_id AS role_id,b.role_id AS rel_id,role_name";
		$jointcondition=array();
		$condition1=array();
		$condition1['table']="(SELECT dept_role.role_id FROM dept_role,role_info WHERE dept_role.role_id=role_info.role_id AND dept_role.dept_id=".$id.") b";
		$condition1['condition']="b.role_id=role_info.role_id";
		$condition1['type']='left';
		array_push($jointcondition, $condition1);
		$count=$this ->role_info_m->get_list_count2($jointcondition,$param);
		$this->data['list'] = $this ->role_info_m->get_list2($jointcondition,$param,$fields,$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
		echo json_encode($tGrid);
	}

	public function detail($id)
	{
		$param=array();
		$param['id']=$id;
		$this->data['obj']=$this->dept_m->get_one($param);
		$this->ci_smarty->view('sys_manage/dept_detail_v',$this->data);
	}

	public function index()
	{
		$this->ci_smarty->view('sys_manage/dept_v',$this->data);
	}
	public function query(){

	}
    public function add(){
    	$data=array();
$data["name"]=$this->input->post("name");
$data["description"]=$this->input->post("description");
    	$this ->dept_m->add($data);

	}
	
	public function addPage(){
		$this->ci_smarty->view('sys_manage/dept_add_v',$this->data);
	}
	
	public function rolePage($id){
		
		$param=array();
		$param['id']=$id;
		$data=$this ->dept_m->get_one($param);
		$this->data['obj']=$data;
		$this->ci_smarty->view('sys_manage/dept_role_v',$this->data);
	}
	
	public function editPage($id){
	    $this->data['id']=$id;
	    $param=array();
	    $param['id']=$id;
	    $data=$this ->dept_m->get_one($param);
	    $this->data['obj']=$data;
		$this->ci_smarty->view('sys_manage/dept_edit_v',$this->data);
	}
	
	public function delete($id){
		 $this ->dept_m->delete($id);
	}
	
	public function update($id){
		$data=array();
$data["name"]=$this->input->post("name");
$data["description"]=$this->input->post("description");
    	$this ->dept_m->update($data,$id);
	}
	    	 
}

