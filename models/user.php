<?php
class User {
	/*==========================================================================
		Log user via the API
	==========================================================================*/
	public function signin () {
		global $session;
		// return Json::parse($id, 'company');
		// return true;
		$res = array('success' => false);

		// Data validation process
		$errors = array();

		// Check empty values
		foreach ($_POST as $name=>$elem) 
			if (empty($elem)) $errors[$name] = _('Not empty');

		if (!empty($errors)) $res['errors'] = $errors;
		else {

			$errorMessages = array(
				100 => _('Login successful'),
				104 => _('Emailaddress does not exist.'),
				107 => _('Password is invalid.')
			);
			$errorFields = array(
				104 => 'email',
				107 => 'email'
			);

			// serialize data
			$_POST['pwd'] = sha1($_POST['pwd']);
			$serialize = 'function=accountLogin';
			foreach ($_POST as $name=>$elem) $serialize .= "&$name=$elem";

			// Send datas to the API (via GET)
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => API_URL,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => $serialize
			));

			$api = json_decode(substr(curl_exec($ch), 3));
			curl_close($ch);

			$errorCode = (int) $api->accountLogin->errorCode;
			if ($errorCode == 100) { 
				$res['success'] = true;
				$session->set('memberID', $api->accountLogin->data->memberId);
			} else $res['errors'][$errorFields[$errorCode]] = $errorMessages[$errorCode];
		}

		return $res;
	}

	/*==========================================================================
		Register user via the API
	==========================================================================*/
	public function signup() {
		global $session;
		$res = array('success' => false);

		// Data validation process
		$errors = array();

		// Check empty values
		foreach ($_POST as $name=>$elem) 
			if (empty($elem)) $errors[$name] = _('Not empty');

		// Check if password match
		if (!empty($_POST['pwd']) && !empty($_POST['password2']))
			if ($_POST['pwd'] != $_POST['password2'])
				$errors['pwd'] = _('Passwords doesn\'t match');

		if (!empty($errors)) $res['errors'] = $errors;
		else {

			$errorMessages = array(
				100 => _('Account created successfully'),
				103 => _('Emailaddress already exists'),
				105 => _('Emailaddress invalid'),
				106 => _('password empty'),
				110 => _('Magazine ID does not exist'),
				115 => _('Language ID does not exist'),
			);
			$errorFields = array(
				103 => 'email',
				105 => 'email',
				106 => 'pwd'
			);

			unset ($_POST['password2']);
			$_POST['pwd'] = sha1($_POST['pwd']); // Encode password

			// serialize data
			$serialize = 'function=accountCreate'
						.'&mId='.CURRENT_MAGAZINE
						.'&lId='.$session->get('language.id');
			foreach ($_POST as $name=>$elem) $serialize .= "&$name=$elem";

			// Send datas to the API (via POST)
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => API_URL,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => $serialize,
				CURLOPT_RETURNTRANSFER => 1
			));

			$api = json_decode(substr(curl_exec($ch), 3));
			curl_close($ch);

			$errorCode = (int) $api->accountCreate->errorCode;
			if ($errorCode == 100) $res['success'] = true;
			else $res['errors'][$errorFields[$errorCode]] = $errorMessages[$errorCode];
		}
		return $res;
	}
}