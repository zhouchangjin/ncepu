<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_manage_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	}
	public function index()
	{
		$this->data['nav'] = 'sys_manage';
		$this->data['sub_nav'] = 'role_manage';
		$this->ci_smarty->view('sys_manage/role_manage',$this->data);
	}
}