<?php
class Company {
	/*==========================================================================
		Get one company from id
	==========================================================================*/
	public function get ($id) {
		return Json::parse($id, 'company');
		// return true;
	}

	/*==========================================================================
		Get all the companies
	==========================================================================*/
	public function getAll ($page = 1, $nb = 2) {
		return Json::parseAll($page, $nb);
	}
}