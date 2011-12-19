<?php

	include "mongodb_feature.php";
	include "supervisor_xmlrpc.php";
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
		
		foreach ($response['msg'] as $key => $program) {
			$response['msg'][$key]["process-state"] = getProcessInfo($program['name']);
		};
		
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
		if (!array_key_exists('description', $_GET)){
			$response = array(
				"ok"=> false,
				"msg"=> "No program specified"
				);
			echo json_encode($response);
			exit;
		}
		
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
