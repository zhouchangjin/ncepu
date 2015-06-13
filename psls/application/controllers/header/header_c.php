<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class test
  {
    public function doit($oldfile,$newfile)
    {
      $command = "c:\\flashpaper.bat  $newfile  $oldfile";
      //echo $command;
      exec($command);
	//echo exec($command);die;
    }
  }
class header_c extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->library('session');

	    $this->load->model('header/header_model');


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

	
	
	public function index()
	{
		$param                   = array();
		$like                    = array();
		$key    		     = $this->input->post('key')?$this->input->post('key'):$this->input->get('key');
		
		if ($key) {
			//$like['work_type'] = $key;
			$sql="select * from header"." where title like '%".$key."%' order by edate desc";
		}
		else
			$sql="select * from header order by edate desc";
		
		
		//echo $sql;die;
		$gonggao_list     = $this->header_model-> getQuery($sql);
		
		//$gonggao_list  = $this->header_model->get_list($param,'*',0,'','','',$like);
		

		

		$this->data['gonggao_list']      = $gonggao_list;
		
		$this->ci_smarty->view('gonggao/gonggao',$this->data);
	}

	public function modify()
	{
		$like = array();
		$id                             = $this->input->post('id');
		$gonggao                        = $this->input->post('gonggao');
		$title                          = $this->input->post('title');
		$safety_policy                  = $this->input->post('safety_policy');
		if ($gonggao) {
			$like['gonggao']                = $gonggao;
		}
		if ($title) {
			$like['title']                  = $title;
		}
		if ($safety_policy) {
			$like['safety_policy']          = $safety_policy;
		}
		$ret = $this->header_model->update_new($like,$id);
		if ($ret) {
			$like['id'] = $id;
			$list = $this->header_model->get_one($like);
			$this->data['list'] = $list;
			$this->ci_smarty->view('header',$this->data);
		}
	}

	public function modifyGonggao($id)
	{
		$param                   = array();
		$param['id'] = $id;
		$gonggao = $this->header_model->get_one($param);
		$this->data['gonggao'] = $gonggao;
		$this->ci_smarty->view('gonggao/modify_gonggao',$this->data);
	}

	public function updateGonggao()
	{
		$like = array();
		$id                             = $this->input->post('id');
		$gonggao                        = $this->input->post('gonggao');
		$title                          = $this->input->post('title');
		if ($gonggao) {
			$like['gonggao']                = $gonggao;
		}
		if ($title) {
			$like['title']                  = $title;
		}
		
		$ret = $this->header_model->update_new($like,$id);
		if ($ret) {
			$this->index();
		}
	}

	public function addGonggao()
	{
		$this->ci_smarty->view('gonggao/add_gonggao',$this->data);
	}

	public function deleteGonggao()
	{
		$gonggao               = $this->input->post('del');
		if ($gonggao) {
			$i=0;
			while (@$gonggao[$i])
			{
				$this->header_model->del(@$gonggao[$i]);
				$i++;
			}
			$msg = "删除成功！";
		}
		else
			$msg = "请选择记录";
		$this->data['msg']= $msg;
		$this->index();
	}

	public function saveGonggao()
	{

		$gonggao['title']               = $this->input->post('title');
		$gonggao['gonggao']               = $this->input->post('gonggao');
		$ret = $this->header_model->add($gonggao);
		if ($ret) {
			$msg = "添加成功！";
			$this->data['msg']= $msg;
			$this->addGonggao();		
		}
	}
	
}