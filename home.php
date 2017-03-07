<!Doctype html>
<html>
<head>
<title>Parivar</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
</head>

<body>
	<div data-role="page" id="homePage">
		<div data-role="header">
			<h1>Parivar Tree Maker</h1>
		</div><!-- /header -->
		
		<fieldset class="ui-grid-a">
			<div class="ui-block-a">
				<form>
					<div style="padding:10px 20px;">
					  <h3>Login</h3>
					  <div id="login_msg" style="display:none;" class="ui-bar ui-bar-e ui-corner-all">Something</div>
					  <label for="login_uname" class="ui-hidden-accessible">Email:</label>
					  <input type="text" name="login_uname" id="login_uname" value="" placeholder="someone@something.com" data-theme="b" />
					  <label for="login_pword" class="ui-hidden-accessible">Password:</label>
					  <input type="password" name="login_pword" id="login_pword" value="" placeholder="Password" data-theme="b" />
					  <input type="button" value="Login" id="login_btn" data-theme="b"/>
					</div>
				</form>
			
			</div>
			
			<div class="ui-block-b">
				<form>
					<div style="padding:10px 20px;">
					  <h3>Register</h3>
					  <div id="register_msg" style="display:none;" class="ui-bar ui-bar-e ui-corner-all">Something</div>
					  <label for="register_uname" class="ui-hidden-accessible">Email:</label>
					  <input type="text" name="register_uname" id="register_uname" value="" placeholder="someone@something.com" data-theme="b" />
					  <label for="login_pword" class="ui-hidden-accessible">Password:</label>
					  <input type="password" name="register_pword" id="register_pword" value="" placeholder="Password" data-theme="b" />
					  <input type="button" value="Sign Up" id="register_btn" data-theme="b"/>
					</div>
				</form>
			</div>	   
		</fieldset>
		<script>
			function validateEmail(email) { 
			    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			    return re.test(email);
			} 
			$("#register_btn").click(function(){
					if($("#register_uname").val() == "" || $("#register_pword").val() == "")
					{
						$("#register_msg").html("Username or Password is blank.");
						$("#register_msg").show();
						return;
					}
					if(!validateEmail($("#register_uname").val()))
					{
						$("#register_msg").html("Email address doesn't seem to be valid.");
						$("#register_msg").show();
						return;
					} 
					$.ajax({
					  url: "login.php",
					  type: "POST", 
					  data: {action: "register", username: $("#register_uname").val(), password: $("#register_pword").val()},
					  dataType: 'html',
					  success: function(data){
							$.mobile.changePage("treeList.php");
					  }
					});
				});
				
			$("#login_btn").click(function(){
					if($("#login_uname").val() == "" || $("#login_pword").val() == "")
					{
						$("#login_msg").html("Username or Password is blank.");
						$("#login_msg").show();
						return;
					}
					if(!validateEmail($("#login_uname").val()))
					{
						$("#login_msg").html("Email address doesn't seem to be valid.");
						$("#login_msg").show();
						return;
					}

					$.ajax({
					  url: "login.php",
					  type: "POST", 
					  data: {action: "login", username: $("#login_uname").val(), password: $("#login_pword").val()},
					  dataType: 'html',
					  success: function(data){
							$.mobile.changePage("treeList.php");
					  }
					});
				});
		</script>
	</div>
</body>

</html>