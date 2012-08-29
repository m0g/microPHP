<?php	
class ExposesController {
	var $name = 'Exposes';
	
	public function __construct () {
		$this->model = new $this->name(); 
	}

	public function GET($params) {
		global $template;

		$this->id = $params[1]; 
		
		$template->set(array('id' => $this->id));
		$template->render ('expose');
	}
}