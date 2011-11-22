<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>TTC Prototype</title>
</head>

<body>
	<h2>TTC Prototype</h2>
	<h3>System Status</h3>
	<p>RabbitMQ....
	<?php 
		include "test_rabbitmq.php";
		try {
			if (IsRabbitMQRunning()) { 
				echo "RUNNING";
			} else {
				echo "not running";
			}
		} catch (Exception $e) {
			echo "no";
			echo '<br>Caught exception: ',  $e->getMessage();
			echo "<br>Trace:\n" . $e->getTraceAsString();
		}
	?>
	</p>
	<p>Supervisord...
	<?php
		include "supervisor_xmlrpc.php";
		try {
			$arr = getState();
			echo $arr['statename'];
		} catch (Exception $e) {
			echo "no";
			echo '<br>Caught exception: ',  $e->getMessage();
			echo "<br>Trace:\n" . $e->getTraceAsString();
		}
	?>
	<a href="http://localhost:9010/">Still can visit the supervisord</a>
	</p>
	
	<h3>Workers</h3>
	<form name="sendMessageToEcho" action="send_message_to_echo_worker.php" method="get">
	Send Messsage via RabbitMQ to a worker or transporter. Content: <input type="text" name="message" /> 
	To (routing key):<input type="text" name="to"/>
	<input type="submit" value="Submit"/>
	</form>
	<form name="sendMessageViaHTTP" action="send_message_via_http.php" method="get"> 
	Send Message via HTTP to a transporter. Content: <input type="text" name="content"/> 
	To (phone number):<input type="text" name="to"/>
	From (phone number):<input type="text" name="from"/>
	<input type="submit" value="Submit" />
	</form>
</body>

</html>
