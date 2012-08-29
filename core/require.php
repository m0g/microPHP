<?php
/*==========================================================================
	Include routes file
==========================================================================*/
// require_once (CONFIG_PATH.'routes.php');

/*==========================================================================
	Include languages file
==========================================================================*/
// require_once (CONFIG_PATH.'languages.php');

/*==========================================================================
	Include core files
==========================================================================*/
require_once (CORE_PATH.'session.php');
require_once (CORE_PATH.'glue.php');
require_once (CORE_PATH.'template.php');
require_once (CORE_PATH.'localization.php');
require_once (CORE_PATH.'json.php');

/*==========================================================================
	Include all the controllers
==========================================================================*/
foreach (scandir(CONTROLLERS_PATH) as $controller)
	if (substr($controller, -3) == 'php') 
		require_once CONTROLLERS_PATH.$controller;

/*==========================================================================
	Include all the classes
==========================================================================*/
foreach (scandir(MODELS_PATH) as $class)
	if (substr($class, -3) == 'php') 
		require_once MODELS_PATH.$class;