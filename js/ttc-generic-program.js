var program = {"program": [ 
				"name", 
				"customer",  
				"shortcode",
				"country",
				"participants",
				"dialogues",
				],
			"name" : "text",
			"customer": "text",
			"shortcode" : "text",
			"participants":["add-participant","add-group"],
			"add-group":"button",
			"add-participant":"button",
			"participant":["phone","name"],
			"phone":"text",
			"country": "text",
			"dialogues": ["add-dialogue"],
			"add-dialogue":"button",
			"dialogue": ["name","type","interactions","dialogue_id"],
			"dialogue_id": "hidden",	
			"interactions":["add-interaction"],
			"interaction":["content","type","schedule_type","interaction_id"],
			"interaction_id":"hidden",
			"add-interaction":"button",
			"announcement": ["content","name","schedule_type"],
			"question": ["content","keyword", "schedule_type",["answer"]],
			"answer":["keyword","feedback","action"],
			"id":"text",
			"schedule_type":"text",
			"type":"text",
			"content":"text",
			"date": "text",
			"time": "text",
			"keyword":"text",
			"action":"text",
			"feedback":"text"
			};
	


function clickBasicButton(){
					
	//alert("click on add element "+$(this).prev('legend'));
	var id = $(this).prevAll("fieldset").length;
	var parentLabel = $(this).attr('label');
	var tableLabel = $(this).parent().attr('name');
	
	var html = "<fieldset class='ui-dform-fieldset' name='"+tableLabel+"["+id+"]'>";
	html = html + "<legend class='ui-dform-legend'>"+$(this).attr('label')+"</legend>";
	if (isArray(program[parentLabel])){
		program[$(this).attr('label')].forEach(function(item) {
			if (isArray(program[item])) {
					html = html + "<fieldset class='ui-dform-fieldset' name='"+tableLabel+"["+id+"]."+item+"'><legend class='ui-form-legend'>"+item+"</legend>"
					if (program[program[item][0]]=="button"){
						html = html + "<button class='ui-dform-addElt' type='button' label='"+program[item][0].substring(4)+"'>add "+program[item][0].substring(4)+"</button>"
					}
					html = html + "</fieldset>"
			}else if (program[item]=="button") {
				html = html + "<button class='ui-dform-addElt' type='button' label='"+item.substring(4)+"'>add "+item.substring(4)+"</button>"
			}else if (program[item]=="hidden") {
				html = html + "<input type="+program[item]+" name='"+tableLabel+"["+id+"]."+item+"' value='"+id+"'></input>"
			}else{
				html = html + "<label class='ui-dform-label'>"+item+"</label><input type="+program[item]+" name='"+tableLabel+"["+id+"]."+item+"'></input>"
			}
		});
	}else{
		html = html + "<label class='ui-dform-label'>"+parentLabel+"</label><input type='text' name='"+tableLabel+"."+parentLabel+"["+id+"]'></input>"
	}
	html = html + "</fieldset>";
	//$(this).after($.dform.createElement({"type":"removeElt"}));
	//alert("adding "+html);
	if ($(this).prevAll("fieldset").length)
	{
		//alert("there is a fieldset")
		$(this).prevAll("fieldset").first().after(html);
	}else{
		//alert("no fieldset")
		if($(this).prevAll("input").length) {
			$(this).prevAll("input").first().after(html);
		}else {
			$(this).prevAll("legend").after(html);
		}
	}
	
	$.each($('.ui-dform-addElt'),function(item,value){
			if (!$.data(value,'events')) {
				$(value).click(clickBasicButton);
			}
	});
	
	//$(".ui-dform-addElt").each(function(){click(clickBasicButton)
	//
	//	$(this).prev().after($(this).prev().prev().clone());
}


 function isArray(obj) {
        	//returns true is it is an array
        	//alert("is object an array "+obj)
        	if (obj.constructor.toString().indexOf("Array") == -1)
        		return false;
        	else
        		return true;
        };


function fromBackendToFrontEnd(configFile) {
	//alert("function called");
	
	$.dform.addType("addElt", function(option) {
			return $("<button type='button'>").dformAttr(option).html("add "+option["label"])		
		});
	$.dform.addType("removeElt", function(option) {
			return $("<button type='button'>").dformAttr(option).html("remove "+option["label"])		
		});
	
	
		
	$.dform.subscribe("alert", function(option, type) {
			//alert("message alert "+type);
			if (type=="add")
			{
				this.click(function (){
					//alert(option +" "+ $(this).prev().prev().text());
					$(this).prev().after($(this).prev().prev().clone());
				});
			};
			if (type=="removeElt"){
				alert("todo");	
			}
			if (type=="addElt")
			{
				this.click(clickBasicButton);
			};
	});
		
	
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
        
        
        
        function configToForm(item,elt,id_prefix){
        	program[item].forEach(function (sub_item){
        			//alert("for "+sub_item);
        			if (!isArray(program[sub_item]))
        			{
        				//alert("add item ");
        				if (program[sub_item]!="button"){
						elt["elements"].push(
							{
								"name":id_prefix+"."+sub_item,
								"caption": sub_item,
								"type": program[sub_item]
							});
					} else {
						elt["elements"].push({
        						"type":"addElt",
        						"alert":"add message",
        						"label": sub_item.substring(4)
        					});	
					}
        			}else{
        				//alert("add fieldset "+sub_item)
        				var myelt = {
        					"type":"fieldset",
        					"caption": sub_item,
        					"name": id_prefix+"."+sub_item,
        					"elements": []
        				};
        				//alert("start recursive call "+sub_item);
        				configToForm(sub_item,myelt,id_prefix+"."+sub_item);
        				elt["elements"].push(myelt);
        		}
        	});
        }
        
        //echo "something"
        configToForm("program",myform, "program");
        
        /*
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
        });*/
          
        myform["elements"].push({
                        "type": "submit",
                        "value": "Save"
                })
        //alert("myform ready:"+myform)
        return myform;
}

