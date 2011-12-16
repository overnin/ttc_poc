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
	"participants":["add-participant"],
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
	"interaction":["radio-type-interaction","radio-type-schedule","interaction_id"],
	"interaction_id":"hidden",
	"add-interaction":"button",
	"announcement": ["content"],
	"question-answer": ["content","keyword", "add-answer"],
	"add-answer": "button",
	"answer": ["choice","feedback", "action"],
	"choice":"text",
	"action":"text",
	"add-request-reply":'button',
	"request-reply":["keyword","feedback","action"],
	"id":"text",
	"type":"text",
	"radio-type-interaction":"radiobuttons",
	"type-interaction": {"announcement":"Announcement","question-answer":"Question","request-response":"Request-Response"},
	"radio-type-schedule":"radiobuttons",
	"type-schedule": {"immediately":"Immediately","fixedtime":"Fixed time","delta":"Wait"},
	"content":"text",
	"date": "text",
	"fixedtime":["time"],
	"delta":["time"],
	"time": "text",
	"keyword":"text",
	"action":"text",
	"feedback":"text"
};

function saveFormOnServer(){
		
	var formData = form2js('generic-worker-form-dynamic', '.', true);
	//alert();
	var indata= "description="+JSON.stringify(formData, null, '\t');
		
	$("#testArea").text(indata);
		
	$.get('create_ttc_worker.php',indata, function(data) {
		$("#result").html(data);
	});
}
	

function clickBasicButton(){
					
	//alert("click on add element "+$(this).prev('legend'));
	var id = $(this).prevAll("fieldset").length;
	var eltLabel = $(this).attr('label');
	var tableLabel = $(this).parent().attr('name');
	var parent = $(this).parent();
	
	var expandedElt = {"type":"fieldset","name":tableLabel+"["+id+"]","caption":eltLabel,"elements":[]}
		
	configToForm(eltLabel, expandedElt,tableLabel+"["+id+"]");
	
	$(parent).formElement(expandedElt);
	
	$(this).clone().appendTo($(parent));
	$(this).remove();
	
	activeForm();
	
};

function activeForm(){
	$.each($('.ui-dform-addElt'),function(item,value){
			if (!$.data(value,'events')) {
				$(value).click(clickBasicButton);
			}
	});
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
}


function isArray(obj) {
	if (obj.constructor.toString().indexOf("Array") == -1)
		return false;
	return true;
};
        
function updateRadioButtonSubmenu() {
	//var elt = event.currentTarget;
	var elt = this;
	var box = $(elt).parent().children("fieldset"); 
	if (box.length){
		$(box).remove();
	}
	
	box = {"type":"fieldset","elements":[]};
	
	configToForm($(elt).attr('value'), box,$(elt).parent().parent().attr('name'));
	
	$(elt).parent().formElement(box);
	
	activeForm();
};


function configToForm(item,elt,id_prefix,configTree){
	program[item].forEach(function (sub_item){
			//alert("for "+sub_item);
			if (!isArray(program[sub_item]))
			{
				//alert("add item ");
				if (program[sub_item]=="button"){
					var label = sub_item.substring(4);
					//populate form
					if (configTree && configTree.length>0){
						var i = 0;
						configTree.forEach(function (configElt){
								var myelt = {
									"type":"fieldset",
									"caption": label +" "+ i,
									"name": id_prefix+"["+i+"]",
									"elements": []
								};
								configToForm(label,myelt,id_prefix+"["+i+"]",configElt);
								i = i + 1;
								elt["elements"].push(myelt);
						});
						
					}
					elt["elements"].push({
						"type":"addElt",
						"alert":"add message",
						"label": label
					});
				} else {
					if (program[sub_item]=="radiobuttons"){
						var radio_type = sub_item.substring(6);
					
						elt["elements"].push(
						{
							"name":id_prefix+"."+radio_type,
							"caption": label,
							"type": program[sub_item],
							//"class": "labellist",
							"options": program[radio_type] 
						});
					
						
					}else{	
						var eltValue = "";
						if (configTree) {
							eltValue = configTree[sub_item];
						}
						var label = null;
						if (program[sub_item]!="hidden"){
							label = sub_item;
						}
						elt["elements"].push(
							{
								"name":id_prefix+"."+sub_item,
								"caption": label,
								"type": program[sub_item],
								"value": eltValue
							});
					}
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
				if (configTree) {
					configToForm(sub_item,myelt,id_prefix+"."+sub_item, configTree[sub_item]);
				} else {
					configToForm(sub_item,myelt,id_prefix+"."+sub_item);
				}
				elt["elements"].push(myelt);
		}
	});
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
		
	
	var myform = {
		"action": "javascript:saveFormOnServer()",
		"method": "post",
                "elements": 
                [{
                        "type": "p",
                        "html": "Program"
                }]
        };
        
        
        
        
        
        //echo "something"
        configToForm("program",myform, "program", configFile);
        
          
        myform["elements"].push({
                        "type": "submit",
                        "value": "Save"
                })
        //alert("myform ready:"+myform)
        return myform;
}

