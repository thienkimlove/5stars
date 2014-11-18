
<?php
	
	//CardCharging

	//CardCharging($soapClient);
				
	function CardCharging($soapClient, $serial, $pin, $serviceProvider)
	{
	//if($CardCharging == null)
	//$CardCharging = new CardCharging();
	$m_PartnerID = "5stars";
	$m_MPIN = "ciuwnvbhl";
	$m_UserName = "5stars";
	$m_Pass = "napkwiaix";
	$m_PartnerCode = "00400";
	//Ten tai khoan nguoi dung tren he thong doi tac
	$m_Target = "useraccount1";
		
		
	$CardCharging = new CardCharging();
	$CardCharging->m_UserName = $m_UserName;
	$CardCharging->m_PartnerID = $m_PartnerID;
	$CardCharging->m_MPIN = $m_MPIN;
	$CardCharging->m_Target = $m_Target;
	$CardCharging->m_Card_DATA = $serial.":".$pin.":"."0".":".$serviceProvider;
	$CardCharging->m_SessionID = "";
	$CardCharging->m_Pass  = $m_Pass;
	$CardCharging->soapClient = $soapClient;

	$transid = $m_PartnerCode.date("YmdHms");//gen transaction id

	$CardCharging -> m_TransID = $transid;
	echo "<br/>Transaction id: ".$transid."<br/>";		
	//var_dump($CardCharging);
	//print_r($CardCharging -> m_SessionID);
	$CardChargingResponse = new CardChargingResponse();
	$CardChargingResponse = $CardCharging->CardCharging_();
//	print_r($CardChargingResponse);
	print_r("Ket qua thuc hien giao dich: ");
	echo "</br>";
	print_r("Trang thai giao dich: ".$CardChargingResponse->m_Status);
	echo "</br>";
	print_r("Menh gia tra ve: ".$CardChargingResponse->m_RESPONSEAMOUNT);
	echo "</br>";
	print_r("Transaction id: ".$CardChargingResponse->m_TRANSID);
	echo "<br/>----------------Ket thuc charging-----------------------<br/>";							
	}		
?>
