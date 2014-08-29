<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ci extends CI_Controller {

	var $controllers_path = "application/controllers/";
	var $models_path = "application/models/";
	var $views_path = "application/views/";
	var $ajax = array('generate','delte','index');

	function __construct()
	{
		parent::__construct();
		$this->load->helper('inflector');
	}


	function generate()
	{
		$action = $this->_getAtrributes($this->uri->segment_array());
		if ($action) {
			
			switch ($action["type"]) {
				case "controller":

					if (array_key_exists("functions", $action)) {
						
						$this->controller($action["name"] , $action["functions"] );
					}
					else{
						
						$this->controller($action["name"]);
					}
					

					break;
				
				case "model":
					if (array_key_exists("functions", $action)) {
						
						$this->model($action["name"] , $action["functions"] );
					}
					else{
						
						$this->model($action["name"]);
					}
					break;


				case "scaffold":
					echo "generate scaffold";
					break;
			}

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
		$class = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\nclass  $name extends CI_Controller {\n\n\n";
		if ($params) {
			foreach ($params as $key => $function) {
				$class.="\tfunction $function() \n\t{ \n\t}\n\n";
			}
		}
		
		$class.="}\n/* End of file ci.php */\n/* Location: ./application/controllers/$name.php */";

		if (!file_exists(FCPATH.$this->controllers_path.$name.".php")) {
			$controller = fopen(FCPATH.$this->controllers_path.$name.".php", "w");
			fwrite($controller, $class);
			fclose($controller);
		}

		$this->views($name , $params);

	}



	private function views($controller , $params = array())
	{
		mkdir(FCPATH.$this->views_path.$controller);
		if ($params) {
			foreach ($params as $key => $view) {
				$viewFile = fopen(FCPATH.$this->views_path.$controller."/$view.php", "w");
				fclose($viewFile);
			}
		}
	}


	private function model($name=null , $params = array())
	{
		$class = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\nclass  $name extends Elegant\Model {\n\n\n";
		$class.="}\n/* End of file ci.php */\n/* Location: ./application/models/$name.php */";
		if (!file_exists(FCPATH.$this->models_path.$name.".php")) {
			
			$controller = fopen(FCPATH.$this->models_path.$name.".php", "w");
			fwrite($controller, $class);
			fclose($controller);
		}

		//Create the Database
		$this->load->dbforge();
		$fields["id"] = array(
					'type'=>'int',
					'constraint'=>11,
					'auto_increment' => TRUE
				);
		$this->dbforge->add_key('id', TRUE);
		if ($params) {
			
			foreach ($params as $key => $field) {
				$field = explode(":", $field);

				$fields[$field[0]] = array(
					
					'type'=> (count($field) >=2)?$field[1]:"VARCHAR",
					'constraint'=>(count($field) >=2 && $field[1]=="int")?"11":"250"

				
				); 
			}

				

		}
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table(plural($name) , TRUE);
		
		
		
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

			//Show Error
			
		}
		return $structure;
		
	}


}

/* End of file ci.php */
/* Location: ./application/controllers/ci.php */