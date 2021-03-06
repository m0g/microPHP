<?php
require_once ('../config/params.php');
require_once ('../config/core.php');
require_once (CORE_PATH.'require.php');

/*==============================================================================
	Init Session
==============================================================================*/
$session = new Session();

/*==============================================================================
	Init Localization
==============================================================================*/
$localization = new Localization();

/*==============================================================================
	Init Template
==============================================================================*/
$template = new Template();

/*==============================================================================
    Front controller
==============================================================================*/
glue::stick($urls);