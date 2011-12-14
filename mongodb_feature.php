<?php

//require_once 'lib/Mongodloid/Connection.php';

function getProgram(){
	
	$connection = new Mongo();
	
	//$db->setSlaveOkay(true);
	$db = $connection->selectDB('dev');
	
	//$db->program->find()->getNext();
	//echo 
	
	$cursor = $db->programs->find();
	
	return $cursor;
	//var_dump(iterator_to_array($cursor));
	
	/*foreach ($db->programs->find() as $program){
		echo $program->name;	
	}*/	
}

function getLogs($program_name){
	$connection = new Mongo();
	
	//$db->setSlaveOkay(true);
	$db = $connection->selectDB('dev');
	
	//$db->program->find()->getNext();
	//echo 
	
	$cursor = $db->selectCollection($program_name.'_logs')->find();
	
	return $cursor;
}

function getSchedules($program_name){
	$connection = new Mongo();
	
	//$db->setSlaveOkay(true);
	$db = $connection->selectDB('dev');
	
	//$db->program->find()->getNext();
	//echo 
	
	//$cursor = $db($program_name.'_Schedules')->find();
	$cursor = $db->selectCollection($program_name.'_schedules')->find();
	
	return $cursor;
}

function removeProgram($program_name){
	$connection = new Mongo();
	
	//$db->setSlaveOkay(true);
	$db = $connection->selectDB('dev');
	
	//$db->program->find()->getNext();
	//echo 
	
	$cursor = $db->programs->remove(array("name" => $program_name));
	$db->dropCollection($program_name.'_schedules');
	$db->dropCollection($program_name.'_logs');
}



?>
