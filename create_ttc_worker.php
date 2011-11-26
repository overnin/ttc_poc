<?php

	echo "Starting worker creation process <br/>";
	print "<pre>".$_GET["description"]."</pre>";

	include "supervisor_xmlrpc.php";
	include "test_rabbitmq.php";
		try {	
			$worker_config = json_decode($_GET["description"]);
			echo startWorker($_GET["description"]);
			echo sendMessageTo($_GET["description"], $worker_config->program->name.".control");
		} catch (Exception $e) {
			echo "no";
			echo '<br>Caught exception: ',  $e->getMessage();
			echo "<br>Trace:\n" . $e->getTraceAsString();
		}
	
?>
