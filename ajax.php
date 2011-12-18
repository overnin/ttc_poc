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
	
	
	if ($_GET['action']=='getprogramlist') {
			
		$response = array(
			"ok"=> true,
			"msg" => getFrontEndProgramList()
			);
		echo json_encode($response);
		exit;		
	}
	
	if ($_GET['action']=='get') {
	
		if (!array_key_exists('id', $_GET)){
			$response = array(
				"ok"=> false,
				"msg"=> "No id specified"
				);
			echo json_encode($response);
			exit;
		}
		
		$response = array(
			"ok"=> true,
			"msg" => getFrontEndProgram($_GET['id'])
			);
		echo json_encode($response);
		exit;		
	}
	
	if ($_GET['action']=="save"){
		$program_config = json_decode($_GET["description"]);
		if (property_exists($program_config,'id')){
			$msg = saveFrontEndProgram($program_config->program,$program_config->id);
		} else {
			$msg = saveFrontEndProgram($program_config->program);
		}
		
		$response = array(
			"ok"=> true,
			"msg" => $msg
			);
		echo json_encode($response);
		exit;
	}
	
	if ($_GET['action']=="delete"){
		
		if (!array_key_exists('id', $_GET)){
			$response = array(
				"ok"=> false,
				"msg"=> "No id specified"
				);
			echo json_encode($response);
			exit;
		}
		
		$response = array(
			"ok"=> true,
			"msg" => deleteFrontEndProgram($_GET['id'])
			);
		echo json_encode($response);
		exit; 
	}
	
	$response = array(
			"ok"=> false,
			"msg"=> "Action not define on server"
			);
	echo json_encode($response);
	exit;

?>
