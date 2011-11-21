<?php
	include "test_rabbitmq.php";
	sendMessageTo($_GET["message"], $_GET["to"]);
	header("Location: http://localhost/ttc_poc/index.php");
?>
