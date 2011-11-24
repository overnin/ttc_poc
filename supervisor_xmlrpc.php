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

?>
