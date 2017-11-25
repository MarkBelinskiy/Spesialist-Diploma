<?php
function check_user_login_pass($login, $password, $pdo)
{
	$stmt = $pdo->prepare('SELECT Name, Password FROM Users WHERE Name = ? AND Password = md5(?)');

	try {
		$stmt->execute([$login, $password]);
	} catch (PDOException $e) {
		return array('status' => 'error', 'message' => $e->getMessage());
	}

	$users = $stmt->fetchAll();
	if (count($users)) {
		return array('status' => 'success');
	} else {
		return array('status' => 'error', 'message' => 'There is no User with this login or password');
	}
}