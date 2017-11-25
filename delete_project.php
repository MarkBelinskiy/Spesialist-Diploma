<?php
require_once __DIR__.'/functions.php';

$project_to_delete = (count(isset($_POST['data']))) ? $_POST['data'] : false;
if ($project_to_delete) {
	
	$delete_links = delete_project_rows_by_id('Links', $project_to_delete, $pdo);
	$delete_nodes = delete_project_rows_by_id('Nodes', $project_to_delete, $pdo);
	$delete_proj_usr = delete_project_rows_by_id('ProjectsUsers', $project_to_delete, $pdo);
	$delete_proj = delete_project_rows_by_id('Projects', $project_to_delete, $pdo);

	if (($delete_links['status'] && $delete_nodes['status'] && $delete_proj_usr['status'] && $delete_proj['status']) == 'success') {
		send_json(array('status' => 'success'));
	} else {
		send_json(array('status' => 'error'));
	}

} else {
	send_json(array('status' => 'error', 'message' => "No data"));
}
die();