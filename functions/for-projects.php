<?php

function get_user_id_by_name($user_name, $pdo){
	$user_id = $pdo->prepare('SELECT ID_user FROM Users WHERE Name = ?');
	try {
		$user_id->execute([$user_name]);
	} catch (PDOException $e) {
		return array('status' => 'error', 'message' => $e->getMessage());
	}

	return $user_id->fetch()['ID_user'];
}

function update_project($project_id, $project_name, $project_description, $pdo)
{

	$upd_prj = $pdo->prepare("
		UPDATE Projects SET 
		Name = :project_name, 
		Description = :project_description
		WHERE ID_project = :project_id");                                  
	$upd_prj->bindParam(':project_name', $project_name, PDO::PARAM_STR);
	$upd_prj->bindParam(':project_description', $project_description, PDO::PARAM_STR);
	$upd_prj->bindParam(':project_id', $project_id, PDO::PARAM_INT);

	try { 
		$upd_prj->execute();
	} catch(PDOExecption $e) { 
		return array('status' => 'error', 'message' => $e->getMessage());
	} 

	return array('project_id' => $project_id, 'project_name' => $project_name, 'project_description' => $project_description);
}

function create_project($project_name, $project_description, $user_name, $pdo)
{
	$user_id = get_user_id_by_name($user_name, $pdo);

	$insert_project = $pdo->prepare("
		INSERT INTO Projects(
		Name,
		Description
		) VALUES (
		:project_name, 
		:project_description)
		");

	$insert_project->bindParam(':project_name', $project_name, PDO::PARAM_STR); 
	$insert_project->bindParam(':project_description', $project_description, PDO::PARAM_STR);   

	try { 
		$insert_project->execute();
		$insered_id = $pdo->lastInsertId(); 
	} catch(PDOExecption $e) { 
		return array('status' => 'error', 'message' => $e->getMessage());
	}


	$insert_project_user = $pdo->prepare("
		INSERT INTO ProjectsUsers(
		ID_user,
		ID_project
		) VALUES (
		:user_id, 
		:insered_id)
		");

	$insert_project_user->bindParam(':user_id', $user_id, PDO::PARAM_INT); 
	$insert_project_user->bindParam(':insered_id', $insered_id, PDO::PARAM_INT);   

	try { 
		$insert_project_user->execute();
	} catch(PDOExecption $e) { 
		return array('status' => 'error', 'message' => $e->getMessage());
	} 

	return array('status' => 'success');
}

function get_project_information($project_id, $pdo){

	$stmt = $pdo->prepare('
		SELECT Projects.Name, Projects.Description
		FROM Projects 
		WHERE Projects.ID_project = ?'
	);

	try {
		$stmt->execute([$project_id]);
	} catch (PDOException $e) {
		return array('status' => 'error', 'message' => $e->getMessage());
	}

	$projects_of_user = $stmt->fetchAll()[0];
	return $projects_of_user;
}

function get_project_by_id($project_ID, $pdo){

	$stmt = $pdo->prepare('
		SELECT Nodes.ID_node as "key", FigureCategories.Name as "category", Nodes.loc, Nodes.text
		FROM Nodes 
		JOIN FigureCategories ON Nodes.ID_figure_category = FigureCategories.ID_figure_category 
		WHERE ID_project = ?'
	);

	try {
		$stmt->execute([$project_ID]);
	} catch (PDOException $e) {
		return array('status' => 'error', 'message' => $e->getMessage());
	}


	$nodes = $stmt->fetchAll();

	$stmt = $pdo->prepare('
		SELECT Links.ID_node_from as "from", Links.ID_node_to as "to", Links.FromPort as "fromPort", Links.ToPort as "toPort"
		FROM Links 
		WHERE ID_project = ?'
	);

	try {
		$stmt->execute([$project_ID]);
	} catch (PDOException $e) {
		return array('status' => 'error', 'message' => $e->getMessage());
	}

	$links = $stmt->fetchAll();

	$project_json = array(
		"class" => "go.GraphLinksModel",
		"linkFromPortIdProperty" => "fromPort",
		"linkToPortIdProperty" => "toPort",
		"nodeDataArray" => $nodes,
		"linkDataArray" => $links,
	);

	return $project_json;
}

function get_user_project_list($user_name, $pdo){

	$user_id = get_user_id_by_name($user_name, $pdo);
	$stmt = $pdo->prepare('
		SELECT ProjectsUsers.ID_project , Projects.Name, Projects.Description
		FROM ProjectsUsers 
		JOIN Projects ON ProjectsUsers.ID_project = Projects.ID_project 
		WHERE ProjectsUsers.ID_user = ?'
	);

	try {
		$stmt->execute([$user_id]);
	} catch (PDOException $e) {
		return array('status' => 'error', 'message' => $e->getMessage());
	}

	$projects_of_user = $stmt->fetchAll();
	return $projects_of_user;
}



function delete_project_rows_by_id($table, $project_ID, $pdo)
{
	$stmt = $pdo->prepare("DELETE FROM $table WHERE ID_project =  :ID_project");
	$stmt->bindParam(':ID_project', $project_ID, PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return array('status' => 'error', 'message' => $e->getMessage());
	}

	return array('status' => 'success', 'message' => 'All data deleted');

}

function add_nodes_project_rows_by_id($nodes, $ready_figures, $project_ID, $pdo)
{
	$stmt = $pdo->prepare("
		INSERT INTO Nodes(
		ID_project,
		ID_node,
		ID_figure_category,
		Text,
		Loc
		) VALUES (
		:ID_project, 
		:ID_node, 
		:ID_figure_category, 
		:Text, 
		:Loc)
		");

	foreach($nodes as $node)
	{	
		$stmt->bindParam(':ID_project', $project_ID, PDO::PARAM_INT);       
		$stmt->bindParam(':ID_node', $node->key, PDO::PARAM_INT); 
		$stmt->bindParam(':ID_figure_category', $ready_figures[$node->category], PDO::PARAM_INT);
		$stmt->bindParam(':Text', $node->text, PDO::PARAM_STR); 
		$stmt->bindParam(':Loc', $node->loc, PDO::PARAM_STR);   

		try {
			$stmt->execute();
		} catch (PDOException $e) {
			return array('status' => 'error', 'message' => $e->getMessage());
		}
	}

	return array('status' => 'success', 'message' => 'All data saved');

}

function add_links_project_rows_by_id($links, $project_ID, $pdo)
{
	$stmt = $pdo->prepare("
		INSERT INTO Links(
		ID_project,
		ID_node_from,
		ID_node_to,
		FromPort,
		ToPort
		) VALUES (
		:ID_project, 
		:ID_node_from, 
		:ID_node_to, 
		:FromPort, 
		:ToPort)
		");

	foreach($links as $link)
	{	
		$stmt->bindParam(':ID_project', $project_ID, PDO::PARAM_INT);       
		$stmt->bindParam(':ID_node_from', $link->from, PDO::PARAM_INT); 
		$stmt->bindParam(':ID_node_to', $link->to, PDO::PARAM_INT);
		$stmt->bindParam(':FromPort', $link->fromPort, PDO::PARAM_INT); 
		$stmt->bindParam(':ToPort', $link->toPort, PDO::PARAM_INT);   
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			return array('status' => 'error', 'message' => $e->getMessage());
		}
	}

	return array('status' => 'success', 'message' => 'All data saved');
}

function get_all_figures($pdo)
{
	$figures = $pdo->query('SELECT * FROM FigureCategories');
	$figures = $figures->fetchAll();

	foreach ($figures as $figure) {
		$ready_figures[$figure['Name']] = $figure['ID_figure_category'];
	}
	return $ready_figures;
}

function update_project_by_id($project_ID, $diagram_data, $pdo)
{
	$nodes = $diagram_data->nodeDataArray;
	$links = $diagram_data->linkDataArray;

	//get all figures from db
	$ready_figures = get_all_figures($pdo);
	if (!$ready_figures ) {
		return array('status' => 'error', 'message' => "Don't get figures");
	}

	//delete all old nodes and links of the project
	$delete_old_nodes_project = delete_project_rows_by_id('Links', $project_ID, $pdo);
	$delete_old_links_project = delete_project_rows_by_id('Nodes', $project_ID, $pdo);

	if (!$delete_old_nodes_project && !$delete_old_links_project ) {
		return array('status' => 'error', 'message' => "Can't to delete from DB");
	}

	//add new nodes and links of the project
	$add_nodes_project = add_nodes_project_rows_by_id($nodes, $ready_figures, $project_ID, $pdo);
	$add_links_project = add_links_project_rows_by_id($links, $project_ID, $pdo);

	if ($add_nodes_project['status'] == 'success' && $add_links_project['status'] == 'success') {
		return array('status' => 'Success', 'message' => "Your project successfully saved!!");
	} else {
		return array('status' => 'Error', 'message' => "Error at project, fix it and try again");

	}

}