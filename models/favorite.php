<?php
class Favorite {
	public function getListFromUrl ($url) {
		$return = false;

		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => API_URL.'?function=projectlistGetUrl&url='.$url,
			CURLOPT_HTTPHEADER => array('Content-type: application/json'),
			CURLOPT_RETURNTRANSFER => 1			
		));

		if ($result = substr(curl_exec($ch), 3)){
			$temp = json_decode($result);
			// Check if no error
			if ($temp->projectlistGetUrl->errorCode == '100') 
				$return = $temp->projectlistGetUrl->data;
		}
		curl_close($ch);
		return $return;
	}

	public function getList ($cookie) {
		$return = false;

		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => API_URL.'?function=projectlistGet&cookie='.$cookie,
			CURLOPT_HTTPHEADER => array('Content-type: application/json'),
			CURLOPT_RETURNTRANSFER => 1			
		));

		if ($result = substr(curl_exec($ch), 3)){
			$temp = json_decode($result);
			// Check if no error
			if ($temp->projectlistGet->errorCode == '100') 
				$return = $temp->projectlistGet->data;
		}
		curl_close($ch);



		return $return;
	}

	public function add ($cookie, $id) {
		$return = false;
		$params = 'cookie='.$cookie.'&pId='.$id;
		
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => API_URL.'?function=projectlistAdd&'.$params,
			CURLOPT_HTTPHEADER => array('Content-type: application/json'),
			CURLOPT_RETURNTRANSFER => 1			
		));

		if ($result = substr(curl_exec($ch), 3)){
			$temp = json_decode($result);
			// Check if no error
			if ($temp->projectlistAdd->errorCode == '100') 
				$return = true;
		}
		curl_close($ch);
		return $return;
	}

	public function remove ($cookie, $id) {
		$return = false;
		$params = 'cookie='.$cookie.'&pId='.$id;

		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => API_URL.'?function=projectlistDelete&'.$params,
			CURLOPT_HTTPHEADER => array('Content-type: application/json'),
			CURLOPT_RETURNTRANSFER => 1			
		));

		if ($result = substr(curl_exec($ch), 3)){
			$temp = json_decode($result);
			// Check if no error
			if ($temp->projectlistDelete->errorCode == '100') 
				$return = true;
		}
		curl_close($ch);

		if ($return && isset($_SESSION['favorites']))
			foreach ($_SESSION['favorites']->projects as $key=>$project){
				$project = (array) $project;
				if ((int) $project['pId'] == $id)
					unset($_SESSION['favorites']->projects[$key]);
			}

 
		return $return; 
	}
}