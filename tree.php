<!Doctype html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
    <title>Tree Experiment</title>
</head>
<body>
	<div data-role="page" id="treePage">
	<style>
        g.node {
            font-family: Verdana, Helvetica;
            font-size: 11px;
            font-weight: normal;
        }
        circle.node-dot {
			cursor: pointer;
            fill: #fff;
            stroke: steelblue;
            stroke-width: 2px;
        }

        path.link {
            fill: none;
            stroke: gray;
        }
    </style>
		<div data-role="header">
			<h1>Parivar Tree Maker</h1>
			<a href="home.php" data-icon="home" class="ui-btn-left" data-theme="b">Home</a>
			<?php 
				if (strlen(session_id()) < 1) {
					session_start();
				}
				if(isset($_SESSION['id'])){
			?>
			<a href="logout.php" data-icon="delete" class="ui-btn-right" data-theme="b">Logout</a>
			<?php
				}
			?>
		</div><!-- /header -->
		
			<div id="tree-container" style="min-width: 1300px; min-height: 800px; overflow-x: scroll;"></div>
			<a id="lnkDialog" href="#actions" data-rel="popup" data-position-to="window" style="display: none;">Open dialog</a> 
			<span id="currentNodeId" style="display: none"></span>
		
		<div data-role="footer">
			<h4>2013 Crayon Bytes</h4>
		</div>
		
		<div data-role="popup" id="actions" data-theme="a" class="ui-content ui-corner-all" >
			<div style="padding:10px 20px;">
				<ul data-role="listview">
					<li><a href="#addChild" data-rel="popup" data-position-to="window">Add a Child</a></li>
					<li><a href="#addSpouse" data-rel="popup" data-position-to="window">Add Spouse</a></li>
					<li><a href="#addParent" data-rel="popup" data-position-to="window">Add Parent</a></li>
				</ul>
			</div>
		</div>
		
		<div data-role="popup" id="addChild" data-theme="a" class="ui-corner-all">
			<form>
				<div style="padding:10px 20px;">
				  <h3>Add a child</h3>
		          <label for="childName" class="ui-hidden-accessible">Name:</label>
		          <input type="text" name="childName" id="childName" value="" placeholder="Name" data-theme="a" />
		    	  <input type="button" value="Add" id="addChild_btn" data-theme="b"/>
				</div>
			</form>
		</div>
		
		<div data-role="popup" id="addSpouse" data-theme="a" class="ui-corner-all">
			<form>
				<div style="padding:10px 20px;">
				  <h3>Add Spouse</h3>
		          <label for="spouseName" class="ui-hidden-accessible">Name:</label>
		          <input type="text" name="spouseName" id="spouseName" value="" placeholder="Name" data-theme="a" />
		    	  <input type="button" value="Add" id="addSpouse_btn" data-theme="b"/>
				</div>
			</form>
		</div>
		
		<div data-role="popup" id="addParent" data-theme="a" class="ui-corner-all">
			<form>
				<div style="padding:10px 20px;">
				  <h3>Add Parent</h3>
		          <label for="parentName" class="ui-hidden-accessible">Name:</label>
		          <input type="text" name="parentName" id="parentName" value="" placeholder="Name" data-theme="a" />
		    	  <input type="button" value="Add" id="addParent_btn" data-theme="b"/>
				</div>
			</form>
		</div>
		<script src="http://d3js.org/d3.v3.min.js"></script>
		<script src="tree.js"></script>
		<?php
					
					if(!isset($_SESSION['id']))
					{
						?>
							<script>
								//window.location = "home.php";
							</script>
						<?php
					}
		?>
		<script>
			$( '#treePage' ).live('pageinit',function(){
				var treeId = <?php echo $_GET['treeId']; ?>;
				$.ajax({
				  url: "getTreeJson.php",
				  data: {treeId: treeId},
				  type: "POST",
				  dataType: 'json',
				  success: function(data){
					var treeOwner = false;
					if(data.owner_id == <?php if(isset($_SESSION['id']))echo $_SESSION['id'];else echo 0; ?>)
						treeOwner = true;
					buildTree("#tree-container", data, treeOwner);
				  }
				});
				
				$("#addChild_btn").click(function(){
					if($("#childName").val() == "")
						return;
					
					$.ajax({
					  url: "actions.php",
					  data: {action: "addChild", name: $("#childName").val(), id: $("#currentNodeId").html()},
					  dataType: 'json',
					  success: function(data){
						window.location = "tree.php?treeId="+treeId;
					  }
					});
				});
				
				$("#addSpouse_btn").click(function(){
					if($("#spouseName").val() == "")
						return;
					
					$.ajax({
					  url: "actions.php",
					  data: {action: "addSpouse", name: $("#spouseName").val(), id: $("#currentNodeId").html()},
					  dataType: 'json',
					  success: function(data){
						window.location = "tree.php?treeId="+treeId;
					  }
					});
				});
				
				$("#addParent_btn").click(function(){
					if($("#parentName").val() == "")
						return;
					
					$.ajax({
					  url: "actions.php",
					  data: {action: "addParent", name: $("#parentName").val(), id: $("#currentNodeId").html()},
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


