<?php
require_once __DIR__.'/functions.php';

$diagram_data = (!empty(isset($_POST['data']))) ? json_decode($_POST['data']) : false;
$id_project= (!empty(isset($_POST['project']))) ? $_POST['project'] : false;
if ($diagram_data && $id_project !== '*') {
	$project_json = get_project_by_id($id_project, $pdo);
	if (count($project_json)) {
		$updated_project = update_project_by_id($id_project, $diagram_data,$pdo);
		send_json($updated_project);
	} else {
		send_json(array('status' => 'Error', 'message' => "Can't load project"));
	}
} elseif ($diagram_data && $id_project) {
	# code...
} else {
	send_json(array('status' => 'Error', 'message' => "No data"));
}
die();