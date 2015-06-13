<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 *******************************************************************************
 * DB 操作原型类
 * Model文件继承此类，即可使用通用DAO方法
 * 继承类请在构造函数最后加上：
 *     $this->table_name = '?';
 *******************************************************************************
 * @version 0.1
 * @athor xudb
 */
class MY_Controller extends CI_Controller
{
	public function __construct()
  	{
    	parent::__construct();
	    $this->load->library('ci_smarty');
	    $this->data = array();
	    $this->load->helper('url');
	    $this->load->library('session');
    
  	}
 
	
}