<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Department_info_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('sys_manage/department_info_m');
	    $this->load->model('sys_manage/company_dept_m');
	    $this->load->model('sys_manage/dept_m');
	    $this->load->model('sys_manage/dept_role_m');
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
	
	public function addDeptGrid($id){
		$param = array();
		$param["company_id"]=NULL;
		$like = array();
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$page_start = $rows*($page-1);
		$per_page = $rows;
		$fields="dept.id,company_dept.company_id,dept.name,dept.description";
		$jointcondition=array();
		$condition1=array();
		$condition1['table']="company_dept";
		$condition1['condition']="company_dept.dept_id=dept.id and company_id=".$id;
		$condition1['type']='left';
		array_push($jointcondition, $condition1);
		$count=$this ->dept_m->get_list_count2($jointcondition,$param);
		$this->data['list'] = $this ->dept_m->get_list2($jointcondition,$param,$fields,$page_start,$per_page);
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
		$count=$this ->department_info_m->getCount($param);	
		$this->data['list'] = $this ->department_info_m->get_list($param,'*',$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}

	public function detail($id){
		$param=array();
		$param['department_id']=$id;
		$this->data['obj']=$this->department_info_m->getOne($param);
		$this->ci_smarty->view('sys_manage/department_info_detail_v',$this->data);
	}
	
	public function deptView($id){
		$this->data['id']=$id;
		$this->ci_smarty->view('sys_manage/department_dept',$this->data);
	}

	public function index()
	{
		$this->ci_smarty->view('sys_manage/department_info_v',$this->data);
	}
	public function query(){

	}
    public function add(){
    	$data=array();
		$data["department_id"]=$this->input->post("department_id");
		$data["department_name"]=$this->input->post("department_name");
		$data["description"]=$this->input->post("description");
    	$this ->department_info_m->add($data);

	}
	
	public function addPage(){
		$this->ci_smarty->view('sys_manage/department_info_add_v',$this->data);
	}
	
	public function assignUserPage($id,$dept_id,$role_id){
		$this->data['company_id']=$id;
		$this->data['dept_id']=$dept_id;
		$this->data['role_id']=$role_id;
		$this->ci_smarty->view('sys_manage/department_info_assignUser_v',$this->data);
	}
	public function assignUser(){
		$ids=$this->input->post('ids');
		$company_id=$this->input->post('company_id');
		$dept_id=$this->input->post('dept_id');
		$role_id=$this->input->post('role_id');
		$idList=explode(";", $ids);
		$data=array();
		$data['department_id']=$company_id;
		$data['dept_id']=$dept_id;
		$data['role_id']=$role_id;
		for($i=0;$i<sizeof($idList);$i++){
			$id=$idList[$i];
			$this->tt_user_m->update($data,$id);
		}
		
	}
	public function addDeptPage($id){
		$this->data['id']=$id;
		$this->ci_smarty->view('sys_manage/department_info_addDept_v',$this->data);
	}
	
	public function editPage($id){
	    $this->data['id']=$id;
	    $param=array();
	    $param['department_id']=$id;
	    $data=$this ->department_info_m->get_one($param);
	    $this->data['obj']=$data;
		$this->ci_smarty->view('sys_manage/department_info_edit_v',$this->data);
	}
	
	public function delete($id){
		 $this ->department_info_m->delete($id,'true','department_id');
	}
	
	public function update($id){
		$data=array();
		$data["department_id"]=$this->input->post("department_id");
		$data["department_name"]=$this->input->post("department_name");
		$data["description"]=$this->input->post("description");
    	$this ->department_info_m->update($data,$id);
	}
	public function usergrid($company_id,$dept_id,$role_id){
		$param=array();
		$param['b.name']=NULL;
		$like = array();
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$page_start = $rows*($page-1);
		$per_page = $rows;
		$fields="tt_user.id,tt_user.name as username,b.name,department_name as company_name,role_name,dept.name AS department_name";
		$jointcondition=array();
		$condition1=array();
		$condition1['table']="dept";
		$condition1['condition']="tt_user.dept_id=dept.id";
		$condition1['type']='left';
		$condition2=array();
		$condition2['table']="role_info";
		$condition2['condition']="tt_user.role_id=role_info.role_id";
		$condition2['type']='left';
		$condition3=array();
		$condition3['table']="department_info";
		$condition3['condition']="tt_user.department_id=department_info.department_id";
		$condition3['type']='left';
		$condition4=array();
		$condition4['table']="(SELECT * FROM tt_user WHERE department_id=".$company_id." AND dept_id=".$dept_id." AND role_id=".$role_id." ) b ";
		$condition4['condition']="tt_user.id=b.id";
		$condition4['type']='left';
		array_push($jointcondition, $condition1);
		array_push($jointcondition, $condition2);
		array_push($jointcondition, $condition3);
		array_push($jointcondition, $condition4);
		$count=$this ->tt_user_m->get_list_count2($jointcondition,$param);
		$this->data['list'] = $this ->tt_user_m->get_list2($jointcondition,$param,$fields,$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
		echo json_encode($tGrid);
	}
	public function deptgrid($id){
		
		$param = array();
		$like = array();
		$param['company_dept.company_id']=$id;
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$page_start = $rows*($page-1);
		$per_page = $rows;
		$fields="company_dept.id,company_dept.dept_id,company_dept.company_id,dept.name,dept.description";
		$jointcondition=array();
		$condition1=array();
		$condition1['table']="dept";
		$condition1['condition']="company_dept.dept_id=dept.id";
		$condition1['type']='left';
		array_push($jointcondition, $condition1);
		$count=$this ->company_dept_m->get_list_count2($jointcondition,$param);
		$this->data['list'] = $this ->company_dept_m->get_list2($jointcondition,$param,$fields,$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
		echo json_encode($tGrid);
		
	}
	public function addDepts(){
		$id=$this->input->post("id");
		$ids=$this->input->post("ids");
		$idList=explode(";", $ids);
		for($i=0;$i<sizeof($idList);$i++){
			$params['company_id']=$id;
			$params['dept_id']=$idList[$i];
			if($idList[$i]==""){
				continue;
			}
			$this ->company_dept_m->add($params);
		}
	}
	public function org($id){
		$list=array();
		$nodeid=$this->input->post("id");
		if($nodeid==""){
			$treeNode=array();
			$treeNode['id']='root';
			$treeNode['text']="公司";
			$treeNode['children']=array();
			$treeNode['state']="closed";
			array_push($list, $treeNode);
		}else if(sizeof(explode("_", $nodeid))==1){
			
			$res=$this ->company_dept_m->getQuery('select company_dept.company_id,company_dept.dept_id,department_info.department_name,dept.name from company_dept left join department_info on company_dept.company_id=department_info.department_id left join dept on company_dept.dept_id=dept.id   where company_id=\''.$id.'\'');
			for($i=0;$i<sizeof($res);$i++){
				$treeNode=array();
				$record=$res[$i];
				$treeNode['id']=$record['company_id'].'_'.$record['dept_id'];
				$treeNode['text']=$record['name'];
				$treeNode['children']=array();
				$treeNode['state']="closed";
				array_push($list, $treeNode);
			}
			
		}else if(sizeof(explode("_", $nodeid))==2){
			$strlist=explode("_", $nodeid);
			$company_id=$strlist[0];
			$dept_id=$strlist[1];
			$res=$this->dept_role_m->getQuery("select dept_role.dept_id,dept_role.role_id,role_info.role_name from dept_role left join role_info on dept_role.role_id=role_info.role_id where dept_role.dept_id='".$dept_id."'");
			for($i=0;$i<sizeof($res);$i++){
				$treeNode=array();
				$record=$res[$i];
				$treeNode['id']=$nodeid.'_'.$record['role_id'];
				$treeNode['text']=$record['role_name'];
				$treeNode['children']=array();
				$treeNode['state']="closed";
				array_push($list, $treeNode);
			}

		}else if(sizeof(explode("_", $nodeid))==3){
			$strlist=explode("_", $nodeid);
			$company_id=$strlist[0];
			$dept_id=$strlist[1];
			$role_id=$strlist[2];
			$res=$this->dept_role_m->getQuery("select * from tt_user where dept_id='".$dept_id."' and role_id='".$role_id."' and department_id='".$company_id."'");
			for($i=0;$i<sizeof($res);$i++){
				$treeNode=array();
				$record=$res[$i];
				$treeNode['id']=$nodeid.'_'.$record['id'];
				$treeNode['text']=$record['name'];
				$treeNode['children']=array();
				$treeNode['state']="open";
				array_push($list, $treeNode);
			}	
		}
		echo json_encode($list);
		
		
	}
	    	 
}

