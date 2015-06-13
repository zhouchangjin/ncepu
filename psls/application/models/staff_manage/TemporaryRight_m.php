<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TemporaryRight_m extends MY_Model {

	function __construct(){
		parent::__construct();	
		$this->table_name = 'temporary_right';
	}	
	public function addItem($param)
	{	
		$this->db->insert($this->table_name, $param);
		$id = $this->db->insert_id();
		return $id;
	}
		
}