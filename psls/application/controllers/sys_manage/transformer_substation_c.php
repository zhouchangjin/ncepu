<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Transformer_substation_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('sys_manage/transformer_substation_m');
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
		$count=$this ->transformer_substation_m->getCount($param);	
		$this->data['list'] = $this ->transformer_substation_m->get_list($param,'*',$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}

	public function detail($id){
		$param=array();
		$param['id']=$id;
		$this->data['obj']=$this->transformer_substation_m->getOne($param);
		$this->ci_smarty->view('sys_manage/transformer_substation_detail_v',$this->data);
	}

	public function index()
	{
		$this->ci_smarty->view('sys_manage/transformer_substation_v',$this->data);
	}
	
	public function readonly(){
		$this->ci_smarty->view('sys_manage/readonly_transformer_substation_v',$this->data);
	}
	
	public function query(){

	}
    public function add(){
    	$data=array();
$data["name"]=$this->input->post("name");
$data["description"]=$this->input->post("description");
$data["address"]=$this->input->post("address");
$data["department_info_id"]=$this->input->post("department_info_id");
    	$this ->transformer_substation_m->add($data);

	}
	
	public function addPage(){
		$this->ci_smarty->view('sys_manage/transformer_substation_add_v',$this->data);
	}
	
	public function editPage($id){
	    $this->data['id']=$id;
	    $param=array();
	    $param['id']=$id;
	    $data=$this ->transformer_substation_m->get_one($param);
	    $this->data['obj']=$data;
		$this->ci_smarty->view('sys_manage/transformer_substation_edit_v',$this->data);
	}
	
	public function delete($id){
		 $this ->transformer_substation_m->delete($id);
	}
	
	public function update($id){
		$data=array();
$data["name"]=$this->input->post("name");
$data["description"]=$this->input->post("description");
$data["address"]=$this->input->post("address");
$data["department_info_id"]=$this->input->post("department_info_id");
    	$this ->transformer_substation_m->update($data,$id);
	}
	    	 
}

