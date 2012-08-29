<?php	
class LanguagesController {
	
	function GET($params) {
		global $languages, $session;

		if (isset($languages[$params[1]]) && !empty($languages[$params[1]])){
			// $session->set('language', $languages[$params[1]]);
			$session->set('language.locale', $languages[$params[1]]['locale']);
			$session->set('language.id', $languages[$params[1]]['id']);
			// die (var_dump($params));

// die (var_dump($session->get('language.locale')));

			header( 'Location: '.SITE_URL.$params[2] ) ;
		}	
	}
}