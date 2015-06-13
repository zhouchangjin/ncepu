<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('invoke.php');
class User_manage_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');
	    $this->load->model('sys_manage/user_model');
	}
	public function index()
	{
		$this->data['nav'] = 'sys_manage';
		$this->data['sub_nav'] = 'user_manage';
		$this->ci_smarty->view('sys_manage/user_manage',$this->data);
	}
	// public function ajax_method()
	// {
	// 	$methodName = $_GET["method"];
	// 	if($methodName != null){
	// 		eval("\$method = ".$methodName.";");
	// 		if($method != null) $this->$method();
	// 	}
	// }
	public function AjaxSearchUsers(){

		//检测变量$_GET["key"]或者$_POST["key"]两个变量之一是否存在 ，若存在则将其值取出存于变量key
		if(isset($_GET["key"]) || isset($_POST["key"]))
		{
			$key = request("key");
		}else{
			//不存在key为空

		if(isset($_GET["key"]) || isset($_POST["key"])){
			$key = request("key");
		}else{
			$key = '';
		}
		if(isset($_GET["pageIndex"]) || isset($_POST["pageIndex"]) ){
			$pageIndex = request("pageIndex");
		}else{
			$pageIndex = 0;
		}
		if(isset($_GET["pageSize"]) || isset($_POST["pageSize"]) ){
			$pageSize = request("pageSize");
		}else{
			$pageSize = 10;
		}
		$pageStart = $pageIndex * $pageSize;
		if(isset($_GET["sortField"]) || isset($_POST["sortField"])  ){
			$sortField = request("sortField");
		}else{
			$sortField = '';
		}
		if(isset($_GET["sortOrder"])  || isset($_POST["sortOrder"]) ){
			$sortOrder = request("sortOrder");
		}else{
			$sortOrder = '';
		}
		$total = $this->user_model->get_user_count(array(),array());

		$resultData = $this->user_model->get_user_list(array(),'*',$pageStart,$pageSize,$sortOrder,$sortField);
		$ret = array("total"=>$total,"data"=>$resultData,"get"=>$pageStart,"post"=>$_POST);
		// var_dump($ret);
		writeJSON($ret);
		}
	}
	public function AjaxSaveUsers()
	{

		$json = request("data");
		$rows = php_json_decode($json);
		foreach ($rows as $row){
			$state = $row["_state"] != null?$row["_state"]:"";
			unset($row["_uid"]);
			unset($row["_index"]); 
			unset($row["_state"]);
			if($state == "added"){ //新增：id为空，或_state为added
				$row["cdate"] = date("Y-m-d   h:i:s");

				$ret = $this->user_model->addUser($row);
			}
			else if ($state == "removed" || $state == "deleted")
	    	{
	    		$id  = $row["id"];
				$ret = $this->user_model->delUser($id);
	    	}
			else if ($state == "modified" || $state)  //更新：_state为空或modified
	    	{
	    		$id  = $row["id"];
				$ret = $this->user_model->updateUser($row,$id);
	   		}
   		}
	}

}