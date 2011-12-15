<?php

	include "mongodb_feature.php";
	//$response=array();
	if (!array_key_exists('action', $_GET)){
		$response = array(
			"ok"=> false,
			"msg"=> "No action specified"
			);
		echo json_encode($response);
		exit;
	} 
	
	if (!array_key_exists('id', $_GET)){
		$response = array(
			"ok"=> false,
			"msg"=> "No id specified"
			);
		echo json_encode($response);
		exit;
	}
	
	if ($_GET['action']=='get') {
		$response = array(
			"ok"=> true,
			"msg" => getFrontEndProgram($_GET['id'])
			);
		echo json_encode($response);
		exit;		
	}

?>
