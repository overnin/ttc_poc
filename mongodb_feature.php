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

function removeFrontEndProgram($program_name){
	$connection = new Mongo();
	
	//$db->setSlaveOkay(true);
	$db = $connection->selectDB('dev');
	
	//$db->program->find()->getNext();
	//echo 
	
	$cursor = $db->feprograms->remove(array("name" => $program_name));
}

function getFrontEndPrograms(){
	
	$connection = new Mongo();
	
	//$db->setSlaveOkay(true);
	$db = $connection->selectDB('dev');
	
	//$db->program->find()->getNext();
	//echo 
	
	$cursor = $db->feprograms->find();
	
	return $cursor;
	//var_dump(iterator_to_array($cursor));
	
	/*foreach ($db->programs->find() as $program){
		echo $program->name;	
	}*/	
}

function getFrontEndProgram($program_id){
	
	$connection = new Mongo();
	$db = $connection->selectDB('dev');
	
	return $db->feprograms->findOne(array("_id" => new MongoID($program_id)));
	// $db->feprograms->findOne(array("name" => "toto"));
}

function saveFrontEndProgram($program){

	echo "start saving in database: ".$program->name;	
	$connection = new Mongo();

	//$db->setSlaveOkay(true);
	$db = $connection->selectDB('dev');
	
	//$db->program->find()->getNext();
	//echo 
	
	$feprogram = array("name"=>$program->name,
		"program"=>$program,
		"status"=>"NotValide", 
		"start_date"=>"",
		"end_date"=>""
		);
	
	$db->feprograms->insert($feprogram);
	
	echo "feprogram saved";
}
	

?>
