<?php

	echo "Starting worker creation process <br/>";
	print "<pre>".$_GET["description"]."</pre>";

	include "supervisor_xmlrpc.php";
		try {	
			echo startWorker($_GET["description"]);
		} catch (Exception $e) {
			echo "no";
			echo '<br>Caught exception: ',  $e->getMessage();
			echo "<br>Trace:\n" . $e->getTraceAsString();
		}
	
?>
