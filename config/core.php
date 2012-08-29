<?php
/*==============================================================================
    Constant definition
==============================================================================*/

// Main directories
define ('ROOT_PATH', 			'../');
define ('CONFIG_PATH', 			ROOT_PATH.'config/');
define ('CORE_PATH', 			ROOT_PATH.'core/');
define ('VIEWS_PATH', 			ROOT_PATH.'views/');
define ('CONTROLLERS_PATH', 	ROOT_PATH.'controllers/');
define ('MODELS_PATH',          ROOT_PATH.'models/');
define ('LOCALE_PATH',          ROOT_PATH.'locale/');
define ('JSONS_PATH',           ROOT_PATH.'datas/');

// Tmp folders
/*define ('TMP_IMAGES',           ROOT_PATH.'www/uploads/img/');
define ('TMP_IMAGES_EXPOSE',    TMP_IMAGES.'expose/');
define ('TMP_IMAGES_EDITORIAL', TMP_IMAGES.'editorial/');
define ('TMP_IMAGES_COMPANY',   TMP_IMAGES.'company/');
*/
/*define ('TMP_JSONS',            ROOT_PATH.'tmp/jsons/');
define ('TMP_LOGS',             ROOT_PATH.'tmp/logs/');

define ('URL_IMAGES',           '/uploads/img/');
define ('URL_IMAGES_EXPOSE',    URL_IMAGES.'expose/');
define ('URL_IMAGES_EDITORIAL', URL_IMAGES.'editorial/');
define ('URL_IMAGES_COMPANY', 	URL_IMAGES.'company/');
*/
// Urls
define ('SITE_URL',             'http://'.$_SERVER['SERVER_NAME'].'/');
// define ('EXPOSE_URL', 			SITE_URL.'expose/');

define ('HASH',                 'a6c9b7f1f630');

/*define ('SLIDE_MIN_NB',         6);
define ('CURRENT_MAGAZINE',     4);

define ('MAX_COMPANY_IMG_Y',    115);
*/

/*==============================================================================
	List all available languages
==============================================================================*/
/*$languages = array(
    'en' => 'en_US',
	'de' => 'de_DE',
	'fr' => 'fr_FR'
);*/
// Only one language for now
$languages = array(
    'en' => array(
        'locale' => 'en_US', 
        'id' => (int) 1
    ),
    'de' => array(
        'locale' => 'de_DE', 
        'id' => (int) 2
    ),
    'fr' => array(
        'locale' => 'fr_FR', 
        'id' => (int) 3
    )
);

/*==============================================================================
    Image size
==============================================================================*/
/*$imageSize = array(
    'slide' => array('width' => 641, 'height' => 481), 
    'home' => array('width' => 454, 'height' => 341)
);*/
/*$imageSize = array( 
	1 => array(
		'slide' => array('width' => 641, 'height' => 481), 
		'home' => array('width' => 454, 'height' => 341)
	),
	11 => array(
		'regular' => array('width' => 100, 'height' => 100)
	)
);*/

/*==============================================================================
    Image src corresponding path
==============================================================================*/
/*$imageSrcPath = array(
    1 => TMP_IMAGES_EXPOSE,
    9 => TMP_IMAGES_COMPANY
);*/

/*==============================================================================
	Define the differents routes here
==============================================================================*/
$urlLangs = '';
foreach ($languages as $key=>$lang) $urlLangs .= $key.'|';
$urls = array(
    '/'                                         => 'IndexController',
    '/('.substr($urlLangs, 0, -1).')/(.*)'      => 'LanguagesController',
    // '/expose/([a-zA-Z0-9\-]+)/([a-zA-Z0-9\-]+)' => 'ExposeSlugController',
    '/post/([0-9]+)'                          => 'PostController',
    // '/company/([0-9]+)'                         => 'CompanyController',
    '/page/([0-9]+)'                            => 'PageController',
    // '/contact_form/([0-9]+)'                    => 'ContactFormController',
    // '/cron/(.*)'                                => 'CronController',
    // '/user/(.*)'                                => 'UserController',
    // '/search/(.*)'                              => 'SearchController',
    // '/favorite/([a-zA-Z0-9]+)/([a-zA-Z0-9]+)'   => 'FavoriteController',
    // '/favorite/(.*)'                            => 'FavoriteController',
    // '/activate/(.*)'                            => 'ActivateController',
    // '/ipad'                                     => 'IpadController',
    // '/signup'                                   => 'SignUpController',
    // '/offer'                                    => 'OfferController',
    '/(.+)'                                     => 'PagesController'
);

// unset all the useless var
unset($urlLangs, $lang, $key);

// reset the internal pointer (for avoiding future problems)
reset($languages);