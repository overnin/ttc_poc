<?php

	echo "Running an mongodb command - ";
	echo "action: ".$_POST["action"]." - ";
	echo "action: ".$_POST["program"]." - ";

	include "mongodb_feature.php";
	if ($_POST["action"] == "delete_feprogram"){
		removeFrontEndProgram($_POST["program"]);
	}else if ($_POST["action"] == "delete_program") { 
		removeProgram($_POST["program"]);
	} else {
		echo "unknown action";
	}
?>
