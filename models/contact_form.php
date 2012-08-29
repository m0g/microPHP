<?php
class ContactForm {

	var $fields = array(
		'first_name' => array('mandatory' => true),
		'last_name' => array('mandatory' => true),
		'email' => array('mandatory' => true),
		'phone' => array('mandatory' => false),
		'subject' => array('mandatory' => true),
		'message' => array('mandatory' => true),
		'expose_id' => array('mandatory' => true)
	);

	public function validate ($data) {

		// Check mandatory fields
		$mandatories = null;
		foreach ($data as $key=>$content)
			if ($this->fields[$key]['mandatory'] && empty($content))
				$mandatories[] = $key;

		return compact('mandatories');
	}
}