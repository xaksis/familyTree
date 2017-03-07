<?php

class Tree
{
	private $server = 'localhost';
	private $user = 'root';
	private $pass = 'fire987';
	private $db = 'familytree_db';
	
	private function addNode($name, $parent_id, $spouse_id, $user_id)
	{
		$parent_id = $parent_id? $parent_id:"NULL";
		$spouse_id = $spouse_id? $spouse_id:"NULL";
		$user_id = $user_id? $user_id:"NULL";
	
		// Connecting, selecting database
		$con = mysql_connect($this->server, $this->user, $this->pass)
			or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db($this->db) or die('Could not select database');

		// Performing SQL query
		$query = "INSERT into trees(name,spouse_id,parent_id, user_id) values('$name', $spouse_id, $parent_id, $user_id)";
		mysql_query($query, $con) or die('Query failed: ' . mysql_error());
		$insert_id = mysql_insert_id(); 
		mysql_close($con);
		return $insert_id;
	}
	
	private function getParentId($child_id)
	{
		// Connecting, selecting database
		$con = mysql_connect($this->server, $this->user, $this->pass)
			or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db($this->db) or die('Could not select database');

		// Performing SQL query
		$query = "SELECT parent_id from trees where id=$child_id";
		$result = mysql_query($query, $con) or die('Query failed: ' . mysql_error());
		$row = mysql_fetch_array($result);
		mysql_close($con);
		return $row['parent_id'];
	}
	
	public function isTopNode($node_id, &$treeName, &$treeId)
	{
		$parent_id = $this->getParentId($node_id);
		if(is_null($parent_id))
			return false;
		//echo "parent id: ",$parent_id;
		// Connecting, selecting database
		$con = mysql_connect($this->server, $this->user, $this->pass)
			or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db($this->db) or die('Could not select database');

		// Performing SQL query
		$query = "SELECT parent_id,name,id from trees where id=$parent_id";
		$result = mysql_query($query, $con) or die('Query failed: ' . mysql_error());
		$row = mysql_fetch_array($result);
		mysql_close($con);
		$treeName = $row['name'];
		$treeId = $row['id'];
		return is_null($row['parent_id']);
	}
	
	public function getNodeInfo($node_id, $con)
	{
		// Performing SQL query
		$query = "SELECT n.id as id, n.user_id as owner_id, n.name as name, s.name as spouse from trees as n left join trees as s on n.spouse_id = s.id where n.id=$node_id";
		$result = mysql_query($query, $con) or die('Query failed: ' . mysql_error());
		$row = mysql_fetch_array($result);
		return $row;
	}
	
	public function getNodeChildren($node_id, $con)
	{
		// Performing SQL query
		$query = "SELECT id from trees where parent_id=$node_id";
		$result = mysql_query($query, $con) or die('Query failed: ' . mysql_error());
		$children = array();
		while($row = mysql_fetch_array($result))
		{
			array_push($children, $row['id']);
		}
		return $children;
	}
	
	public function editNode($node_id, $newName, $spouse_id, $parent_id, $user_id)
	{
		//echo $node_id, $newName, $spouse_id, $parent_id;
		// Connecting, selecting database
		$con = mysql_connect($this->server, $this->user, $this->pass)
			or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db($this->db) or die('Could not select database');

		// Performing SQL query
		$query="UPDATE trees SET";
		$useComma=false;
		if(!is_null($newName))
		{
			$query .= " name='$newName'";
			$useComma=true;
		}
		if(!is_null($spouse_id))
		{
			if($useComma)
				$query .= ",";
			$query .= " spouse_id=$spouse_id";
			$useComma=true;
		}
		if(!is_null($parent_id))
		{
			if($useComma)
				$query .= ",";
			$query .= " parent_id=$parent_id";
			$useComma=true;
		}
		if(!is_null($user_id))
		{
			if($useComma)
				$query .= ",";
			$query .= " user_id=$user_id";
		}
		$query .= " WHERE id=$node_id";	
		//echo $query,"<br>";
		mysql_query($query, $con) or die('Query failed: ' . mysql_error());
		mysql_close($con);
	}
	
