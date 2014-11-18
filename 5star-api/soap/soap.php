<?php
    require_once("nusoap.php");
    $server = new soap_server();
    $server->configureWSDL("messageReceiverWSDL ","urn:messageReceiver WSDL");

    $server->register("messageReceiver",array("RequestID" => "xsd:string", "UserID" => "xsd:string", "ReceiverID" => "xsd:string", "ServiceID" => "xsd:string", "CommandCode" => "xsd:string", "Info" => "xsd:string", "ReceiveTime" => "xsd:string", "username" => "xsd:string", "password" => "xsd:string"),array("return" => "xsd:string"),"urn:messageReceiver","urn:messageReceiver#messageReceiver");

    function messageReceiver($RequestID, $UserID, $ReceiverID, $ServiceID, $CommandCode, $Info, $ReceiveTime, $username, $password) {
        if ($username == '5stars-sub-daily' && $password == '5stars@gdata1234' && $CommandCode) {
            $con = mysql_connect('localhost', 'api_game', 'MrhAmt2UVBVbXJCN', 'api_game'); 
            if (mysql_connect_errno($con)) {
                return "0|0| Xin thu lai sau.";
            } else {
                $sql = "INSERT into subscriptions set 
                request_id = '".mysql_real_escape_string($RequestID)."',
                user_id = '".mysql_real_escape_string($UserID)."', 
                receive_id = '".mysql_real_escape_string($ReceiverID)."',
                service_id = '".mysql_real_escape_string($ServiceID)."', 
                command_code = '".mysql_real_escape_string($CommandCode)."',
                info = '".mysql_real_escape_string($Info)."',
                receive_time = '".mysql_real_escape_string($ReceiveTime)."'
                ";
                if (mysql_query($con,$sql)) {
                    return "1|0| Ban da dang ky thanh cong thue bao VIP cua Game Ba Khi Giang Ho. He thong se tu dong dang ky VIP cho ban vao tuan tiep theo! Xin cam on!";
                }
                else {
                    return "0|0| Xin thu lai sau.";
                }
            }
            mysql_close($con);
        } else {
            return "0|0| Khong du tham so yeu cau hoac mat khau khong hop le.";
        }
    }

    $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
    $server->service($HTTP_RAW_POST_DATA);
?>
