<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// 6月1号 长进修改
error_reporting(E_ALL & ~E_NOTICE);
class Login extends MY_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('url');
	    $this->load->model('sys_manage/department_info_m');
	    $this->load->model('sys_manage/user_model');
	    $this->load->model('staff_manage/Staff');
	    $this->load->model('staff_manage/Department');
	    $this->load->model('staff_manage/Right');
	    $this->load->model('staff_manage/Role');
	    $this->load->library('session');
	}

	public function index()
	{
		$this->data['error_msg'] = '3';
		$this->data['nav'] = '';
		$this->data['sub_nav'] = '';

		date_default_timezone_set('PRC');
		$date =   date('Y-m-d H:i:s',time());
		$date =  substr($date,0,10);
		
		$this->data['date'] = $date;
		$date = explode('-', $date);
		$k = date("w");
		$this->data['time1'] = date('Y-m-d',strtotime('-1 week'));

		$sql="select * from department_info";
		$department_list     = $this->department_info_m-> getQuery($sql);
		$this->data['department_list'] = $department_list;
		
		
		
	    $this->ci_smarty->view('noLogin/shouye',$this->data);
	}

	

	public function user_login()
	{
		//die;
		$username = trim($this->input->post('username'));
		//$department = trim($this->input->post('department'));
		//echo("<script> alert("+$username+") </script>");
		$password = trim($this->input->post('password'));
		$sql="select * from tt_user".
				 " where account='".$username."'";
		//echo $sql;die;
		$userInfo     = $this->user_model-> getQuery($sql);
		
		
		//var_dump($user[0]['password']);die;
		
		if (empty($userInfo)) {
			$this->data['error_msg'] = '0';
			$this->ci_smarty->view('shouye',$this->data);
		}elseif ($userInfo[0]['password'] != $password) {

			$this->data['error_msg'] = '1';
			$this->ci_smarty->view('login',$this->data);
		}else{
			$sql="select a.department_info_id,b.name,".
			"c.id,c.name,a.id,".
			"a.name,a.birthdate,a.gender,a.password,a.status,c.role_alias".
			" from tt_user a,".
			"department_info b,role_info c".			
		    " where a.account='".$username."' and a.department_info_id=b.id and a.role_info_id=c.id";
		  
			$userInfo     = $this->user_model-> getQuery($sql);
			//创建登录用户对象，保存用户信息到session中
			session_start();
			$department= new Department();
			$department->setDepartmentId($userInfo[0]['department_info_id']);
			$department->setDepartmentName($userInfo[0]['department_name']);
			$department->setDeptId($userInfo[0]['dept_id']);
			$department->setDeptName($userInfo[0]['dept_name']);
			
			$role =new Role();			
			$role->setRoleId($userInfo[0]['role_id']);
			$role->setRoleName($userInfo[0]['role_name']);
			$role->setRoleRight();
			$role->setRoleAlias($userInfo[0]['role_alias']);
			$user =new Staff();
			$user->setId($userInfo[0]['id']);
			$user->setName($userInfo[0]['name']);
			$user->setBirthdate($userInfo[0]['birthdate']);
			$user->setGender($userInfo[0]['gender']);
			$user->setPassword($userInfo[0]['password']);
			$user->setDepartment($department);
			$user->setStatus($userInfo[0]['status']);
			$user->setRole($role);
			$user->setGroup($userInfo[0]['work_group']);
			$_SESSION['user']     = serialize($user);
			$_SESSION['logged_in']=true;
			redirect('login/homepage');
	    }
	}
	
	public function indexpage(){
		session_start();
		$user      =unserialize($_SESSION['user']);
		$logged_in =$_SESSION['logged_in'];
		$this->data['user']=$user;
		$sql="select * from header order by cdate desc";
		$list     = $this->header_model-> getQuery($sql);
		$this->data['list'] = $list;
		$this->ci_smarty->view('indexpage',$this->data);
	}
	
	public function homepage()
	{
		session_start();
		$user      =unserialize($_SESSION['user']);
		$logged_in =$_SESSION['logged_in'];
		
		
		$this->data['user']=$user;

		if ($logged_in) 
		{
			//获取公告信息
			$this->ci_smarty->view_content('header',$this->data);
		}else{
			redirect('login/index');
		}
	}
	public function user_logout()
	{
		session_start();
		unset($_SESSION['user']);
		unset($_SESSION['logged_in']);
		//var_dump($_SESSION['user']);die;
		redirect('login/index');
	}

	public function timeline()
	{
		$sql="select * from kancha_data";
		$list     = $this->header_model-> getQuery($sql);
		//print_r($list);die;
		$this->data['list'] = $list;
		$this->ci_smarty->view('timeline/timeline',$this->data);
	}

	public function user_login_view()
	{	
		$this->data['error_msg'] = '3';
		$this->data['nav'] = '';
		$this->data['sub_nav'] = '';
		$this->ci_smarty->view('login',$this->data);
	}

	public function plan_work()
	{
		$param                   = array();
		$like                    = array();

		$company    		 = $this->input->post('company')?$this->input->post('company'):$this->input->get('company');
		$status    		     = $this->input->post('status')?$this->input->post('status'):$this->input->get('status');
		$check_people    	 = $this->input->post('check_people')?$this->input->post('check_people'):$this->input->get('check_people');
		$rank    		     = $this->input->post('rank')?$this->input->post('rank'):$this->input->get('rank');
		$time1               = $this->input->post('time1')?$this->input->post('time1'):$this->input->get('time1');
		$time2               = $this->input->post('time2')?$this->input->post('time2'):$this->input->get('time2');
		$key    		     = $this->input->post('key')?$this->input->post('key'):$this->input->get('key');
		
		if ($company&&$company!="全部") {
			$param['company']  = $company;
		}
		if ($status) 
		{
			if ($status!=4) {
				$param['status'] = $status;
			}
		}
		//echo $status;
		if ($check_people&&$check_people!="全部") {
			$like['check_people']  = substr($check_people, 0,3);
		}
		if ($rank&&$rank!="全部") {
			$param['rank'] = $rank;
		}
		
		if ($key) {
			$like['task'] = $key;
			$like['facility'] = $key;
		}
		
		$page_start 				= $this->input->get('per_page');
		$per_page 					= 11;
		if (!$page_start) {
			$page_start = 0;
		}

		$plan_list  = $this->plan_into_model->get_work_list($param,'*',$page_start,$per_page,'','',$like);
		$list = array();
		$i=0;
		if ($time1&&empty($time2)) {
			foreach ($plan_list as $p ) {
				if ($p['plan_start_time']>=$time1)
				{
					$list[$i++]=$p;
				}
			}
			$plan_list = $list;
		}

		elseif ($time2&&empty($time1)) {
			foreach ($plan_list as $p ) {
				if ($p['plan_start_time']<=$time2)
				{
					$list[$i++]=$p;
				}
			}
			$plan_list = $list;
		}

		elseif($time1&&$time2) {
			foreach ($plan_list as $p ) {
				if ($p['plan_start_time']<=$time2&&$p['plan_start_time']>=$time1)
				{
					$list[$i++]=$p;
				}
			}
			$plan_list = $list;
		}
		$this->load->library('pagination');
		$config['base_url']   = '/psls/login/plan_work/?page=true?&company='.$company."&status=".$status."&check_people=".$check_people."&rank=".$rank."&time1=".$time1."&time2=".$time2."&key=".$key;
		if ($time1||$time2) {
			$config['total_rows'] = $i-1;
		}
		else
		{
			$config['total_rows'] = $this->plan_into_model->get_work_list_count($param,$like);
		}
		$config['per_page']   = $per_page; 
		$config['page_query_string'] = TRUE;
		$config['first_link'] = '首页';
		$config['last_link'] = '末页';
		$config['next_link'] = '下一页';
		$config['prev_link'] = '上一页';
		
		//$config['cur_tag_open'] = '当前页';
		//$config['display_pages'] = FALSE;
		$this->pagination->initialize($config);
		$this->data['page_link'] = $this->pagination->create_links();

		$this->data['plan_list']   = $plan_list;
		$this->data['count']       = $config['total_rows'];

		$this->data['company']             = $company;
		$this->data['status']              = $status;
		$this->data['check_people']        = $check_people;
		$this->data['rank']                = $rank;
		$this->data['time1']               = $time1;
		$this->data['time2']               = $time2;
		$this->data['key']                 = $key;

		$this->data['right']          = $this->session->userdata('right');
		$this->data['name']           = $this->session->userdata('name');

		$this->ci_smarty->view('noLogin/plan_work',$this->data);
	}

	public function detail($id)
	{
		$like                    = array();
		$like['id']              = $id;
		$one                     = $this->plan_into_model->get_work($like);
		$this->data['plan_list'] = $one;
		$this->ci_smarty->view('noLogin/plan_work_detail',$this->data);
	}

	public function faultRepair()
	{
		$param                   = array();
		$like                    = array();

		$company    		 = $this->input->post('company')?$this->input->post('company'):$this->input->get('company');
		$time1               = $this->input->post('time1')?$this->input->post('time1'):$this->input->get('time1');
		$time2               = $this->input->post('time2')?$this->input->post('time2'):$this->input->get('time2');
		$key    		     = $this->input->post('key')?$this->input->post('key'):$this->input->get('key');
		
		if ($company&&$company!="全部") {
			$param['company']  = $company;
		}
		
		
		if ($key) {
			$like['task'] = $key;
			$like['facility'] = $key;
		}
		
		$page_start 				= $this->input->get('per_page');
		$per_page 					= 11;
		if (!$page_start) {
			$page_start = 0;
		}

		$plan_list  = $this->plan_into_model->get_work_list($param,'*',$page_start,$per_page,'','',$like);
		$list = array();
		$i=0;
		if ($time1&&empty($time2)) {
			foreach ($plan_list as $p ) {
				if ($p['plan_start_time']>=$time1)
				{
					$list[$i++]=$p;
				}
			}
			$plan_list = $list;
		}

		elseif ($time2&&empty($time1)) {
			foreach ($plan_list as $p ) {
				if ($p['plan_start_time']<=$time2)
				{
					$list[$i++]=$p;
				}
			}
			$plan_list = $list;
		}

		elseif($time1&&$time2) {
			foreach ($plan_list as $p ) {
				if ($p['plan_start_time']<=$time2&&$p['plan_start_time']>=$time1)
				{
					$list[$i++]=$p;
				}
			}
			$plan_list = $list;
		}
		$this->load->library('pagination');
		$config['base_url']   = '/psls/login/faultRepair/?page=true?&company='."&time1=".$time1."&time2=".$time2."&key=".$key;
		if ($time1||$time2) {
			$config['total_rows'] = $i-1;
		}
		else
		{
			$config['total_rows'] = $this->plan_into_model->get_work_list_count($param,$like);
		}
		$config['per_page']   = $per_page; 
		$config['page_query_string'] = TRUE;
		$config['first_link'] = '首页';
		$config['last_link'] = '末页';
		$config['next_link'] = '下一页';
		$config['prev_link'] = '上一页';
		
		//$config['cur_tag_open'] = '当前页';
		//$config['display_pages'] = FALSE;
		$this->pagination->initialize($config);
		$this->data['page_link'] = $this->pagination->create_links();

		$this->data['plan_list']   = $plan_list;
		$this->data['count']       = $config['total_rows'];

		$this->data['company']             = $company;
		
		$this->data['time1']               = $time1;
		$this->data['time2']               = $time2;
		$this->data['key']                 = $key;

		$this->data['right']          = $this->session->userdata('right');
		$this->data['name']           = $this->session->userdata('name');
		$this->ci_smarty->view('noLogin/fault_repair',$this->data);
	}

	public function temporaryWork()
	{
		$param                   = array();
		$like                    = array();

		$company    		 = $this->input->post('company')?$this->input->post('company'):$this->input->get('company');
		$time1               = $this->input->post('time1')?$this->input->post('time1'):$this->input->get('time1');
		$time2               = $this->input->post('time2')?$this->input->post('time2'):$this->input->get('time2');
		$key    		     = $this->input->post('key')?$this->input->post('key'):$this->input->get('key');
		
		if ($company&&$company!="全部") {
			$param['company']  = $company;
		}
		
		
		if ($key) {
			$like['task'] = $key;
			$like['facility'] = $key;
		}
		
		$page_start 				= $this->input->get('per_page');
		$per_page 					= 11;
		if (!$page_start) {
			$page_start = 0;
		}

		$plan_list  = $this->plan_into_model->get_work_list($param,'*',$page_start,$per_page,'','',$like);
		$list = array();
		$i=0;
		if ($time1&&empty($time2)) {
			foreach ($plan_list as $p ) {
				if ($p['plan_start_time']>=$time1)
				{
					$list[$i++]=$p;
				}
			}
			$plan_list = $list;
		}

		elseif ($time2&&empty($time1)) {
			foreach ($plan_list as $p ) {
				if ($p['plan_start_time']<=$time2)
				{
					$list[$i++]=$p;
				}
			}
			$plan_list = $list;
		}

		elseif($time1&&$time2) {
			foreach ($plan_list as $p ) {
				if ($p['plan_start_time']<=$time2&&$p['plan_start_time']>=$time1)
				{
					$list[$i++]=$p;
				}
			}
			$plan_list = $list;
		}
		$this->load->library('pagination');
		$config['base_url']   = '/family/login/temporaryWork/?page=true?&company='."&time1=".$time1."&time2=".$time2."&key=".$key;
		if ($time1||$time2) {
			$config['total_rows'] = $i-1;
		}
		else
		{
			$config['total_rows'] = $this->plan_into_model->get_work_list_count($param,$like);
		}
		$config['per_page']   = $per_page; 
		$config['page_query_string'] = TRUE;
		$config['first_link'] = '首页';
		$config['last_link'] = '末页';
		$config['next_link'] = '下一页';
		$config['prev_link'] = '上一页';
		
		//$config['cur_tag_open'] = '当前页';
		//$config['display_pages'] = FALSE;
		$this->pagination->initialize($config);
		$this->data['page_link'] = $this->pagination->create_links();

		$this->data['plan_list']   = $plan_list;
		$this->data['count']       = $config['total_rows'];

		$this->data['company']             = $company;
		
		$this->data['time1']               = $time1;
		$this->data['time2']               = $time2;
		$this->data['key']                 = $key;

		$this->data['right']          = $this->session->userdata('right');
		$this->data['name']           = $this->session->userdata('name');
		$this->ci_smarty->view('noLogin/temporary_work',$this->data);
	}

	public function qualification()
	{
		$param                   = array();
		$like                    = array();
		$unit    		     = $this->input->post('unit')?$this->input->post('unit'):$this->input->get('unit');
		$name    		     = $this->input->post('name')?$this->input->post('name'):$this->input->get('name');
		$key    		     = $this->input->post('key')?$this->input->post('key'):$this->input->get('key');

		
		if ($unit) {
			$like['unit'] = $unit;
		}
		if ($name) {
			$like['name'] = $name;
		}
		$page_start 				= $this->input->get('per_page');
		$per_page 					= 16;
		if (!$page_start) {
			$page_start = 0;
		}
		
		$qualification_list  = $this->qualification_model->get_list($param,'*',$page_start,$per_page,'','',$like);
		
		$this->load->library('pagination');
		$config['base_url']   = '/psls/qualification_manage/qualification_c/?page=true?&key='.$key.'&name='.$name.'&unit='.$unit;
		
		$config['total_rows'] = $this->qualification_model->get_list_count($param,$like);
		
		$config['per_page']   = $per_page; 
		$config['page_query_string'] = TRUE;
		$config['first_link'] = '首页';
		$config['last_link'] = '末页';
		$config['next_link'] = '下一页';
		$config['prev_link'] = '上一页';
		
		//$config['cur_tag_open'] = '当前页';
		//$config['display_pages'] = FALSE;
		$this->pagination->initialize($config);
		$this->data['page_link'] = $this->pagination->create_links();

		

		$this->data['qualification_list']      = $qualification_list;
		$this->data['count']       = $config['total_rows'];

		$this->ci_smarty->view('noLogin/qualification',$this->data);
	}

	public function getUserAjax()
	{

	 	$department_info_id =$_POST['department'];
	 	$sql        = "select * from tt_user where department_info_id='".$department_info_id."'";
	 	$result     = $this->user_model->getQuery($sql);	
	 	for($i=0;$i<count($result);$i++){
	 		 echo "<option value='".$result[$i]['account']."'>".$result[$i]['name']."</option>";
	 	}//是将username的数据项全部打印出来
	}
	
	public function devMode(){
		$this->ci_smarty->view('noLogin/dev',$this->data);
	}
}	