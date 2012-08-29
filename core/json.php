<?php
class Json {
	/*==========================================================================
		Parse & return the given project
	==========================================================================*/
	static public function parse ($id, $type = 'post') {
		global $session;
		
		$filename = JSONS_PATH.$type.'-'.$id.'-'.$session->get('language.id').'.json';
		
		if (!file_exists($filename)) return false;
		return json_decode(
			fread(fopen($filename, 'r'), filesize($filename))
		);
	}

	/*==========================================================================
		Parse & return all the project according to the pagination
	==========================================================================*/
	static public function parseAll ($page = 1, $nb = 2) {
		global $session;
		$data = null;
		$page = (int) $page;
		$nb = (int) $nb;

		$homeStruct = json_decode(
			fread(fopen(JSONS_PATH.'home.json', 'r'), filesize(JSONS_PATH.'home.json'))
		);

		for ($i = (($page*$nb)-$nb); $i < ($page*$nb); $i++){
			if (!isset($homeStruct->posts[$i])) break;
			$json = JSONS_PATH.'post-'.$homeStruct->posts[$i]->id.'-'.$session->get('language.id').'.json';
			
// die (var_dump($json));

			if (file_exists($json))
				$data[] = json_decode(
					fread(fopen($json, 'r'), filesize($json))
				);
		}

		$homeStruct->posts = $data;
		return $homeStruct;
	}

	/*==========================================================================
		Parse & return all the search results according to the pagination
	==========================================================================*/
	static public function searchParseAll ($search, $page = 1, $nb = 2) {
		global $session;
		$data = null;
		$page = (int) $page;
		$nb = (int) $nb;

		for ($i = (($page*$nb)-$nb); $i < ($page*$nb); $i++){
			if (!isset($search[$i])) break;
			$jsons[] = $json = JSONS_PATH.'project-'.$search[$i].'-'.$session->get('language.id').'.json';

			if (file_exists($json))
				$data[] = json_decode(
					fread(fopen($json, 'r'), filesize($json))
				);
		}

		$homeStruct->projects = $data;
		$homeStruct->searchJson = json_encode($search);

		return $homeStruct;
	}

	static public function parseFromSlug ($slug) {
		$slugsList = json_decode(
			fread(
				fopen(JSONS_PATH.'slugs.json', 'r'), 
				filesize(JSONS_PATH.'slugs.json')
			)
		);

		return Json::parse($slugsList->{$slug});
	}

	static public function getSlug ($id) {
		$slugsList = json_decode(
			fread(
				fopen(JSONS_PATH.'slugs.json', 'r'), 
				filesize(JSONS_PATH.'slugs.json')
			)
		);
		foreach ($slugsList as $slug=>$sId) if ($id == $sId) return $slug;
		return false;
	}
}