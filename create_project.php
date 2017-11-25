<?php
require_once __DIR__.'/functions.php';

$form_data = (count(isset($_POST['data']))) ? $_POST['data'] : false;
if ($form_data) {
	foreach ($form_data as $form_field) {
		if ($form_field['name'] == ('project_name' || 'project_desc') && !empty($form_field['value'])) {
			${$form_field['name']} = $form_field['value'];
		} else {
			send_json(array('status' => 'error', 'message' => "All fields is required!"));
		}
	}
	$user_name = $_COOKIE['auth'];
	$create_project = create_project($project_name, $project_desc, $user_name, $pdo);

	if ($create_project['status'] == 'success') {
		send_json(array('status' => 'redirect', 'message' => "dashboard.php"));
	} else {
		send_json($check_current_user_pass);
	}

} else {
	send_json(array('status' => 'error', 'message' => "No data"));
}
die();