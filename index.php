<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>TTC Prototype</title>
	<link href="lib/jqueryui/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet">
	<link href="css/form.css"rel="stylesheet">
	
	<script src="lib/jqueryui/js/jquery-1.6.2.min.js"></script>
	<script src="lib/jqueryui/js/jquery-ui-1.8.16.custom.min.js"></script>
	<script src="lib/dform/jquery.dform-0.1.4.min.js"></script>
	<script src="js/ttc-generic-program.js"></script>
	<script type="text/javascript" src="lib/form2js/src/form2js.js"></script>
	<script>
	$(function() {
		//create tab
		$("#tabs").tabs();
		$(".action_program").click(function(){
				var element = $(this);
				var action = element.attr("action");
				//alert("delete "+element.attr("id"))
				var dataString = "action="+action+"&program="+element.attr("var")
				$.ajax({
						type: "POST",
						url: "runmongo.php",
						data: dataString,
						cache: false,
						success: function(html){
							alert("data deleted, refresh the page!" + html);
						}
				});
		});
		
		$.dform.addType("add", function(option) {
			return $("<button type='button'>").dformAttr(option).html("add something")		
		});
		
		function isArray(obj) {
        	//returns true is it is an array
        		if (obj.constructor.toString().indexOf("Array") == -1)
        			return false;
        		else
        			return true;
        	};	
		
		
		
		//create form
		
		$("#generic-worker-form").buildForm({
                    "action": "index.html",
                    "method": "post",
                    "elements": [{
                        "type": "p",
                        "html": "Program"
                    }, {
                        "name": "program.name",
                        "id": "txt-programname",
                        "caption": "Program Name",
                        "type": "text",
                        "placeholder": "MH4"
                    }, {
                        "type": "fieldset",
                        "caption": "Group",
                        "elements" : [
                        	{
                        		"name" : "numbers",
                        		"caption": "phone number",
                        		"type" : "text",
                        		"placeholder": "256788601462"
                        	},{
                        		"type" : "add",
                        		"alert" : "add number"
                        	}
                        ]
                    }, {
                        "type": "fieldset",
                        "caption": "Message",
                        "elements" : [
                        	{
                        		"caption": "Message 1",
                        		"type": "fieldset",
                        		"elements" : [
                        			{
                        				"name":"program.messages[0].content",
                        				"caption":"Content",
                        				"type": "text",
                        				"placeholder":"Hello"
                        			},{
                        				"name":"program.messages[0].date",
                        				"caption":"Date",
                        				"type": "text",
                        				"placeholder":"now"
                        			}
                        		]
                        	}, {
                        		"caption": "Message 2",
                        		"type": "fieldset",
                        		"elements" : [
                        			{
                        				"name":"program.messages[1].content",
                        				"caption":"Content",
                        				"type": "text",
                        				"placeholder":"How are you"
                        			},{
                        				"name":"program.messages[1].date",
                        				"caption":"Date",
                        				"type": "text",
                        				"placeholder":"later"
                        			}
                        		]
                        	},{
                        		"type" : "add",
                        		"alert" : "add message"
                        	}
                        ]
                    }, {
                        "type": "submit",
                        "value": "Save"
                    }]
                });
	
		$("#generic-worker-form-dynamic").buildForm(fromBackendToFrontEnd());
	});
	
	$(function(){
	  	var xml = "<program><name>M4H</name><group>  <number>256788601462</number></group><message><content>Hello World</content><datetime>now</datetime></message></program>";
		$('#xml').text(xml); 
	});
	
	function test(){
		
		var formData = form2js('generic-worker-form-dynamic', '.', true);
		//alert();
		var indata= "description="+JSON.stringify(formData, null, '\t');
		
		$("#testArea").text(indata);
		
		$.get('create_ttc_worker.php',indata, function(data) {
				$("#result").html(data);
		});
	}
	
	</script>
	
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
			echo getState();
		} catch (Exception $e) {
			echo "no";
			echo '<br>Caught exception: ',  $e->getMessage();
			echo "<br>Trace:\n" . $e->getTraceAsString();
		}
	?>
	<a href="http://localhost:9010/">Still can visit the supervisord (localhost access required</a>
	</p>
	<div>
	<?php
		#include "supervisor_xmlrpc.php";
		try {	
			$arr = getAllProcessInfo();
			foreach ($arr as $item) {
				echo "=> ".$item['group']." : ".$item['name']." is ".$item['statename']."<br/>";
			}
		} catch (Exception $e) {
			echo "no";
			echo '<br>Caught exception: ',  $e->getMessage();
			echo "<br>Trace:\n" . $e->getTraceAsString();
		} 
	?>
	</div>
	<h3>Communication</h3>
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
	<h3>TTC Generic Program Worker</h3>
	<form name="createTTCWorker" action="create_ttc_worker.php" method="get">
	Start a new TTC worker. Enter json description of your worker <input type="text" name="description"/>
	<input type="submit" value="Submit"/>
	</form>
	<form name="removeTTCWorker" action="remove_ttc_worker.php" method="get">
	Remove TTC worker. Enter worker name<input type="text" name="name"/>
	<input type="submit" value="Submit"/>
	</form>
	
	<h3>TTC generic form non dynamic</h3>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Form</a></li>
			<li><a href="#tabs-2">JSON</a></li>
			<li><a href="#tabs-3">XML</a></li>
		</ul>
		<div id="tabs-1">
			<form id="generic-worker-form"></form>
		</div>
		<div id="tabs-2">
			<code>{"program":{"name":"M4H","group":{"number":256788601462},"message":{"content":"Hello World","datetime":{"value":"now"}}}}</code>
		</div>
		<div id="tabs-3">
			<code id="xml"></code>
		</div>
	</div>
	<h3>Create a new TTC generci worker (dynamic form)</h3>
		<form id="generic-worker-form-dynamic"></form>
	
	<p>data to be send</p>
	<pre><code id="testArea">
	</code></pre>
	<p>result:</p>
	<div id="result"></div>
	<h3>TTC generic worker... what is in their database</h3>
	<?php 
		include "mongodb_feature.php";
		try {
			$cursor = getProgram();
			foreach ($cursor as $program){
				echo "<div class='box'> ";
				echo "<div>".$program['name']."<input var='".$program['name']."' class='action_program' action='delete_program' type='button' value='delete'/></div>";
				$schedules = getSchedules($program['name']);
				echo "<div><p>->".$program['name']."_schedules:</p>";
				foreach ($schedules as $schedule){
					echo "-->datetime:".$schedule['datetime']." phone:".$schedule['participant_phone']."<br/>";
				}
				$logs = getLogs($program['name']);
				echo "<div><p>->".$program['name']."_log:</p>";
				foreach ($logs as $log){
					echo "-->datetime:".$log['datetime']." phone:".$log['message']['to_addr']." content:".$log['message']['content']."<br/>";
				}
				echo "</div>";
				echo "</div>";
				echo "</div><br/>";
			}
		} catch (Exception $e) {
			echo "no";
			echo '<br>Caught exception: ',  $e->getMessage();
			echo "<br>Trace:\n" . $e->getTraceAsString();
		}
	?>
	<h3>Front-End control of the programs</h3> 
	<?php
		try{
			$cursor = getFrontEndPrograms();
			foreach ($cursor as $program){
				echo "<div class='box'> ";
				echo "<div>".$program['name'];
				echo "<input var='".$program['name']."' class='action_program' action='delete_feprogram' type='button' value='delete program'/>";
				echo "<a href='feprogram.php?id=".$program['_id']."'>edit</a>";
				echo "<input var='".$program['name']."' class='add_feparticipant' type='button' value='add participant'/>";
				echo "</div>";
				echo "<div>->Status: ".$program['status'];
				if ($program['status'] == "NotValide") {
					echo " Please edit the program to make it valid";
				} else if ($program['status'] == "Valide") {
					echo "<input id='".$program['name']."' class='start_feprogram' type='button' value='start'/>";
				} else if ($program['status'] == "Running") {
					echo "<input id='".$program['name']."' class='stop_feprogram' type='button' value='stop'/>";
				} else {
					echo "status unknown";
				}
				echo "<div>->Worker started:".$program['start_date']."</div>";
				echo "<div>->tasks finished:".$program['end_date']."</div>";
				echo "</div>";
				echo "</div><br/>";
			}
		}catch (Exception $e) {
			echo "no";
			echo '<br>Caught exception: ',  $e->getMessage();
			echo "<br>Trace:\n" . $e->getTraceAsString();
		}
	?>
</body>

</html>
