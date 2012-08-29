<?php	
class IndexController {
	var $model = 'Post';
	
	public function __construct () {
		$this->model = new $this->model(); 
	}

	function GET() {
		global $template, $session;

		$data = $this->model->getAll();

		$template->set($data);
		$template->render ('index');
	}
}