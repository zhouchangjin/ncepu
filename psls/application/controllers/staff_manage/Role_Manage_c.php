<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once ('/Staff.php');
class Role_Manage_c extends MY_Controller {
	private $user;
	public function __construct()
	{
	    parent::__construct();
	    
	    $this->load->helper('url');	    
	    session_start();	
	    header("Cache-control: private");
	    $this->load->model('staff_manage/Right');
	    $this->load->model('staff_manage/Role');
	    $this->load->model('staff_manage/roleToRight_m');
	    $this->user     =     unserialize($_SESSION['user']);
	    $this->data['user']=$this->user;
	    $this->data['message']="";  
	 }
    public function roleAdd(){
    	$sql="select * from right_info where right_code like '4%'";
    	$this->data['role']=$this->Role->getQuery($sql);
    	//var_dump($this->data['role']);
    	$this->ci_smarty->view('staff_manage/roleAdd_v',$this->data);
    }
    public function addSave(){
    
    	$data['role_name']=$_POST['roleName'];
    	$data['department_id']=$_POST['department'];
    	//$right=$_POST['right'];
    
	    	$this->Role->insert2($data);    	
	    	/*$id=$this->Role->getQuery("select max(role_id) from role_info");
	    	var_dump($id);
	    	for($i=0;$i<count($right);$i++) {
	    		$data1['role_id']=$id[0]['max(role_id)'];
	    		//$data1['right_code']=$right[$i];
	    		$this->roleToRight_m-> insert($data1);
	    	}*/
    }
}