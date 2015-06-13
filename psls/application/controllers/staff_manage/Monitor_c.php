<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once ('/Staff.php');
class Monitor_c extends MY_Controller {
	private $user;
	public function __construct()
	{
	    parent::__construct();
	    $GLOBALS['department_name'] 	= array(0=>'财务资产部',3 => '筹备处',2 => '综合管理部',4 => '甸顶山风电场',5 => '计划工程部',6=>'安全生产部',10=>'公司领导');
	     
	    $this->load->helper('url');	    
	    session_start();	
	    header("Cache-control: private");
	    $this->load->model('staff_manage/Staff');
	    $this->load->model('sys_manage/user_model');
	    $this->load->model('sys_manage/monitor_model');
	    $this->load->model('staff_manage/Right');
	    $this->load->model('staff_manage/Role');
	    $this->load->model('staff_manage/StaffControl');
	    $this->load->model('staff_manage/roleToRight_m');
	    $this->user     =     unserialize($_SESSION['user']);
	    $this->data['user']=$this->user;
	    $this->data['message']="";  
	    
	 }

    public function index()
    {
    	
    	$this->ci_smarty->view('test1',$this->data);
    }
	

	public function getdata(){ 
		$param                   = array();
    	$like                    = array();
		$user_list  = $this->monitor_model->get_user_list($param,'*','','',$like);
 	    echo json_encode($user_list);
	}
}