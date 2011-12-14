<?php

	echo "Running an mongodb command - ";
	print "action: ".$_POST["action"]." - ";
	print "action: ".$_POST["program"]." - ";

	include "mongodb_feature.php";
	removeProgram($_POST["program"]);

?>
