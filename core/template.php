<?php
class Template {
	var $data = array();

	/*==========================================================================
		Template var
	==========================================================================*/
	public function set ($var) {
		// if (is_array($array)) $this->data = $array;
		if (isset($var) && !empty($var)) $this->data = $var;
	}

	/*==========================================================================
		Render desired template
	==========================================================================*/
	public function render ($tpl, $withHeader = true) {
		global $session;
		if (!file_exists(VIEWS_PATH.$tpl.'.php')) $tpl = '404'; 

		ob_start("callback");

		if ($withHeader) require_once (VIEWS_PATH.'header.php');
		require_once (VIEWS_PATH.$tpl.'.php');
		if ($withHeader) require_once (VIEWS_PATH.'footer.php');

		$html = ob_get_contents();
		ob_end_clean();
		echo $html;
	}
}