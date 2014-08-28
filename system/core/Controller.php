<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst.  It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * variable used to pass the data to view
	 *
	 * @var	object
	 */
	var  $data = array();

	/**
	 *  layout controller
	 *
	 * @var	string
	 */
	var  $layout = "application";

	/**
	 *  ajax methods
	 *
	 * @var	string
	 */
	var  $ajax = array();

	/**
	 * remap function
	 *
	 * @return	void
	 */
	public function _remap($method, $params = array())
	{
		
	    if (method_exists($this, $method)){


	        call_user_func_array(array($this, $method), $params);
	   
	    }
	    else{

	    	show_404();
	    }
	    
	}


	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		$mod_path = APPPATH . 'models/';
		if(file_exists($mod_path)) $this->_read_model_dir($mod_path);
		$this->setLayout($this->layout);
		if (!in_array($this->router->method, $this->ajax)) $this->view(strtolower($this->router->class)."/".$this->router->method, $this->data);
			
		log_message('debug', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
	 *  Open model directories recursively and load the models inside
	 *@params dirpath
	 *dirpath:	model path 
	 */
	private function _read_model_dir($dirpath)
	{
		$ci =& get_instance();

		$handle = opendir($dirpath);
		if(!$handle) return;

		while (false !== ($filename = readdir($handle)))
		{
			if($filename == "." or $filename == "..") continue;

			$filepath = $dirpath.$filename;
			if(is_dir($filepath))
				$this->_read_model_dir($filepath);

			elseif(strpos(strtolower($filename), '.php') !== false)
			{
				$name = strtolower($filepath);
				require_once $name;
			}

			else continue;
		}

		closedir($handle);
	}

	/**
	 *	set the layout controller
	 *@params layout
	 *layout:	the layout name
	 */
	private function setLayout($layout)
    {
      $this->layout = $layout;
    }


    /**
	 *	load the view
	 *@params view
	 *view:	the view name
	 */
    private function view($view, $data=null, $return=false)
    {
        $loadedData = array();
        $loadedData['yield'] = $this->load->view($view,$data,true);

        if($return)
        {
            $output = $this->load->view("layouts/".$this->layout, $loadedData, true);
            return $output;
        }
        else
        {
            $this->load->view("layouts/".$this->layout, $loadedData, false);
        }
    }

}

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */