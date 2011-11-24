<?php

function getState(){

	require_once('lib/xmlrpc-3.0.0.beta/xmlrpc.inc');
	
	$f=new xmlrpcmsg('supervisor.getState');
	
	$c=new xmlrpc_client("/RPC2", "localhost",9010);
	
	//$c->setDebug(1);
	
	$r=&$c->send($f);
	
	if(!$r->faultCode())
	{
		$arr = php_xmlrpc_decode($r->value());
		return $arr['statename'];
	}
	else
	{
		return "An error occurred, Code: " . htmlspecialchars($r->faultCode())
			. " Reason: '" . htmlspecialchars($r->faultString()) . "'";
	}
}

function startWorker($config){
	require_once('lib/xmlrpc-3.0.0.beta/xmlrpc.inc');
	
	$c=new xmlrpc_client("/RPC2", "localhost",9010);
	
	//$c->setDebug(1);
	$worker_config = json_decode($config);
	
	print $worker_config->program->name;
	
	$val = array(
		new xmlrpcval('echo_worker'),
		new xmlrpcval($worker_config->program->name), 
		new xmlrpcval (
			array( 
				'command' => new xmlrpcval("twistd -n start_worker --vhost=/develop --worker-class=vumi.workers.ttc.TtcGenericWorker --set-option=transport_name:".$worker_config->program->name),
				'autostart' => new xmlrpcval("true"),
				'autorestart' => new xmlrpcval("false"),
				'startsecs' => new xmlrpcval("0")
			),"struct")
		);
	//echo "array size:". count($val)."<br/>";
	//for ($i = 0; $i < count($val); $i++)
	//	{
	//		$v = $val[$i];
	//		print "Element $i of the array is ".$v->kindOf()."<br/>";
	//	}
	
	
	$f=new xmlrpcmsg('twiddler.addProgramToGroup', $val);
	
	$r=&$c->send($f);
	
	if(!$r->faultCode())
	{
		echo php_xmlrpc_decode($r->value());
	}
	else
	{
		return "An error occurred, Code: " . htmlspecialchars($r->faultCode())
			. " Reason: '" . htmlspecialchars($r->faultString()) . "'";
	}
}

?>
