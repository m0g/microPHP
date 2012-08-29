<?php	
class ExposeSlugController {
	var $model = 'Expose';
	
	public function __construct () {
		$this->model = new $this->model(); 
	}

	public function GET($params) {
		global $template;

		if (isset($params[1]) && isset($params[2]) && !empty($params[1]) && !empty($params[2])){
			$template->set($this->model->getFromSlug($params[1].'/'.$params[2]));
			$template->render ('expose');
		} else {
			$template->render('404');
		}
	}
}