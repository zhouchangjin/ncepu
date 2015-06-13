<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Xinwen_m extends MY_Model {

	function __construct(){
		parent::__construct();	
		$this->table_name = 'xinwen';
	}
	
	public function get_one($param)
	{
		$one = $this->getOne($param);
		return $one;
	}
	
	//
	public function get_list($params = array(), $fields = '*', $start = 0, $perpage = 0, $order = '', $sort = '', $like = array(), $group = array())
	{
		$list = $this->getList($params , $fields, $start, $perpage, $order, $sort, $like , $group);
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
		return $this->delete($id,true);
	}

	//更新数据
	public function update_new($param,$id)
	{

		return $this->update($param,$id);
	}
	
	//多表查询
	public function get_list2($jointcondition=array(),$params = array(), $fields = '*', $start = 0, $perpage = 0, $order = '', $sort = '', $like = array(), $group = array())
	{
		$list = $this->getListRel($params , $fields, $start, $perpage, $order, $sort, $like , $group,$jointcondition);
		return $list;
	}
	//多表查询
	public function get_list_count2($jointcondition=array(),$params = array(), $like = array())
	{
		return $this->getCountRel($jointcondition,$params,$like);
	}
	
	
} 