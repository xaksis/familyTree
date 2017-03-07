<?php
if(isset($_POST['treeId']))
{
	include("treeModel.php");

	$myTree = new Tree();
	$data = $myTree->readTreeAndGenerateJson($_POST['treeId']);

	echo $data;
}
?>