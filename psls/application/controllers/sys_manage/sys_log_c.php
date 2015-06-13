<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_log_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	}
	public function index()
	{
		$this->data['nav'] = 'sys_manage';
		$this->data['sub_nav'] = 'sys_log';
		$this->ci_smarty->view('sys_manage/sys_log',$this->data);
	}
}