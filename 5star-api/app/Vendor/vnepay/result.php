<?php
$provider = $_POST["provider"];
$cardpin = $_POST["pin"];
$serial = $_POST["serial"];


	require_once("libs/nusoap.php");	
	include("Entries.php");	
	//set_time_limit(0);	
	// load SOAP library		

	//$webservice = "http://183.91.14.43:8080/CardChargingGW/services/Services?wsdl";
	$webservice = "http://charging-test.megapay.net.vn:10001/CardChargingGW_V2.0/services/Services?wsdl";
	$soapClient = new SoapClient(null, array('location' => $webservice, 'uri' => "http://113.161.78.134/VNPTEPAY/"));
	
	require_once('home_.php');

?>

<table>
<tr>
<td>
<?php CardCharging($soapClient,$serial,$cardpin,$provider);?>
</td>
</tr>
</table>