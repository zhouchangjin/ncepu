<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Department_info_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('sys_manage/department_info_m');
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
		$field_param='department_info.id';
		$metadata=$this ->department_info_m->getFieldsMetadata();
		$jointtables=array();
		for($i=0;$i<sizeof($metadata);$i++){
		    $field=$metadata[$i];
			if(strstr($field->name,"_id")){
			   array_push($jointtables,str_replace("_id","",$field->name));
			}else{
			    if($field->name!='id'){
			    	$field_param.=',department_info.'.$field->name;
			    }
				
			}
		}
		if(sizeof($jointtables)==0){
			$count=$this ->department_info_m->getCount($param);	
			$this->data['list'] = $this ->department_info_m->get_list($param,'*',$page_start,$per_page);
		}else{
		    $jointcondition=array();
			for($k=0;$k<sizeof($jointtables);$k++){
			    $leftjoin=array();
				$table=$jointtables[$k];
				$alias='t_'.$k;
				$condition='department_info.'.$table.'_id='.$alias.'.id';
				$leftjoin['table']=$table.' '.$alias;
				$leftjoin['condition']=$condition;
				$leftjoin['type']='left';
				array_push($jointcondition,$leftjoin);
				$field_param.=','.$alias.'.name as '.$table;
			}
			$count=$this ->department_info_m->get_list_count2($jointcondition,$param);	
			$this->data['list'] = $this ->department_info_m->get_list2($jointcondition,$param,$field_param,$page_start,$per_page);
		}
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}

	public function detail($id){
		$param=array();
		$param['id']=$id;
		$this->data['obj']=$this->department_info_m->getOne($param);
		$this->ci_smarty->view('sys_manage/department_info_detail_v',$this->data);
	}

	public function index()
	{
		$this->data['dictionary']=$this ->department_info_m->getDictionary();
		$this->ci_smarty->view('sys_manage/department_info_v',$this->data);
	}
	
	public function readonly(){
		$this->data['dictionary']=$this ->department_info_m->getDictionary();
		$this->ci_smarty->view('sys_manage/readonly_department_info_v',$this->data);
	}
	
	public function query(){

	}
    public function add(){
    	$data=array();
$data["name"]=$this->input->post("name");
$data["description"]=$this->input->post("description");
$data["address"]=$this->input->post("address");
$data["department_info_id"]=$this->input->post("department_info_id");
    	$this ->department_info_m->add($data);

	}
	
	public function addPage(){
		$this->ci_smarty->view('sys_manage/department_info_add_v',$this->data);
	}
	
	public function editPage($id){
	    $this->data['id']=$id;
	    $param=array();
	    $param['id']=$id;
	    $data=$this ->department_info_m->get_one($param);
	    $this->data['obj']=$data;
		$this->ci_smarty->view('sys_manage/department_info_edit_v',$this->data);
	}
	
	public function delete($id){
		 $this ->department_info_m->delete($id);
	}
	
	public function update($id){
		$data=array();
$data["name"]=$this->input->post("name");
$data["description"]=$this->input->post("description");
$data["address"]=$this->input->post("address");
$data["department_info_id"]=$this->input->post("department_info_id");
    	$this ->department_info_m->update($data,$id);
	}
	    	 
}

