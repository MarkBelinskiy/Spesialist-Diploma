<?php
require_once __DIR__.'/functions.php';

$form_data = (count(isset($_POST['data']))) ? $_POST['data'] : false;
if ($form_data) {
	foreach ($form_data as $form_field) {
		if ($form_field['name'] == ('login' || 'password' || 'password2') && !empty($form_field['value'])) {
			${$form_field['name']} = $form_field['value'];
		} else {
			send_json(array('status' => 'error', 'message' => "All fields is required!"));
		}
	}
	if ($password !== $password2) {
		send_json(array('status' => 'error', 'message' => "Passwords doesn't equal!"));
	}

	$check_current_user = check_user_login($login, $pdo);
	if ($check_current_user['status'] == 'success') {
		$add_user = add_user($login, $password, $pdo);
		if ($add_user['status'] == 'success') {
			send_json(array('status' => 'redirect', 'message' => "index.php?registration=success"));
		} else {
			send_json($add_user);
		}
	} else {
		send_json($check_current_user);
	}

} else {
	send_json(array('status' => 'error', 'message' => "No data"));
}
die();