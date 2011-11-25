<?php

function IsRabbitMQRunning(){
	//AMQP PHP library test
	
	require_once('lib/php-amqplib/amqp.inc');
	
	$EXCHANGE = 'test';
	$BROKER_HOST   = 'localhost';
	$BROKER_PORT   = 5672;
	$QUEUE    = 'echo';
	$USER     ='guest';
	$PASSWORD ='guest';
	
	$msg_body = NULL;
	
//	try
//	{
	    //echo "Creating connection\n";
	    $conn = new AMQPConnection($BROKER_HOST, $BROKER_PORT,
				       $USER,
				       $PASSWORD);
	    
	    //echo "Getting channel\n";
	    $ch = $conn->channel();
	    //echo "Requesting access\n";
	    $ch->access_request('/data', false, false, true, true);
	    
	    //echo "Declaring exchange\n";
	    $ch->exchange_declare($EXCHANGE, 'direct', false, false, false);
	    //echo "Creating message\n";
	    $msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain'));
	    
	    //echo "Publishing message\n";
	    $ch->basic_publish($msg, $EXCHANGE, $QUEUE);
	    
	    //echo "Closing channel\n";
	    $ch->close();
	    //echo "Closing connection\n";
	    $conn->close();
	    return (true);
	    //echo "Done.\n";
//	} catch (Exception $e) {
	    //echo 'Caught exception: ',  $e->getMessage();
	    //echo "\nTrace:\n" . $e->getTraceAsString();
//	    return(false);
//	}
}

function sendMessageTo($msg, $to) {
		
	require_once('lib/php-amqplib/amqp.inc');
	
	echo "sending '".$msg."' to '".$to."'";
	
	$EXCHANGE = 'vumi';
	$BROKER_HOST   = 'localhost';
	$BROKER_PORT   = 5672;
	$QUEUE    = $to; //'telnet.inbound';
	//$ROUTING_KEY = 'telnet.event';
	$USER     ='guest';
	$PASSWORD ='guest';
	$VHOST = "/develop";
	
	$arr = array("content" => "mon message");
	//vumi use the JSON fromat for his messages
	if (strpos($to,"inbound")) {
		$arr = array("content" => $msg, 
			"message_version" => '20110921',
			"message_type" => '', 
			"timestamp" =>"", 
			"message_id" => "",
			"to_addr" => "0.0.0.0:9020",
			"from_addr" => "",
			"in_reply_to" => "",
			"session_event"=> null,
			"transport_name" =>"",
			"transport_type" => "",
			"transport_metadata" => "",
			"helper_metadata" => "");
	} elseif (strpos($to,"event")) {
		$arr = array(
			"message_version"=>"20110921",
			"message_type"=>"event",
			"user_message_id"=>"",
			"event_id"=>"",
			"event_type"=>"ack",
			"timestamp"=>"",
			"sent_message_id"=>"");	
	} /*else {
		return ("no message send, not type matching");	
	}*/
	
	$msg_body = json_encode($arr);
		
	echo "starting...";
	
	$conn = new AMQPConnection($BROKER_HOST, $BROKER_PORT,
				       $USER,
				       $PASSWORD,"/develop");
	//echo "Getting channel\n";
	$ch = $conn->channel();
	//echo "Requesting access\n";
	$ch->access_request('/data', false, false, true, true);
	
	echo "<br>Declaring exchange";
	$ch->exchange_declare($EXCHANGE, 'direct', false, true, false);
	echo "<br>Creating message\n";
	$msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain'));
	
	echo "<br>Publishing message";
	$ch->basic_publish($msg, $EXCHANGE, $QUEUE);
	
	echo "<br>Closing channel\n";
	$ch->close();
	echo "<br>Closing connection\n";
	$conn->close();
	return ($msg_body);
}



?>
