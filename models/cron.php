<?php
class Cron {
	/*==========================================================================
		Get the HomeStruct (the main API file)
	==========================================================================*/
	public function getHomeStruct ($magazineID, $languageID) {
		$homeStruct = false;
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => API_URL.'?function=homeStruct&mId='.$magazineID.'&lId='.$languageID,
			CURLOPT_HTTPHEADER => array('Content-type: application/json'),
			CURLOPT_RETURNTRANSFER => 1			
		));
		
		if ($res = substr(curl_exec($ch), 3)){
			$temp = json_decode($res);
			// Check if no error
			if ($temp->homeStruct->errorCode == '100')
				$homeStruct = $temp->homeStruct->data;
		}

		curl_close($ch);
		return $homeStruct;
	}

	/*==========================================================================
		Get the available search parameters for the dropdown menus
	==========================================================================*/
	public function getSearchParameters ($magazineID, $languageID) {
		$searchParameters = false;
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => API_URL.'?function=searchParameters&mId='.$magazineID.'&lId='.$languageID,
			CURLOPT_HTTPHEADER => array('Content-type: application/json'),
			CURLOPT_RETURNTRANSFER => 1			
		));
		
		if ($res = substr(curl_exec($ch), 3)){
			$temp = json_decode($res);
			// Check if no error
			if ($temp->searchParameters->errorCode == '100')
				$searchParameters = $temp->searchParameters->data;
		}

		curl_close($ch);
		return $searchParameters;
	}

	/*==========================================================================
		Get one project by id & language
	==========================================================================*/
	public function getProject ($projectID, $languageID) {
		
		$project = false;
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => API_URL.'?function=projectStruct&pId='.$projectID.'&lId='.$languageID,
			CURLOPT_HTTPHEADER => array('Content-type: application/json'),
			CURLOPT_RETURNTRANSFER => 1			
		));
		// Check if something is returned
		if ($res = substr(curl_exec($ch), 3)){

			$temp = json_decode($res);
			// Check if no error
			if ($temp->webappProjectStruct->errorCode == '100'){
				// Add project ID
				$temp->webappProjectStruct->data->pId = $projectID;
				$project = $temp->webappProjectStruct->data;

				if (strpos($project->locationCity, '-'))
					list($project->locationCity, $project->locationDistrict)
						= explode('-', $project->locationCity);

				
				/*function toAscii($str, $replace=array(), $delimiter='-') {
					if( !empty($replace) ) {
						$str = str_replace((array)$replace, ' ', $str);
					}

					$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
					$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
					$clean = strtolower(trim($clean, '-'));
					$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

					return $clean;
				}*/

				// Generate the slug
				$company = iconv('UTF-8', 'ASCII//TRANSLIT', $project->companyName);
				$company = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $company);
				$company = strtolower(trim($company, '-'));
				$company = preg_replace("/[\/_|+ -]+/", '-', $company);

				$projectName = iconv('UTF-8', 'ASCII//TRANSLIT', $project->projectName);
				$projectName = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $projectName);
				$projectName = strtolower(trim($projectName, '-'));
				$projectName = preg_replace("/[\/_|+ -]+/", '-', $projectName);

				$project->slug = $company.'/'.$projectName;
				// $project->slug = 'blabla';
				// die (var_dump($project->slug));
			}
		}


		curl_close($ch);

