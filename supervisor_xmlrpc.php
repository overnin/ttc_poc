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
		new xmlrpcval('yo_transport'),
		new xmlrpcval($worker_config->program->name), 
		new xmlrpcval (
			array( 
				'command' => new xmlrpcval("twistd --pidfile=./tmp/pids/%(program_name)s_%(process_num)s.pid -n start_worker --vhost=/develop --worker-class=vumi.workers.ttc.TtcGenericWorker2 --set-option=transport_name:".$worker_config->program->name),
				//'command' => new xmlrpcval("ls -l"),
				'autostart' => new xmlrpcval("true"),
				'autorestart' => new xmlrpcval("true"),
				'startsecs' => new xmlrpcval("0"),
				'numprocs' => new xmlrpcval("1"),
				'stdout_logfile' => new xmlrpcval("./logs/%(program_name)s_%(process_num)s.log"),
				'stderr_logfile' => new xmlrpcval("./logs/%(program_name)s_%(process_num)s.err")
			),"struct")
		);
	
	
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

function removeWorker($name){
	require_once('lib/xmlrpc-3.0.0.beta/xmlrpc.inc');
	
	$c=new xmlrpc_client("/RPC2", "localhost",9010);
	
	//$c->setDebug(1);
	
	$val = array(
		new xmlrpcval('echo_worker'),
		new xmlrpcval($name)
		);
	
	
	$f=new xmlrpcmsg('twiddler.removeProcessFromGroup', $val);
	
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

function stopWorker($name){
	require_once('lib/xmlrpc-3.0.0.beta/xmlrpc.inc');
	
	$c=new xmlrpc_client("/RPC2", "localhost",9010);
	
	//$c->setDebug(1);
	
	$val = array(
		new xmlrpcval('echo_worker:'.$name)
		);
	
	$f=new xmlrpcmsg('supervisor.stopProcess', $val);
	
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
