<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Userinterface extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
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
	public function menu(){
		//$rolename="coordinator";
		$rolename=$this->user->getRole()->getRoleAlias();
		$this->getMenu($rolename);
	}
	
	public function home(){
		$rolename=$this->user->getRole()->getRoleAlias();
		$this->ci_smarty->view('homepage/'.$rolename,$this->data);
	}
	
	public function getEnumList($table,$col){
		$query=$this->db->query("select * from dictionary where table_name='".$table."' and column_name='".$col."'");
		echo json_encode($query->result_array());
	}
	
	public function getList($table,$col,$idcol){
		$where=$this->input->post("where");
		$q=$this->db->select("".$idcol.",".$col);//query("select ".$idcol.",".$col." from ".$table."");
		if($where){
			$q=$q->where($where);
		}
		$q=$q->get($table);
		echo json_encode($q->result_array());
	}
	
	function getMenu($name){
		$path=dirname(dirname(dirname(__FILE__)))."/menu/".$name.".xml";
		$xml_object=NULL;
		if (file_exists($path))
		{
			$xml_object = simplexml_load_file($path);
		}
		else
		{
			exit('Failed to open test.xml.');
		}
		$xml_json=json_encode($xml_object);
		echo str_replace("@attributes","attrs",$xml_json);

	}
}

