<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Database_admin_c extends MY_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$this->ci_smarty->view('devMode/database_mgr',$this->data);
	}
	
	public function create_table(){
		$this->ci_smarty->view('devMode/create_table',$this->data);
	}
}