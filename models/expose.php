<?php
class Expose {
	/*==========================================================================
		Get the expose for the given ID
	==========================================================================*/
	public function get ($id) {
		return Json::parse($id);
	}

	public function getSlug ($id) {
		return Json::getSlug($id);
	}

	public function getFromSlug ($slug) {
		return Json::parseFromSlug ($slug);
	}

	/*==========================================================================
		Get all the exposes according to the pagination
	==========================================================================*/
	public function getAll ($page = 1, $nb = 2) {
		return Json::parseAll($page, $nb);
	}

	/*==========================================================================
		Get all the search results according to the pagination
	==========================================================================*/
	public function getSearchAll ($search, $page = 1, $nb = 2) {
		return Json::searchParseAll($search, $page, $nb);
	}
}