	public function addChild($name, $parent_id)
	{
		$this->addNode($name, $parent_id, null, null);
	}
	
	public function addSpouse($name, $spouse_id)
	{
		//add the spouse as a node
		$insert_id = $this->addNode($name, null, $spouse_id, null);
		//update the original node
		$this->editNode($spouse_id, null, $insert_id, null, null); 
	}
	
	public function createNewTree($name, $user_id)
	{
		return $this->addNode($name, null, null, $user_id);
	}
	
	public function addParent($name, $child_id, $user_id)
	{
		//can only be called for the top node
		if(!$this->isTopNode($child_id, $treeName, $treeId))
			return;
		//echo "is top node","<br>";
		//create new node for the tree
		$newTree_id = $this->createNewTree($treeName, $user_id);
		//update the old tree node with new parent
		$this->editNode($treeId, $name, null, $newTree_id, "NULL");
		return $newTree_id;
	}
	
	private function readNode($node_id, &$tree, $con)
	{
		$row = $this->getNodeInfo($node_id, $con);
		$tree['name'] = $row['name'];
		$tree['spouse'] = $row['spouse'];
		$tree['id'] = $row['id'];
		$tree['owner_id'] = $row['owner_id'];
		$children = $this->getNodeChildren($node_id, $con);
		$count=0;
		foreach($children as $child_id)
		{
			$this->readNode($child_id, $tree['contents'][$count], $con);
			$count++;
		}
	}
	
	public function readTreeAndGenerateJson($tree_id)
	{
		$tree = array();
		// Connecting, selecting database
		$con = mysql_connect($this->server, $this->user, $this->pass)
			or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db($this->db) or die('Could not select database');
		
		$this->readNode($tree_id, $tree, $con);
		
		mysql_close($con);
		
		return json_encode($tree);
	}
	
	public function createUser($username, $password)
	{
		$password = sha1($password);
		// Connecting, selecting database
		$con = mysql_connect($this->server, $this->user, $this->pass)
			or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db($this->db) or die('Could not select database');

		// Performing SQL query
		$query = "INSERT into users(username,password) values('$username', '$password')";
		mysql_query($query, $con) or die('Query failed: ' . mysql_error());
		$user = array();
		$user["id"] = mysql_insert_id(); 
		$user["name"] = $username; 
		mysql_close($con);
		return $user;
	}
	
	public function login($username, $password, &$user)
	{
		$password = sha1($password);
		// Connecting, selecting database
		$con = mysql_connect($this->server, $this->user, $this->pass)
			or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db($this->db) or die('Could not select database');

		// Performing SQL query
		$query = "SELECT id,username from users where username='$username' and password='$password'";
		$result = mysql_query($query, $con) or die('Query failed: ' . mysql_error());
		mysql_close($con);
		if($row = mysql_fetch_array($result))
		{
			$user['id'] = $row['id'];
			$user['name'] = $row['username'];
			return true;
		}
		return false;
	}
	
	public function getAllTrees($user_id)
	{
		$con = mysql_connect($this->server, $this->user, $this->pass)
			or die('Could not connect: ' . mysql_error());
		//echo 'Connected successfully';
		mysql_select_db($this->db) or die('Could not select database');

		// Performing SQL query
		$query = "SELECT id,name from trees where user_id=$user_id order by created desc";
		$result = mysql_query($query, $con) or die('Query failed: ' . mysql_error());
		mysql_close($con);
		$treeList = array();
		$count=0;
		while($row = mysql_fetch_array($result))
		{
			$treeList[$count]['id'] = $row['id'];
			$treeList[$count]['name'] = $row['name'];
			$count++;
		}
		return $treeList;
	}
	
}
?>