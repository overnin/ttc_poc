<?php

	include "mongodb_feature.php";
	include "supervisor_xmlrpc.php";
	include "test_rabbitmq.php";
	
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
			if (hasRunBefore($program_config->program->name)==true) {
				//Has to update the running version and notify the worker
				$msg = $msg . "Update running worker data and send him update message.";
				$feprogram = getFrontEndProgram($program_config->id);
				$msg = $msg . saveProgram($feprogram['program']); 
				sendMessageTo('{"action":"resume","content":"'.$feprogram['program']['name'].'"}', 
					$feprogram['program']['name'].".control");
			}
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
	
	if ($_GET['action']=="start"){
		
		if (!array_key_exists('id', $_GET)){
			$response = array(
				"ok"=> false,
				"msg"=> "No id specified"
				);
			echo json_encode($response);
			exit;
		}
		
		$feprogram = getFrontEndProgram($_GET['id']); //retrieve program
		//echo "feprogram name: ". $feprogram['program']['name'];
		saveProgram($feprogram['program']);             //save it in running programme table
		startWorker2($feprogram['program']['name']);
		sendMessageTo('{"action":"start","content":"'.$feprogram['program']['name'].'"}', 
			$feprogram['program']['name'].".control");
		
		$response = array(
			"ok"=> true,
			"msg" => "The program is running."
			);
		echo json_encode($response);
		exit; 
	}
	
	if ($_GET['action']=="pause"){
		
		if (!array_key_exists('id', $_GET)){
			$response = array(
				"ok"=> false,
				"msg"=> "No id specified"
				);
			echo json_encode($response);
			exit;
		}
		
		$feprogram = getFrontEndProgram($_GET['id']); //retrieve program
		//echo "feprogram name: ". $feprogram['program']['name'];
		stopWorker($feprogram['program']['name']);
		removeWorker($feprogram['program']['name']);
		
		$response = array(
			"ok"=> true,
			"msg" => "The program ".$feprogram['program']['name']." has been stoped."
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
