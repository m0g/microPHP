<?php	
class ExposeController {
	var $model = 'Expose';
	
	public function __construct () {
		$this->model = new $this->model(); 
	}

	public function GET($params) {
		global $template;

		if (isset($params[1]) && !empty($params[1])){
			$slug = $this->model->getSlug($params[1]);
			header('location: '.EXPOSE_URL.$slug);
			/*$template->set($this->model->get($params[1]));
			$template->render ('expose');*/
		} else {
			$template->render('404');
		}
	}
}