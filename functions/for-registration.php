<?php

function add_user($login, $password, $pdo)
{
	$stmt = $pdo->prepare("INSERT INTO Users( Name, Password ) VALUES ( :login, md5(:password)) ");
	$stmt->bindParam(':login', $login, PDO::PARAM_STR); 
	$stmt->bindParam(':password', $password, PDO::PARAM_STR);   

	try {
		$stmt->execute();
	} catch (PDOException $e) {
		return array('status' => 'error', 'message' => $e->getMessage());
	}

	return array('status' => 'success');
}