function fromBackendToFrontEnd(configFile) {
	//alert("function called");
	
	$.dform.addType("addElt", function(option) {
			return $("<button type='button'>").dformAttr(option).html("add "+option["label"])		
		});
	/*	
	$.dform.subscribe("alert", function(option, type) {
			if (type=="addElt")
			{
				this.click(function (){
					alert("click on add element");
					$(this).prev().after($(document.createElement('label')).html(this.label));
					//	$(this).prev().after($(this).prev().prev().clone());
			});
		}
	});*/
	$.dform.subscribe("alert", function(option, type) {
			//alert("message alert "+type);
			if (type=="add")
			{
				this.click(function (){
					//alert(option +" "+ $(this).prev().prev().text());
					$(this).prev().after($(this).prev().prev().clone());
				});
			};
			if (type=="addElt")
			{
				this.click(function (){
					
					//alert("click on add element "+$(this).prev('legend'));
					var id = $(this).prevAll("fieldset").length;
					var html = "<fieldset class='ui-dform-fieldset'>";
					html = html + "<legend class='ui-form-legend'>"+$(this).attr('label')+"</legend>";
					var parentLabel = $(this).attr('label');
					var tableLabel = $(this).parent().attr('name');
					program[$(this).attr('label')].forEach(function(item) {
							if (!isArray(program[item])) 
								html = html + "<label>"+item+"</label><input type='text' name='program."+tableLabel+"["+id+"]."+parentLabel+"."+item+"'></input>"
							else {
								html = html + "<fieldset class='ui-dform-fieldset'><legend class='ui-form-legend'>"+item+"</legend></fieldset>"
							}
					})
					html = html + "</fieldset>";
					//alert("adding "+html);
					if ($(this).prevAll("fieldset").length)
					{
						//alert("there is a fieldset")
						$(this).prevAll("fieldset").first().after(html);
					}else{
						//alert("no fieldset")
						$(this).prevAll("legend").after(html);
					}
					//
					//	$(this).prev().after($(this).prev().prev().clone());
			});
			};
	});
		
	var program = {"program": [ 
				"name", 
				"customer",  
				"shortcode",
				"country",
				"numbers",
				"messages"
				],
			"name" : "text",
			"customer": "text",
			"shortcode" : "text",
			"numbers":"textarea",
			"country": "text",
			"messages": ["message","question","answer"],
			"message": ["content","date","time"],
			"question": ["content","keyword", "time",["answer"]],
			"answer":["keyword","feedback","action"],
			"id":"text",
			"content":"text",
			"date": "text",
			"time": "text",
			"keyword":"text",
			"action":"text",
			"feedback":"text"
			};
	
	//var test = ""
	//program["program"].forEach(function(item){test= test+item});
	//alert("program contains"+test);
	
	var myform = {
		"action": "javascript:test()",
		"method": "post",
                "elements": 
                [{
                        "type": "p",
                        "html": "Program"
                }]
        };
        
        function isArray(obj) {
        	//returns true is it is an array
        	if (obj.constructor.toString().indexOf("Array") == -1)
        		return false;
        	else
        		return true;
        };
        
        program["program"].forEach(function(item){
        		if (!isArray(program[item]))
        		{
        			//alert("add item "+item);
        			myform["elements"].push(
        			{
        			"name":"program."+item,
        			"caption": item,
        			"type": program[item]
        			});
        		}
        	else 
        	{
//        		alert("array type "+item);
        		var myelt = {
        			"type":"fieldset",
        			"caption": item,
        			"name": item,
          			"elements": []
        			};
//      			alert("array is "+program[item]);
        		program[item].forEach(function (elt) {
        				myelt["elements"].push({
        						"type":"addElt",
        						"alert":"add message",
        						"label": elt
        				});
        			});
        		myform["elements"].push(myelt);
        	}
        });
          
        myform["elements"].push({
                        "type": "submit",
                        "value": "Save"
                })
        //alert("myform ready:"+myform)
        return myform;
}

