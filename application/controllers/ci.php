<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ci extends CI_Controller {

	var $controllers_path;
	var $model_path;
	var $ajax = array('generate','delte','index');

	function __construct()
	{
		parent::__construct();
	}


	function generate()
	{
		$action = $this->_getAtrributes($this->uri->segment_array());
		if ($action) {
			
			

		}
	}

	function delete()
	{

	}

	function index()
	{
		
	}


	private function controller($name=null , $params = array())
	{

	}

	private function model($name=null , $params = array())
	{

	}

	private function scaffold($name=null , $params = array())
	{

	}

	private function assets()
	{

	}

	private function csss()
	{

	}

	private function js()
	{

	}

	private function _getAtrributes($attributes = array())
	{
		$structure = array();
		if(count($attributes)>4) {
			
			$structure["type"] = $attributes[3];
			$structure["name"] = $attributes[4];

			for ($i=5; $i <= count($attributes) ; $i++) { 
				
				$structure["functions"][] = $attributes[$i]; 

			}
			
		}
		else if(count($attributes)>3){
			$structure["type"] = $attributes[3];
			$structure["name"] = $attributes[4];
		}

		else {

			
		}
		return $structure;
		
	}

}

/* End of file ci.php */
/* Location: ./application/controllers/ci.php */