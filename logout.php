<?php
	if (strlen(session_id()) < 1) {
		session_start();
	}
	
	session_destroy();
	header('Location: home.php');
?>