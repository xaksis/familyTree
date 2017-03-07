<?php
include_once("treeModel.php");
if(isset($_POST['action']) && isset($_POST['username']) && isset($_POST['password']))
{
	if (strlen(session_id()) < 1) {
		session_start();
	}
	
	$action=$_POST["action"];
	$username= $_POST["username"];
	$password = $_POST["password"];
	
	$tree = new Tree();
	switch($action)
	{
		case 'register':
			$user = $tree->createUser($username, $password);
			echo "Registered";
			break;
		case 'login':
			$user = array();
			$tree->login($username, $password, $user);
			echo "Login Successful";
			break;
		default:
			echo "Something went Wrong";
			session_destroy();
			break;
	}
	
	if(isset($user['id']) && isset($user['name']))
	{
		$_SESSION['id'] = $user['id'];
		$_SESSION['name'] = $user['name'];
		var_dump($_SESSION);
	}
	else
	{
		session_destroy();
	}
	
}
?>