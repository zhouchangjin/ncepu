<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'third_party/Smarty-3.1.11/libs/Smarty.class.php';
/**
 * Smarty Class
 *
 * Lets you use smarty engine in CodeIgniter.
 *
 * @package		Application
 * @subpackage	Libraries
 * @category	Customize Class
 * @author		sitearth
 * @link		blog.sitearth.com
 */
class CI_Smarty extends Smarty {

	var $CI;

	/**
	 * Smarty constructor
	 *
	 * The constructor runs the session routines automatically
	 * whenever the class is instantiated.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->CI =& get_instance();
		$this->template_dir = APPPATH . 'views/';
		$this->compile_dir = 'cache/templates_c/';
		//$this->config_dir = $CI->config->item('configs_dir');
		//$this->cache_dir = $CI->config->item('cache');
		//$this->caching = true;
		//$this->cache_lifetime = ;
		//$this->debugging = true;
		$this->left_delimiter = '{*';
		$this->right_delimiter = '*}';
	}

	// --------------------------------------------------------------------

	/**
	 * An encapsulation of display method in smarty class
	 *
	 * @access	public
	 * @param	string
	 * @param   mixed
	 * @return	void
	 */
	public function view($template_file, $assigns = array())
	{
		if (strpos($template_file, '.') === false)
		{
			$template_file .= '.html';
		}
		// var_dump($this->template_dir);
		if ( ! is_file($this->template_dir[0] . '/' . $template_file)) {
			show_error("Smarty error: {$template_file} cannot be found.");
		}

		if (is_array($assigns) && !empty($assigns))
		{
			foreach ($assigns as $key => $value)
				$this->assign($key, $value);
		}

		$this->display($template_file);
	}
	public function view_content($template_file, $assigns = array())
	{
		$this->view('header',$assigns);
	}
}
/* End of file Smarty.php */
/* Location: ./application/libraries/Smarty.php */
