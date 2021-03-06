<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Factor_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('identification/factor_m');
	    $this->load->model('identification/student_m');
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
	
	public function temp(){
		
		for($i=0;$i<30;$i++){
			$id=1132227210+$i;
			$stu=array();
			$stu["id"]=''.$id;
			$stu["name"]='test'.$i;
			$stu['class']='控计1319';
			$this ->student_m->add($stu);
			$data=array();
			$data["student_id"]=''.$id;
			$data["children_count"]=mt_rand(0, 10);
			$data["labor_ratio"]=mt_rand(0, 1);
			$data["health_expense"]=mt_rand(0, 2);
			$data["disasters"]=mt_rand(0, 2);
			$data["event"]=mt_rand(0, 2);
			$data["martyr"]=mt_rand(0, 2);
			$data["poor_district"]=mt_rand(0, 1);
			$data["average_income"]=mt_rand(0, 10000);
			$data["orphan"]=mt_rand(0,1);
			$data["disabled"]=mt_rand(0, 1);
			$data["expense"]=mt_rand(0, 1000);
			$data["application_level"]='A';
			$this ->factor_m->add($data);
		}
	}

	public function grid(){
		$param = array();
		$like = array();
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$className=$this->input->post('class_name');
		if($className){
			$param['class']=$className;
		}
		$page_start = $rows*($page-1);
		$per_page = $rows;
		$field_param='factor.id';
		$metadata=$this ->factor_m->getFieldsMetadata();
		$jointtables=array();
		for($i=0;$i<sizeof($metadata);$i++){
		    $field=$metadata[$i];
			if(strstr($field->name,"_id")){
			   array_push($jointtables,str_replace("_id","",$field->name));
			}else{
			    if($field->name!='id'){
			    	$field_param.=',factor.'.$field->name;
			    }
				
			}
		}
		if(sizeof($jointtables)==0){
			$count=$this ->factor_m->getCount($param);	
			$this->data['list'] = $this ->factor_m->get_list($param,'*',$page_start,$per_page);
		}else{
		    $jointcondition=array();
			for($k=0;$k<sizeof($jointtables);$k++){
			    $leftjoin=array();
				$table=$jointtables[$k];
				$alias='t_'.$k;
				$condition='factor.'.$table.'_id='.$alias.'.id';
				$leftjoin['table']=$table.' '.$alias;
				$leftjoin['condition']=$condition;
				$leftjoin['type']='left';
				array_push($jointcondition,$leftjoin);
				$field_param.=','.$alias.'.name as '.$table;
			}
			$count=$this ->factor_m->get_list_count2($jointcondition,$param);	
			$this->data['list'] = $this ->factor_m->get_list2($jointcondition,$param,$field_param,$page_start,$per_page);
		}
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
if($this->input->post("student_id")){$data["student_id"]=$this->input->post("student_id");}
if($this->input->post("children_count")){$data["children_count"]=$this->input->post("children_count");}
if($this->input->post("labor_ratio")){$data["labor_ratio"]=$this->input->post("labor_ratio");}
if($this->input->post("health_expense")){$data["health_expense"]=$this->input->post("health_expense");}
if($this->input->post("disasters")){$data["disasters"]=$this->input->post("disasters");}
if($this->input->post("event")){$data["event"]=$this->input->post("event");}
if($this->input->post("martyr")){$data["martyr"]=$this->input->post("martyr");}
if($this->input->post("poor_district")){$data["poor_district"]=$this->input->post("poor_district");}
if($this->input->post("average_income")){$data["average_income"]=$this->input->post("average_income");}
if($this->input->post("orphan")){$data["orphan"]=$this->input->post("orphan");}
if($this->input->post("disabled")){$data["disabled"]=$this->input->post("disabled");}
if($this->input->post("expense")){$data["expense"]=$this->input->post("expense");}
if($this->input->post("application_level")){$data["application_level"]=$this->input->post("application_level");}
if($this->input->post("application_date")){$data["application_date"]=$this->input->post("application_date");}
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
if($this->input->post("student_id")){$data["student_id"]=$this->input->post("student_id");}
if($this->input->post("children_count")){$data["children_count"]=$this->input->post("children_count");}
if($this->input->post("labor_ratio")){$data["labor_ratio"]=$this->input->post("labor_ratio");}
if($this->input->post("health_expense")){$data["health_expense"]=$this->input->post("health_expense");}
if($this->input->post("disasters")){$data["disasters"]=$this->input->post("disasters");}
if($this->input->post("event")){$data["event"]=$this->input->post("event");}
if($this->input->post("martyr")){$data["martyr"]=$this->input->post("martyr");}
if($this->input->post("poor_district")){$data["poor_district"]=$this->input->post("poor_district");}
if($this->input->post("average_income")){$data["average_income"]=$this->input->post("average_income");}
if($this->input->post("orphan")){$data["orphan"]=$this->input->post("orphan");}
if($this->input->post("disabled")){$data["disabled"]=$this->input->post("disabled");}
if($this->input->post("expense")){$data["expense"]=$this->input->post("expense");}
if($this->input->post("application_level")){$data["application_level"]=$this->input->post("application_level");}
if($this->input->post("application_date")){$data["application_date"]=$this->input->post("application_date");}
    	$this ->factor_m->update($data,$id);
	}
	    	 
}

