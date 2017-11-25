<?php 
function send_json( $response ) {
	@header( 'Content-Type: application/json; charset=utf8' );
	echo json_encode( $response );
	die();
}

function check_user_login($login, $pdo)
{
	$stmt = $pdo->prepare('SELECT Name FROM Users WHERE Name = ?');

	try {
		$stmt->execute([$login]);
	} catch (PDOException $e) {
		return array('status' => 'error', 'message' => $e->getMessage());
	}

	$users = $stmt->fetchAll();
	if (count($users)) {
		return array('status' => 'error', 'message' => 'We have user with this Login, try some else please.');
	} else {
		return array('status' => 'success');
	}
}

function check_auth_user($pdo){
	if (!empty(isset($_COOKIE['auth']))) {
		$check = check_user_login($_COOKIE['auth'], $pdo);
		//there is user with this login
		if($check['status'] == 'error'){
			return true;
		} 
	}
	return false;
}