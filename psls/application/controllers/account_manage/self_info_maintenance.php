<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type: text/html; charset=utf-8");
//echo dirname(__FILE__);
require_once(dirname(dirname(__FILE__)).'\invoke.php');
class self_info_maintenance extends MY_Controller {

	public function __construct()
	{		
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    	    
	    session_start();
	    $this->load->model('staff_manage/Staff');
	    $this->load->model('staff_manage/Department');
	    $this->load->model('staff_manage/Right');
	    $this->load->model('staff_manage/TemporaryRight');
	    $this->load->model('staff_manage/Role');
	   // if($_SESSION['user']){
	    $this->user=unserialize($_SESSION['user']);
	    $this->data['user']      =   $this->user;
	   // }
	}
	public function index()
	{		
		//修改密码的模块
		$this->ci_smarty->view("account_manage/self_info_maintenance",$this->data);
	}
	public function setNewPassword(){
		//if($_POST['newpassword'])
		$data['password']  =$_POST['newpassword'];
		$bool = 0;
		//if($_POST['userId']){
			$this->db->where('id', $_POST['userId']);
			$bool = $this->db->update("tt_user", $data);
		//}
		$this->db->where('id', $_POST['userId']);
		$bool = $this->db->update("tt_user", $data);
		if($bool) 
		$this->data['error_msg']=2;
		//session_start();
		unset($_SESSION['user']);
		unset($_SESSION['logged_in']);
		
		$this->ci_smarty->view('login',$this->data);
	}
	
}