<?php	
class PagesController {
	function GET($page) {
		global $template;
		$template->render ('static-'.$page[1]);
		// $template->render ('404');
	}
}