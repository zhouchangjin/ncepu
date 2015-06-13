<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class picture_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('sys_manage/picture_m');
		session_start();
	    header("Cache-control: private");
	    $this->load->model('staff_manage/Staff');
	    $this->load->model('staff_manage/Department');
	    $this->load->model('staff_manage/Right');
	    $this->load->model('staff_manage/TemporaryRight');
	    $this->load->model('staff_manage/Role');
	    $this->load->model('sys_manage/picture_m');
	    $this->user=unserialize($_SESSION['user']);
	    $this->data['user']      =   $this->user;

	}

	public function uploadPicture()
	{
		$absoluteAddress=dirname(dirname(dirname(__FILE__)));
		$fileName=substr($_FILES["photo"]["name"],0,strrpos($_FILES["photo"]["name"], '.'));
		$houzhui=substr($_FILES["photo"]["name"],strrpos($_FILES["photo"]["name"], '.'),strlen($_FILES["photo"]["name"]));
		$tmp = $_FILES['photo']['tmp_name'];
		if (!empty ($tmp)) 
		{ 
					$file_name = date('Ymdhis')."$houzhui";//上传后的文件保存路径和名称
					copy($tmp, "ui/upload_picture/".$file_name);
			}		
		$data=array();
		$data["title"]=$this->input->post("title");
		$data["content"]=$this->input->post("content");
		$data["picpath"]=$file_name;
    	$this ->picture_m->add($data);
	}

	public function grid(){
		$param = array();
		$like = array();
		$rows=$this->input->post('rows')?$this->input->post('rows'):10;
		$page=$this->input->post('page')?$this->input->post('page'):1;
		$page_start = $rows*($page-1);
		$per_page = $rows;
		$count=$this ->picture_m->getCount($param);	
		$this->data['list'] = $this ->picture_m->get_list($param,'*',$page_start,$per_page);
		$tGrid=array();
		$tGrid['total']=$count;
		$tGrid['rows']=$this->data['list'];
	    echo json_encode($tGrid);
	}

	public function detail($id){
		$param=array();
		$param['id']=$id;
		$this->data['obj']=$this->picture_m->getOne($param);
		$this->ci_smarty->view('sys_manage/picture_detail_v',$this->data);
	}

	public function index()
	{
		$this->ci_smarty->view('sys_manage/picture_v',$this->data);
	}
	
	public function readonly(){
		$this->ci_smarty->view('sys_manage/readonly_picture_v',$this->data);
	}
	
	public function query(){

	}
    public function add(){
    	$data=array();
		$data["title"]=$this->input->post("title");
		$data["content"]=$this->input->post("content");
		$data["picpath"]=$_FILES["photo"]["name"];
    	$this ->picture_m->add($data);

	}
	
	public function addPage(){
		$this->ci_smarty->view('sys_manage/picture_add_v',$this->data);
	}
	
	public function editPage($id){
	    $this->data['id']=$id;
	    $param=array();
	    $param['id']=$id;
	    $data=$this ->picture_m->get_one($param);
	    $this->data['obj']=$data;
		$this->ci_smarty->view('sys_manage/picture_edit_v',$this->data);
	}
	
	public function delete($id){
		 $this ->picture_m->delete($id);
	}
	
	public function update($id){
		$data=array();
		$data["title"]=$this->input->post("title");
		$data["content"]=$this->input->post("content");
    	$this ->picture_m->update($data,$id);
	}
	    	 
}

