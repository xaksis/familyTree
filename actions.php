<?php
include_once("treeModel.php");
if(isset($_GET['action']) && isset($_GET['id']))
{
	if (strlen(session_id()) < 1) {
		session_start();
	}
	
	$action=$_GET["action"];
	$id=$_GET["id"];
	$name = $_GET["name"];
	
	$tree = new Tree();
	switch($action)
	{
		case 'addChild':
			$tree->addChild($name, $id);
			echo $id;
			break;
		case 'addSpouse':
			$tree->addSpouse($name, $id);
			echo $id;
			break;
		case 'addParent':
			$newId = $tree->addParent($name, $id, $_SESSION['id']);
			echo $newId;
			break;
		case 'createTree':
			$newTreeId = $tree->createNewTree($name, $_SESSION['id']);
			echo $newTreeId;
			break;
		default:
			echo "No Action Taken";
			break;
	}
}
?>