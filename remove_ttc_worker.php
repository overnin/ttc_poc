<?php

	echo "Removing worker <br/>";
	print "<pre>".$_GET["name"]."</pre>";

	include "supervisor_xmlrpc.php";
		try {	
			echo stopWorker($_GET["name"]);
			echo removeWorker($_GET["name"]);
		} catch (Exception $e) {
			echo "no";
			echo '<br>Caught exception: ',  $e->getMessage();
			echo "<br>Trace:\n" . $e->getTraceAsString();
		}
	

?>
