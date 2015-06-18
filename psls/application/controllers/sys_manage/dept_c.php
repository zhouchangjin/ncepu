<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dept_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('sys_manage/dept_m');
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
		$field_param='dept.id';
		$metadata=$this ->dept_m->getFieldsMetadata();
		$jointtables=array();
		for($i=0;$i<sizeof($metadata);$i++){
		    $field=$metadata[$i];
			if(strstr($field->name,"_id")){
			   array_push($jointtables,str_replace("_id","",$field->name));
			}else{
			    if($field->name!='id'){
			    	$field_param.=',dept.'.$field->name;
			    }
				
			}
		}
		if(sizeof($jointtables)==0){
			$count=$this ->dept_m->getCount($param);	
			$this->data['list'] = $this ->dept_m->get_list($param,'*',$page_start,$per_page);
		}else{
		    $jointcondition=array();
			for($k=0;$k<sizeof($jointtables);$k++){
			    $leftjoin=array();
				$table=$jointtables[$k];
				$alias='t_'.$k;
				$condition='dept.'.$table.'_id='.$alias.'.id';
				$leftjoin['table']=$table.' '.$alias;
				$leftjoin['condition']=$condition;
				$leftjoin['type']='left';
				array_push($jointcondition,$leftjoin);
				$field_param.=','.$alias.'.name as '.$table;
			}
			$count=$this ->dept_m->get_list_count2($jointcondition,$param);	
			$this->data['list'] = $this ->dept_m->get_list2($jointcondition,$param,$field_param,$page_start,$per_page);
		}
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}

	public function detail($id){
		$param=array();
		$param['id']=$id;
		$this->data['obj']=$this->dept_m->getOne($param);
		$this->ci_smarty->view('sys_manage/dept_detail_v',$this->data);
	}

	public function index()
	{
		$this->data['dictionary']=$this ->dept_m->getDictionary();
		$this->ci_smarty->view('sys_manage/dept_v',$this->data);
	}
	
	public function readonly(){
		$this->data['dictionary']=$this ->dept_m->getDictionary();
		$this->ci_smarty->view('sys_manage/readonly_dept_v',$this->data);
	}
	
	public function query(){

	}
    public function add(){
    	$data=array();
if($this->input->post("name")){$data["name"]=$this->input->post("name");}
if($this->input->post("description")){$data["description"]=$this->input->post("description");}
    	$this ->dept_m->add($data);

	}
	
	public function addPage(){
		$this->ci_smarty->view('sys_manage/dept_add_v',$this->data);
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
if($this->input->post("name")){$data["name"]=$this->input->post("name");}
if($this->input->post("description")){$data["description"]=$this->input->post("description");}
    	$this ->dept_m->update($data,$id);
	}
	
	public function org(){
		$list=array();
		$nodeid=$this->input->post("id");
		if($nodeid==""){
			$treeNode=array();
			$treeNode['id']='root';
			$treeNode['text']="华北电力大学";
			$treeNode['children']=array();
			$treeNode['state']="closed";
			array_push($list, $treeNode);
		}else if($nodeid=="root"){
			$res=$this ->dept_m->getQuery('select * from dept');
			for($i=0;$i<sizeof($res);$i++){
				$treeNode=array();
				$record=$res[$i];
				$treeNode['id']=$record['id'];
				$treeNode['text']=$record['name'];
				$treeNode['children']=array();
				$treeNode['state']="closed";
				array_push($list, $treeNode);
			}
		}else if(sizeof(explode("_", $nodeid))==1){
		$res=$this ->dept_m->getQuery('select * from class where dept_id="'.$nodeid.'"');
			for($i=0;$i<sizeof($res);$i++){
				$treeNode=array();
				$record=$res[$i];
				$treeNode['id']=$nodeid."_".$record['id'];
				$treeNode['text']=$record['name'];
				$treeNode['children']=array();
				$treeNode['state']="closed";
				array_push($list, $treeNode);
			}
		}
		echo json_encode($list);
	}
	    	 
}

