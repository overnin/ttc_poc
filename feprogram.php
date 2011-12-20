<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Program View</title>
	<link href="lib/jqueryui/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet">
	<link href="css/form.css"rel="stylesheet">
	
	<script src="lib/jqueryui/js/jquery-1.6.2.min.js"></script>
	<script src="lib/jqueryui/js/jquery-ui-1.8.16.custom.min.js"></script>
	<!--<script src="lib/dform/jquery.dform-0.1.4.min.js"></script>-->
	<script src="lib/dform/src/dform.js"></script>
	<script src="lib/dform/src/dform.converters.js"></script>
	<script src="lib/dform/src/dform.extensions.js"></script>
	<script src="lib/dform/src/dform.subscribers.js"></script>
	<script src="js/ttc-generic-program.js"></script>
	<script type="text/javascript" src="lib/form2js/src/form2js.js"></script>
	<script>
	$(function(){
			var program_id = $.getUrlVar('id');
			//$("#generic-worker-form-dynamic").buildForm(fromBackendToFrontEnd());
			updateListOfProgram();
			$.get('ajax.php',"action=get&id="+program_id, function(data){
					var response = $.parseJSON(data);
					//alert("get the program from the server"+response['ok']);
					if (response['msg']){
						//var id = response['msg']['_id']['$id'];
						$("#generic-worker-form-dynamic").empty();
						$("#generic-worker-form-dynamic").buildForm(fromBackendToFrontEnd(response['msg']['program'],response['msg']['_id']['$id']));
						activeForm();	
					};
			});
			
	});
	
	function updateListOfProgram(){
		$.get('ajax.php',"action=getprogramlist", function(data){
				$("#program-list").empty();
				var response = $.parseJSON(data);
				var html = "";
				if (response['ok']){
					response['msg'].forEach(function(program){
						html = html + "<div class='box' id="+program['id']+" class='program-control'>"
						html = html + "<a href='feprogram.php?id="+program['id']+"'>View "+program['name']+"</a>"
						html = html + "<button class='delete-program' type='button' >delete</button>"
						html = html + "<button class='start-program' type='button' >start</button>"
						html = html + "<button class='pause-program' type='button' >pause</button>"
						if ("process-state" in program){
							html = html + "<div>Process state: "+program['process-state']+"</div>";	
						} else {
							html = html + "<div>No process status available</div>";
						}
						if ("action-log" in program) {
							html = html + "<div>Last user actions:"+program['action-log']['action']+" at "+program['action-log']['time']+"</div>";
						} else {
							html = html + "<div>Last user actions: not available</div>"
						}
						if ("send-log" in program) {
							html = html + "<div>Last message send:"+program['send-log']['msg']+" at "+program['send-log']['time']+"</div>";
							html = html + "<div>Total message send:"+program['send-log']['total']+"</div>";
						} else {
							html = html + "<div>Last message send: not available</div>"
						} 
						if ("schedule" in program) {
							html = html + "<div>Next scheduled message to:"+program['schedule']['participant_phone']+" at "+program['schedule']['time']+"</div>";
							html = html + "<div>Total scheduled message:"+program['schedule']['total']+"</div>";
						} else {
							html = html + "<div>Last message scheduled: not available</div>"
						} 
						
						html = html + "</div>";
					});
				}
				$("#program-list").html(html);
				activateUI();
		});
	};
	
	function activateUI(){
		$('button.delete-program').click(function(){
			deleteProgram($(this).parent().attr('id'));
		});	
	}

	function createNew() {
		$("#generic-worker-form-dynamic").empty();
		$("#generic-worker-form-dynamic").buildForm(fromBackendToFrontEnd());
		activeForm();	
	}
	
	function startProgram(){
		$.get('ajax.php',"action=start&id="+id, function(data){
				var response = $.parseJSON(data);
				updateFlash(response['msg']);
		});
	}
	
	function deleteProgram(id) {
		$.get('ajax.php',"action=delete&id="+id, function(data){
				var response = $.parseJSON(data);
				updateFlash(response['msg']);
				
		});
	}
	
	function updateFlash(msg){
		$("#flash").empty();
		$("#flash").html(msg + " The page is going to be refreshed.");
		//window.setTimeout('location.reload()', 2000);
	}
	
	$.extend({
			getUrlVars: function(){
				var vars = [], hash;
				var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
				for(var i = 0; i < hashes.length; i++)
				 {
				      hash = hashes[i].split('=');
				      vars.push(hash[0]);
				      vars[hash[0]] = hash[1];
				  }
				 return vars;
			},
			getUrlVar: function(name){
				return $.getUrlVars()[name];
			}
	});	
	</script>
</head>

<body>
	<div id="flash" class="flash"></div>
	<h3>List of program on the server</h3>
	<div id="program-list"></div>
	<button id="create-new-button" onclick="javascript:createNew()" label="Create New">Create New</button>
	<h3>View one program</h3>
	<form var="" id="generic-worker-form-dynamic"></form>
	<pre><code id="testArea">
	</code></pre>
</body

</html>
