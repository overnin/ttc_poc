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
			$.get('ajax.php',"action=get&id="+program_id, function(data){
					var response = $.parseJSON(data);
					//alert("get the program from the server"+response['ok']);
					$("#generic-worker-form-dynamic").buildForm(fromBackendToFrontEnd(response['msg']['program']));
					$.each($("input[name*='type-interaction']"),function (key, elt){
							if (!$.data(elt,'events')){	
								$(elt).change(updateRadioButtonSubmenu);
							};
					});
					$.each($("input[name*='type-schedule']"),function (key, elt){
							if (!$.data(elt,'events')){	
								$(elt).change(updateRadioButtonSubmenu);
							};
					});
			});
			
	});

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
	
	<h3>Create a new TTC generci worker (dynamic form)</h3>
	
	<form var="" id="generic-worker-form-dynamic"></form>
	<p>data to be send</p>
	<pre><code id="testArea">
	</code></pre>
	<p>result:</p>
	<div id="result"></div>
	
</body

</html>
