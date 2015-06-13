<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Factor_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('identification/factor_m');
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
		$count=$this ->factor_m->getCount($param);	
		$this->data['list'] = $this ->factor_m->get_list($param,'*',$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}

	public function detail($id){
		$param=array();
		$param['id']=$id;
		$this->data['obj']=$this->factor_m->getOne($param);
		$this->ci_smarty->view('identification/factor_detail_v',$this->data);
	}

	public function index()
	{
		$this->data['dictionary']=$this ->factor_m->getDictionary();
		$this->ci_smarty->view('identification/factor_v',$this->data);
	}
	
	public function readonly(){
		$this->data['dictionary']=$this ->factor_m->getDictionary();
		$this->ci_smarty->view('identification/readonly_factor_v',$this->data);
	}
	
	public function query(){

	}
    public function add(){
    	$data=array();
$data["student_id"]=$this->input->post("student_id");
$data["children_count"]=$this->input->post("children_count");
$data["labor_ratio"]=$this->input->post("labor_ratio");
$data["health_expense"]=$this->input->post("health_expense");
$data["disasters"]=$this->input->post("disasters");
$data["event"]=$this->input->post("event");
$data["martyr"]=$this->input->post("martyr");
$data["poor_district"]=$this->input->post("poor_district");
$data["average_income"]=$this->input->post("average_income");
$data["orphan"]=$this->input->post("orphan");
$data["disabled"]=$this->input->post("disabled");
$data["expense"]=$this->input->post("expense");
$data["application_level"]=$this->input->post("application_level");
$data["application_date"]=$this->input->post("application_date");
    	$this ->factor_m->add($data);

	}
	
	public function addPage(){
		$this->ci_smarty->view('identification/factor_add_v',$this->data);
	}
	
	public function editPage($id){
	    $this->data['id']=$id;
	    $param=array();
	    $param['id']=$id;
	    $data=$this ->factor_m->get_one($param);
	    $this->data['obj']=$data;
		$this->ci_smarty->view('identification/factor_edit_v',$this->data);
	}
	
	public function delete($id){
		 $this ->factor_m->delete($id);
	}
	
	public function update($id){
		$data=array();
$data["student_id"]=$this->input->post("student_id");
$data["children_count"]=$this->input->post("children_count");
$data["labor_ratio"]=$this->input->post("labor_ratio");
$data["health_expense"]=$this->input->post("health_expense");
$data["disasters"]=$this->input->post("disasters");
$data["event"]=$this->input->post("event");
$data["martyr"]=$this->input->post("martyr");
$data["poor_district"]=$this->input->post("poor_district");
$data["average_income"]=$this->input->post("average_income");
$data["orphan"]=$this->input->post("orphan");
$data["disabled"]=$this->input->post("disabled");
$data["expense"]=$this->input->post("expense");
$data["application_level"]=$this->input->post("application_level");
$data["application_date"]=$this->input->post("application_date");
    	$this ->factor_m->update($data,$id);
	}
	    	 
}

