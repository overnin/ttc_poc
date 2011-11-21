<?php

$r = new HttpRequest('http://localhost:9021/sms', HttpRequest::METH_GET);
$r->addQueryData(array('content' => $_GET["content"],
			'to_addr' => $_GET["to"],
			'from_addr' => $_GET["from"]));
try {
    $r->send();
    echo "<br>request send: ".$r->getUrl();
    if ($r->getResponseCode() == 200) {
    	    echo "<br>response: ".$r->getResponseBody();
    }
} catch (HttpException $ex) {
    echo $ex;
}

?>
