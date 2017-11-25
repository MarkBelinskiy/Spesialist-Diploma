<?php
require_once __DIR__.'/functions.php';

$project_update_id = (count(isset($_POST['get_project_id']))) ? $_POST['get_project_id'] : false;
$form_data = (count(isset($_POST['data']))) ? $_POST['data'] : false;
if ($project_update_id) {
	$get_project_information = get_project_information($project_update_id, $pdo);
	send_json($get_project_information);
} 

if ($form_data) {
	foreach ($form_data as $form_field) {
		if ($form_field['name'] == ('project_id' || 'project_name' || 'project_desc') && !empty($form_field['value'])) {
			${$form_field['name']} = $form_field['value'];
		} else {
			send_json(array('status' => 'error', 'message' => "All fields is required!"));
		}
	}
	$update_project = update_project($project_id, $project_name, $project_desc, $pdo);

		send_json($update_project);

}
send_json(array('status' => 'error', 'message' => "No data"));