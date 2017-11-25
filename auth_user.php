<?php
require_once __DIR__.'/functions.php';

$form_data = (count(isset($_POST['data']))) ? $_POST['data'] : false;
if ($form_data) {
	foreach ($form_data as $form_field) {
		if ($form_field['name'] == ('login' || 'password') && !empty($form_field['value'])) {
			${$form_field['name']} = $form_field['value'];
		} else {
			send_json(array('status' => 'error', 'message' => "All fields is required!"));
		}
	}

	$check_current_user_pass = check_user_login_pass($login, $password, $pdo);
	if ($check_current_user_pass['status'] == 'success') {
		setcookie( "auth", $login, strtotime( '+30 days' ) );
		send_json(array('status' => 'redirect', 'message' => "dashboard.php"));
	} else {
		send_json($check_current_user_pass);
	}

} else {
	send_json(array('status' => 'error', 'message' => "No data"));
}
die();