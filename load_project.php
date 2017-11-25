<?php
require_once __DIR__.'/functions.php';

$diagram_data = (!empty(isset($_POST['data']))) ? json_decode($_POST['data']) : false;
if ($diagram_data !== '*') {
	$id_project = $diagram_data;
	$project_json = get_project_by_id($id_project, $pdo);
	send_json($project_json);	
} else {
	send_json(array('status' => 'Error', 'message' => "Can't load project"));
}
die();