// die (var_dump($project));

		return $project;
	}

	/*==========================================================================
		Retrieve one company & generate the corresponding JSON
	==========================================================================*/
	// public function generateCompany ($id, $companyImage, $languageID) {
	public function generateCompany ($id, $languageID) {
		$company = null;
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => API_URL.'?function=companyProfile&cId='.$id.'&lId='.$languageID,
			CURLOPT_HTTPHEADER => array('Content-type: application/json'),
			CURLOPT_RETURNTRANSFER => 1			
		));
		// Check if something is returned
		if ($res = substr(curl_exec($ch), 3)){
			$temp = json_decode($res);
			// Check if no error
			if ($temp->companyProfile->errorCode == '100'){
				// Add project ID
				$company = $temp->companyProfile->data;
				

				// $company->companyImage = $companyImage;
			}
		}
		curl_close($ch);
		
		return $company;

		/*$name = 'company-'.$id.'-'.$languageID.'.json';

		// Check if the file doesn't exist or is outdated
		if (file_exists(TMP_JSONS.$name))
			if (filemtime(TMP_JSONS.$name) < (int) $company->companyTimestamp)
			 	unlink(TMP_JSONS.$name);
			else return $name;
			// $companies->{$project->companyId}->image = $project->companyImage;

		

		// Write the JSON
		$f = fopen(TMP_JSONS.$name, 'w');
		fwrite($f, json_encode($company));
		fclose($f);

		return $name;*/
	}

	public function writeCompany ($company, $languageID) {
		$name = 'company-'.$company->companyId.'-'.$languageID.'.json';

		// Check if the file doesn't exist or is outdated
		if (file_exists(TMP_JSONS.$name))
			if (filemtime(TMP_JSONS.$name) < (int) $company->companyTimestamp)
			 	unlink(TMP_JSONS.$name);
			else return $name;
			// $companies->{$project->companyId}->image = $project->companyImage;

		

		// Write the JSON
		$f = fopen(TMP_JSONS.$name, 'w');
		fwrite($f, json_encode($company));
		fclose($f);

		return $name;
	}

	/*==========================================================================
		Generate & decline project/expose images
	==========================================================================*/
	public function generateImage ($projectImage, $src = 1) {
		global $imageSize, $imageSrcPath;

		// image name
		$forbidden = array(' ', '/');
		$name = $projectImage->piId
			// .'-'.str_replace(' ', '-', preg_replace("/&#?[a-z0-9]{2,8};/i","",$projectImage->piText))
			.'-'.str_replace($forbidden, '-', preg_replace("/&#?[a-z0-9]{2,8};/i","",$projectImage->piText))
			.'.'.$projectImage->piFormatExtension;
		
		$type = key($imageSize);
		reset($imageSize);
		
		// Check only the first file
		if (file_exists($imageSrcPath[$src].$type.'-'.$name))
			if (filemtime($imageSrcPath[$src].$type.'-'.$name) < (int) $projectImage->piTimestamp)
			 	unlink($imageSrcPath[$src].$type.'-'.$name);
			else return $name;

		$ch = curl_init();
		curl_setopt(
			$ch, 
			CURLOPT_URL, 
			API_URL_FILE.'?id='.$projectImage->piId.'&src='.$src
		);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$imgBase64 = curl_exec($ch);
		curl_close($ch);

		foreach ($imageSize as $type => $size){
			// Get original size of image
			$image = imagecreatefromstring(base64_decode($imgBase64));
	
			$new_width = (int) $size['width'];
			$new_height = (int) $size['height'];
	
			// Create new image using thumbnail sizes
			$thumb = imagecreatetruecolor($new_width,$new_height);
	
			// Copy original image to thumbnail
			imagecopyresampled($thumb,$image,0,0,0,0,$new_width,$new_height,imagesx($image),imagesy($image));
			
			if ($projectImage->piFormatExtension == "png")
				imagepng($thumb, $imageSrcPath[$src].$type.'-'.$name);
			else imagejpeg($thumb, $imageSrcPath[$src].$type.'-'.$name);
		}

		return $name;
		
	}

	
	public function generateCompanyImageName ($id, $name, $fullPath = false) {
		global $imageSrcPath;
		$name = $id
			.'-'.str_replace(' ', '-', preg_replace("/&#?[a-z0-9]{2,8};/i","",$name))
			.'.jpg';
		return $fullPath ? $imageSrcPath[9].$name : $name;
	}


	/*==========================================================================
		Get one company from JSON
	==========================================================================*/
	public function generateCompanyImage ($company, $src = 9) {
		global $imageSrcPath;

		/*$name = $company->companyId
			.'-'.str_replace(' ', '-', preg_replace("/&#?[a-z0-9]{2,8};/i","",$company->companyName))
			.'.jpg';*/

		$name = $this->generateCompanyImageName ($company->companyId, $company->companyName);
		
		// Check only the first file
		if (file_exists($imageSrcPath[$src].$name))
			if (filemtime($imageSrcPath[$src].$name) < (int) $company->companyTimestamp)
			 	unlink($imageSrcPath[$src].$name);
			else return $name;

		// if (file_exists($imageSrcPath[$src].$name)) return $name;

		$ch = curl_init();
		curl_setopt(
			$ch, 
			CURLOPT_URL, 
			API_URL_FILE.'?id='.$company->companyId.'&src='.$src
			// "http://soap.redise.com/SmartexposeWebApp.php?function=syncStruct&mId=2&lId=1"
		);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$imgBase64 = curl_exec($ch);
		curl_close($ch);

		$image = imagecreatefromstring(base64_decode($imgBase64)); 
		imagejpeg($image, $imageSrcPath[$src].$name);

		// Open image & check the size
		$size = getimagesize ($imageSrcPath[$src].$name);
		// $size = imagesy($image);

		// die (var_dump($size));
		if ((int) $size[1] > MAX_COMPANY_IMG_Y){
			$newX = $size[0] / ($size[1] / MAX_COMPANY_IMG_Y);
			unlink($imageSrcPath[$src].$name);

			// Create new image using thumbnail sizes
			$temp = imagecreatetruecolor($newX,MAX_COMPANY_IMG_Y);
	
			// Copy original image to thumbnail
			imagecopyresampled($temp,$image,0,0,0,0,$newX,MAX_COMPANY_IMG_Y,imagesx($image),imagesy($image));
			imagejpeg($temp, $imageSrcPath[$src].$name);

		}

		return $name;
	} 

	/*==========================================================================
		Generate JSON for given project (language specific)
	==========================================================================*/
	public function generateJson ($projectID, $project, $languageID) {
		$name = 'project-'.$projectID.'-'.$languageID.'.json';

		// Check if the file doesn't exist or is outdated
		if (file_exists(TMP_JSONS.$name))
			if (filemtime(TMP_JSONS.$name) < (int) $project->projectModified)
			 	unlink(TMP_JSONS.$name);
			else return $name;

		// Write the JSON
		$f = fopen(TMP_JSONS.$name, 'w');
		fwrite($f, json_encode($project));
		fclose($f);

		return $name;
	}

	/*==========================================================================
		Generate the home.json on every sync call
	==========================================================================*/
	public function generateHomeJson ($homeStruct, $languageID) {
		$name = 'home.json';

		// Write the JSON
		$f = fopen(TMP_JSONS.$name, 'w');
		fwrite($f, json_encode($homeStruct));
		fclose($f);

		return $name;
	}

	public function generateSlug ($projects) {
		$name = 'slugs.json';
		$slugs = null;

		foreach ($projects as $project)
			$slugs[$project->slug] = $project->pId;

		$f = fopen(TMP_JSONS.$name, 'w');
		fwrite($f, json_encode($slugs));
		fclose($f);
	}
}