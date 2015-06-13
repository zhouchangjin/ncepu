<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class header_model extends MY_Model {

	function __construct(){
		parent::__construct();	
		$this->table_name = 'header';
	}
	public function get_one($param)
	{

		$one = $this->getOne($param);
		//echo $inbound_one;

		return $one;
	}
	//获取入库单列表：
	public function get_list($params = array(), $fields = '*', $start = 0, $perpage = 0, $order = '', $sort = '', $like = array(), $group = array())
	{
		global $approve_state;
		$list = $this->getList($params , $fields, $start, $perpage, $order, $sort, $like , $group);
		// var_dump($like);
		// foreach ($plan_list as $key => $value) {
		// 	$plan_list[$key]['budget'] = $value['budget'] /100;
		// 	$plan_list[$key]['approve_state_string'] = $approve_state[$value['approve_state']];
		// 	$plan_list[$key]['date'] = substr($value['date'], 0 , 10); 
		// }
		// var_dump($plan_list);
		return $list;
	}
	
    //获取数据的总行数
	public function get_list_count($params = array(),$like = array())
	{
		return $this->getCount($params,$like);
	}

	//插入一条数据
	public function add($param)
	{
		return $this->insert($param);
	}

	//批量导入数据
	public function add_batch($param)
	{
		return $this->insert_batch($param);
	}

    //删除数据
	public function del($id)
	{
		return $this->delete($id);
	}

	//更新数据
	public function update_new($param,$id)
	{

		return $this->update($param,$id);
	}
} 