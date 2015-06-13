
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class roleToRight_m extends MY_Model {

	function __construct(){
		parent::__construct();	
		$this->table_name = 'role_to_right';
	}
}