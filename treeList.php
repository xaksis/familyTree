<!Doctype html>
<html>
<head>
<title>Parivar</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
</head>

<body>
	<div data-role="page" id="treeList">
		<?php
			if (strlen(session_id()) < 1) {
						session_start();
					}
					
					if(!isset($_SESSION['id']))
					{
						?>
							<script>
								window.location = "home.php";
							</script>
						<?php
					}
		?>
		<?php	
			require_once("treeModel.php");
			$tree = new Tree();
			$treelist = $tree->getAllTrees($_SESSION['id']);
		?>
	
		<div data-role="header">
			<h1>Parivar Tree Maker</h1>
		</div><!-- /header -->
		<div data-role="controlgroup" data-type="horizontal">
			<a href="#createTree" id="createTreePop_btn" data-role="button" data-rel="popup" data-position-to="window" data-icon="plus">Add New Tree</a>
		</div>
		<ul data-role="listview" data-inset="true">
			<?php
				foreach($treelist as $myTree)
				{
					echo "<li><a href='tree.php?treeId=",$myTree['id'],"'>",$myTree['name'],"</a></li>";
				}
			?>
		</ul>
		
		<div data-role="popup" id="createTree" data-theme="a" class="ui-corner-all">
			<form>
				<div style="padding:10px 20px;">
				  <h3>Create a New Tree</h3>
		          <label for="treeName" class="ui-hidden-accessible">Name:</label>
		          <input type="text" name="treeName" id="treeName" value="" placeholder="Tree Name" data-theme="a" />
		    	  <input type="button" value="Create" id="createTree_btn" data-theme="b"/>
				</div>
			</form>
		</div>
		
		<script>
			$( '#treeList' ).live('pageinit',function(){
				$("#createTree_btn").click(function(){
					$.ajax({
					  url: "actions.php",
					  data: {action: "createTree", name: $("#treeName").val(), id: <?php echo $_SESSION['id']; ?>},
					  dataType: 'json',
					  success: function(data){
						window.location = "tree.php?treeId="+data;
					  }
					});
				});
			});
		</script>
	</div>
</body>

</html>