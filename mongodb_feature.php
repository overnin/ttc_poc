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
	
	$db = $connection->selectDB('dev');
	
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

function deleteFrontEndProgram($program_id){
	$connection = new Mongo();
	
	$db = $connection->selectDB('dev');

	$db->feprograms->remove(array("_id" => new MongoID($program_id)));

	return "The program ".$program_id." has been removed.";
}

function getFrontEndPrograms(){
	
	$connection = new Mongo();
	
	//$db->setSlaveOkay(true);
	$db = $connection->selectDB('dev');
	
	//$db->program->find()->getNext();
	//echo 
	
	$cursor = $db->feprograms->find();
	
	return $cursor;
	
}

function getFrontEndProgramList(){
	$connection = new Mongo();

	$db = $connection->selectDB('dev');
	$result = array();	
	
	$i = 0;
	foreach ($db->feprograms->find() as $cursor){
		
		$result[$i] = array (
			'id' => $cursor['_id']->{'$id'},
			'name' => $cursor['program']['name'],
			'action-log' => '',
			'send-log' => '',
			'schedule-log' => ''
			);
		$actionlog = $db->selectCollection($cursor['program']['name'].'_action_log')->findOne();
		
		if ($actionlog) {
			$result[$i]['action-log'] = array ( 
				'action' => $actionlog['action'],
				'time' => $actionlog['time']
				);
		}
		
		$sendlog = $db->selectCollection($cursor['program']['name'].'_logs')->findOne();
		$sendtotal = $db->selectCollection($cursor['program']['name'].'_logs')->count();
		
		if ($sendlog) {
			$result[$i]['send-log'] = array ( 
				'send' => $sendlog['message']['content'],
				'time' => $sendlog['datetime'],
				'total' => $sendtotal
				);
		}
		
		$schedule = $db->selectCollection($cursor['program']['name'].'_schedules')->findOne();
		$scheduletotal = $db->selectCollection($cursor['program']['name'].'_schedues')->count();
		
		if ($schedule) {
			$result[$i]['schedule'] = array ( 
				'participant_phone' => $schedule['participant_phone'],
				'time' => $schedule['time'],
				'total' => $scheduletotal
				);
		}
		$i = $i + 1;
	};
	
	return $result;
}

function getFrontEndProgram($program_id){
	
	$connection = new Mongo();
	$db = $connection->selectDB('dev');
	
	return $db->feprograms->findOne(array("_id" => new MongoID($program_id)));
	// $db->feprograms->findOne(array("name" => "toto"));
}

function saveFrontEndProgram($program,$id=null){

	//echo "start saving in database: ".$program->name;	
	$connection = new Mongo();

	//$db->setSlaveOkay(true);
	$db = $connection->selectDB('dev');
	
	//$db->program->find()->getNext();
	//echo 
	
	if ($id){
		$feprogram = array(
			"program"=>$program
			);
		$db->feprograms->update( array("_id" => new MongoID($id)),array('$set'=> array('program'=> $feprogram['program'])));
		return "The program ".$program->name." has been updated";
	}else{
		if (!property_exists($program,'name')) {
			return "Name not defined, program not saved";
		}
		
		$feprogram = array(
			"program"=>$program,
			"status"=>"NotValide", 
			"start_date"=>"",
			"end_date"=>""
			);
		$db->feprograms->insert($feprogram);
		return "The program ".$program->name." has been saved";
	}
	
	//echo "feprogram saved";
}
	

?>
