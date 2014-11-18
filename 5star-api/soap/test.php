<?php
    require_once 'nusoap.php';
    $client = new nusoap_client('http://localhost/trunk/5star-api/soap/soap.php');
    $result = $client->call('messageReceiver', array('RequestID' => 'test', 'ReceiverID' => 'test2'));
    print_r($result);
?>
