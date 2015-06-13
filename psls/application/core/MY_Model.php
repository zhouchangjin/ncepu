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
class MY_Model extends CI_Model
{

	protected $table_name;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function getTableMetadata(){
		
		$query=$this->db->query("select * from information_schema.tables where table_name='".$this->table_name."' and table_schema='psls'");
		$res=$query->result();
		$table_cmt=$res[0]->TABLE_COMMENT;
		return $table_cmt;
	}
	public function getDictionary(){
		$fields = $this->db->field_data($this->table_name);
		$meta=array();
		foreach ($fields as $field)
		{
			$query=$this->db->query("select * from information_schema.columns where table_schema='psls' and table_name='".$this->table_name."' and column_name='".$field->name."'");
			$res=$query->result();
			$field->comment=$res[0]->COLUMN_COMMENT;
			$field->dictionary=array();
			$query=$this->db->query("select * from dictionary where table_name='".$this->table_name."' and column_name='".$field->name."'");
			$res=$query->result();
			for($i=0;$i<sizeof($res);$i++){
				$record=$res[$i];
				$type=$record->type;
				$code=$record->$type;
				$field->dictionary[$code]=$record;
			}
			$meta[$field->name]=$field;
		}
		return $meta;
	}
	public function getFieldsMetadata(){
		$fields = $this->db->field_data($this->table_name);
		$meta=array();
		foreach ($fields as $field)
		{
			$query=$this->db->query("select * from information_schema.columns where table_schema='psls' and table_name='".$this->table_name."' and column_name='".$field->name."'");
			$res=$query->result();
			$field->comment=$res[0]->COLUMN_COMMENT;
			$field->dictionary=0;
			$query=$this->db->query("select * from dictionary where table_name='".$this->table_name."' and column_name='".$field->name."'");
			$field->dictionary=sizeof($query->result());
			array_push($meta,$field);
		}
		return $meta;
	}

	/** 
	 * 新增数据
	 *
	 * @param 	array 	$data
	 * @return 	int
	 */
	public function insert($data)
	{
		if ( ! isset($data['cdate'])) {
			$data['cdate'] = date('Y-m-d H:i:s');
		}
		$this->db->insert($this->table_name, $data);
		$id = $this->db->insert_id();
		if ($id) {
			return $id;
		} else {
			return $this->db->affected_rows();
		}
	}
	public function insert2($data)
	{		
		$this->db->insert($this->table_name, $data);
		$id = $this->db->insert_id();
		if ($id) {
			return $id;
		} else {
			return $this->db->affected_rows();
		}
	}

	/** 
	 * 批量新增数据 (一般用于数据导入)
	 * 传入二维数组
	 *
	 * @param 	array 	$data
	 * @return 	bool
	 */
	public function insert_batch($data)
	{
		return $bool = $this->db->insert_batch($this->table_name, $data);
	}

	/**
	 * 更新数据
	 * 传入单个id, 或者一个数组条件
	 *
	 * @param 	array 	$data
	 * @param 	fixed 	$id
	 * @return 	bool
	 */
	public function update($data, $id, $idcol='id')
	{

		/*if ( ! isset($data['edate'])) {			
			$data['edate'] = date('Y-m-d H:i:s');

		}*/

		if (is_array($id)) {

			$this->db->where($id, null, false);
		} else {
			$this->db->where($idcol, $id);
		}
		return $bool = $this->db->update($this->table_name, $data);
	}



	/**
	 * 删除数据, $real true 时 真删除
	 *
	 * @param 	fixed 	$id
	 * @param 	bool 	$real
	 * @return 	bool
	 */
	public function delete($id, $real = true, $idcol='id')
	{
		if ($real){
			return $bool = $this->_realDelete($id,$idcol);
		} else {
			$data = array(
				'status' => '-1',
				'edate'  =>	date('Y-m-d H:i:s')
			);
			return $bool = $this->update($data, $id, $idcol);
		}
	}

	/**
	 * 删除数据 (真删除)
	 *     注：此方法变更为私有方法，请调用 del($id, true) 实现同功能
	 *
	 * @param 	fixed 	$id
	 * @return 	int
	 */
	private function _realDelete($id, $idcol='id')
	{
		if (is_array($id)) {
			$this->db->where($id, null, false);
		} else {
			$this->db->where($idcol, $id);
		}
		return $bool = $this->db->delete($this->table_name);
	}

	/**
	 * 查询单个数据
	 *
	 * @param 	fixed 	$params
	 * @param 	string 	$fields
	 * @return 	array
	 */
	public function getOne($params = array(), $fields = '*')
	{		
		$q = $this->db->select($fields, false)->where($params)->get($this->table_name);
		return $row = $q->row_array();
	}
	
	public function getOneRel($params = array(),$jointcondition=array(),$fields='*'){
		
		$q = $this->db->select($fields, false);		
		for($i=0;$i<sizeof($jointcondition);$i++){
			$tablename=$jointcondition[$i]['table'];
			$condition=$jointcondition[$i]['condition'];
			$type=$jointcondition[$i]['type'];
			$q=$q->join($tablename,$condition,$type);
		}
		$q=$q->where($params)->get($this->table_name);
		return $row = $q->row_array();
	}

