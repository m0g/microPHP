<?php
class Localization {
	/*==========================================================================
		Initialize website current language & PHP gettext
	==========================================================================*/
	public function __construct() {
		global $languages, $session;

		$merged_languages = null;
		foreach ($languages as $lang) $merged_languages[] = $lang['locale'];
		if (preg_match('/utf8$/', $session->get('language.locale')))
			$session->set('language.locale', substr($session->get('language.locale'), 0, -5));

		reset($languages);
		$locale = $languages[key($languages)]['locale'];
		

		if (
			$session->get('language.locale') 
			&& in_array($session->get('language.locale'), $merged_languages)
		) 
			$locale = $session->get('language.locale');
		else if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])){
			$lang = explode(',', $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
			foreach ($languages as $key=>$language)
				if (preg_match('/^'.$key.'/', $lang[0])) $locale = $language['locale'];
		}

		$domain = 'default';

		// Hack for Ubuntu
		$locale .= '.utf8';

		putenv('LC_ALL='.$locale);
		putenv('LANGUAGE='.$locale);
		putenv('LANG='.$locale);
		putenv('LC_ALL='.$locale);
		putenv('LC_MESSAGES='.$locale);

		$locale = setlocale(LC_ALL, $locale);

		$session->set('language.locale', $locale);
		$session->set('language.id', $languages[substr($locale, 0, 2)]['id']);

		// Specify location of translation tables
		$path = bindtextdomain($domain, LOCALE_PATH);
		bind_textdomain_codeset($domain, 'UTF-8');

		// Choose domain
		$po = textdomain("default");
	}
}