	/**
	 * 查询记录条数
	 *
	 * @param 	fixed 	$params
	 * @param 	array 	$like
	 * @return 	int
	 */
	public function getCount($params = array(), $like = array())
	{
		//echo "MyModel".$params;
		if($params)
		return $count = $this->db->where($params)->or_like($like)->from($this->table_name)
								 ->count_all_results();
		else 
			return $count=$this->db->or_like($like)->from($this->table_name)
							 ->count_all_results();
	}
	public function getCountRel($joincondition=array(),$params = array(), $like = array())
	{
		$q=$this->db;
		for($i=0;$i<sizeof($joincondition);$i++){
			$tablename=$joincondition[$i]['table'];
			$condition=$joincondition[$i]['condition'];
			$type=$joincondition[$i]['type'];
			$q=$q->join($tablename,$condition,$type);
		}
		$count=$q->where($params)->or_like($like)->from($this->table_name)
		->count_all_results();
		return $count;
	}
	public function getListRel($params = array(), $fields = '*', $start = 0, $perpage = 0, 
							$order 	= '', $sort = '', $like = array(), $group = array(),$joincondition=array()){
		if ($perpage) {
			$this->db->limit($perpage, $start);
		}
		if ($order && $sort) {
			$this->db->order_by($order, $sort);
		}
		
		if (!empty($group) && count($group) > 0) {
			$this->db->group_by($group);
		}
		$q = $this->db->select($fields, false);
		for($i=0;$i<sizeof($joincondition);$i++){
			$tablename=$joincondition[$i]['table'];//用于左连接的表明
			$condition=$joincondition[$i]['condition'];//连接条件（哪两个属性值相等）
			$type=$joincondition[$i]['type'];//连接类型（左连接还是右连接）
			$q=$q->join($tablename,$condition,$type);
		}
		$q =$q->where($params)->or_like($like)->get($this->table_name);
		
		return $q->result_array();
	}

	/**
	 * 查询列表
	 *
	 * @param 	array 	$params
	 * @param 	string 	$data
	 * @param 	int 	$start
	 * @param 	int 	$perpage
	 * @param 	string 	$order
	 * @param 	string 	$sort
	 * @param 	array 	$like
	 * @return 	array
	 */
	public function getList($params = array(), $fields = '*', $start = 0, $perpage = 0, 
							$order 	= '', $sort = '', $like = array(), $group = array())
	{
		if ($perpage) {
			$this->db->limit($perpage, $start);
		}
		if ($order && $sort) {
			$this->db->order_by($order, $sort);
		}
		
		if (!empty($group) && count($group) > 0) {
			$this->db->group_by($group);
		}		
		$q = $this->db->select($fields, false)->where($params)->or_like($like)->get($this->table_name);
		
		return $list = $q->result_array();//这个应该是通过select的字段查找结果的函数
	}

	/**
	 * 模糊查询
	 *     注：此版本变更为通用方法，转调List方法
	 *
	 * @param 	array 	$like
	 * @param 	array 	$params
	 * @param 	string 	$data
	 * @param 	int 	$start
	 * @param 	int 	$perpage
	 * @param 	string 	$order
	 * @param 	string 	$sort
	 * @return 	array 	array
	 */
	public function getLike($like = array(), $params = array(), $fields = '*', 
							$start = 0, $perpage = 0, $order = '', $sort = '')
	{
		return $list = $this->getList($params, $fields, $start, $perpage, $order, $sort, $like);
	}

	/**
	 * In 查询
	 *
	 * @param 	string 	$inField
	 * @param 	array 	$inArray
	 * @param 	array 	$params
	 * @param 	string 	$data
	 * @param 	int 	$start
	 * @param 	int 	$perpage
	 * @param 	string 	$order
	 * @param 	string 	$sort
	 * @return 	array 	array
	 */
	public function getIn($inField, $inArray, $params = array(), $fields = '*', 
						  $start = 0, $perpage = 0, $order = '', $sort = '')
	{
		if ( empty($inArray) ) {
			return array();
		}
		$inString = implode(',', $inArray);//这个是连接字符串
		//$params["$inField in ($inString)"] = null;
		return $list = $this->getList($params, $fields, $start, $perpage, $order, $sort);
	}

	/**
	 * 查询In记录条数
	 *
	 * @param 	string 	$inField
	 * @param 	array 	$inArray
	 * @param 	array 	$params
	 * @return 	int
	 */
	public function getInCount($inField, $inArray, $params = array())
	{
		if ( empty($inArray) ) {
			return 0;
		}
		$list = $this->getIn($inField, $inArray, $params, $fields=' count(*) as num ');
		if (isset($list[0]['num'])) {
			return $count = intval($list[0]['num']);
		} else {
			return 0;
		}
	}

	/**
	 * 组定义 SQL 查询
	 *
	 * @param string $sql
	 * @return array
	 */
	public function getQuery($sql)
	{
		return $list = $this->db->query($sql)->result_array();
	}

	/***************IN查询函数**************
	$params              用于指示哪一个属性是要做in查询
	$fields              指示要查询那些属性（列）
	$paramsIn            指示IN查询语句IN后面的括号中的元素
	*/
	public function getListIn($params = '', $fields = '*', $paramsIn = array(), $start = 0, $perpage = 0, //in查询
							$order 	= '', $sort = '', $like = array(), $group = array())//in查询
	{
		if ($perpage) {
			$this->db->limit($perpage, $start);
		}
		if ($order && $sort) {
			$this->db->order_by($order, $sort);
		}
		
		if (!empty($group) && count($group) > 0) {
			$this->db->group_by($group);
		}		

        $q = $this->db->select($fields, false)->where_in($params,$paramsIn)->or_like($like)->get($this->table_name);

		return $list = $q->result_array();//这个应该是通过select的字段查找结果的函数
	}
}



/* End of file: MY_Model.php */