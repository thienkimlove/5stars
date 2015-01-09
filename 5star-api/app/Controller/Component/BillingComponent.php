<?php   
class BillingComponent extends Component {       
    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
        $this->controller = $collection->getController();  
        $this->Payment = ClassRegistry::init('Payment');
        $this->Game = ClassRegistry::init('Game');
    }
    public function processPayment($params) {            
        $params['card_vendor'] = strtolower($params['card_vendor']);
        switch($params['channel_id']) {    
            //test payment (always pass)            
            case "100":
                //$params = $this->_payDirectPaymentGateway($params);
                $params['payment_status'] = 1;
                $params['amount'] = 1000;
                break;
                //apota main payment.
            case "2" :      
                $params = $this->_apotaPaymentGateway($params);
                break;              

            case "102":
                $params = $this->_mworkPaymentGateway($params);
                break;  
            case "107":
                $params = $this->_hdcPaymentGateway($params);
                break;    
            case "108":
                $params = $this->_ewayPaymentGateway($params);
                break;          
                //channel tu doanh. (ket noi payment ABC)
            default:

                //$params = $this->_abcPaymentGateway($params);
                //khoa payment hien tai
               /* if (strtolower($params['card_vendor']) == 'viettel') {
                    $params['card_vendor'] = strtolower($params['card_vendor']);
                    $params = $this->_abcPaymentGateway($params);
                } else {
                    $params = $this->_vnepayPaymentGateway($params);
                }      */
                //dung payment cua paydirect 2014-10-17
                $params = $this->_payDirectPaymentGateway($params);
                

                //send to Tinhvan Log.

                if ($params['channel_id'] == 113 && $params['payment_status'] == 1) {
                    
                    if ($params['card_vendor'] == 'mobifone') {
                        $telco = 'VMS';
                    }
                    if ($params['card_vendor'] == 'viettel') {
                        $telco = 'VTE';
                    }
                    if ($params['card_vendor'] == 'vinaphone') {
                        $telco = 'VNP';
                    }
                    $transID = md5(time());
                    $Sign = md5('StarsgaMeTvT(@!22'.$transID. $telco.$params['card_code'].$params['card_serial'].$params['sub_id']);
                    try{
                        $tinhvanUrl = 'http://payment.vimob.vn/payment/dis/starsGame';
                        $post = array(
                            'Trans_ID' => $transID,
                            'Game_ID' => $params['game_id'],
                            'Card_type' => $telco,
                            'Pin_card' => $params['card_code'],
                            'Serial_card' => $params['card_serial'],
                            'Card_Amount' => $params['amount'],
                            'SubID' => $params['sub_id'],
                            'UserID' => $params['user_id'],
                            'Create_Date' => date('Y-m-d H:i:s'),
                            'Sign' => $Sign                       
                        );                                         
                        $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
                        $response = $HttpSocket->post($tinhvanUrl, $post);
                    } catch(Exception $e) {
                        $this->log($e->getMessage());
                    }
                }           

                break;                  
        }            
        return $params;
    }

    public function sendPaymentToGame($payment, $debug = 0) {
        //waiting for GMo API.
        $sendPayment = $payment['Payment'];
        $this->Game->recursive = -1;            
        $game = $this->Game->findById($sendPayment['game_id']);            
        $amountSendToGame =  $game['Game']['exchange_rate'] * $sendPayment['amount'] ; 
        $sendPayment['server_id'] = ($sendPayment['server_id'])? $sendPayment['server_id'] : "";

        $postData =  array(
            'Amount' => $amountSendToGame,
            'id' => $sendPayment['user_id'],
            'orderId' => $sendPayment['id'],
            'serverId' => $sendPayment['server_id']
        );

        $str = '?';

        foreach ($postData as $key => $value) {
            $str .= "&".$key.'='.$value;
        }

        $string = trim($game['Game']['security_key']).$str;

        $postData['sign'] = md5($string);
        $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
        try {
            $response = $HttpSocket->post($game['Game']['billing_url'], json_encode($postData), array('header' => array('Content-Type' => 'application/json')));
            $res_debug =  $response->body;
            $response = json_decode($response->body);
            $this->Payment->id = $sendPayment['id']; 
            $this->Payment->saveField('game_billing_response', $string);
            $status = (int) $response->status;
            if ($status === 0) {
                $this->Payment->saveField('send_game_status', 1);
                $this->Payment->saveField('cron', null);
            } else {
                //add to cron.                
                $this->Payment->saveField('send_game_status', 0);
                $this->Payment->saveField('cron', null);
                $this->Payment->saveField('game_billing_response', $res_debug);
            }

            if ($debug == 1) {
                return $response;
            }
        }  catch (Exception $e) {
            $this->Payment->id = $sendPayment['id']; 
            $this->Payment->saveField('game_billing_response', $e->getMessage());
            $this->Payment->saveField('cron', 1);
            if ($debug == 1) {
                return $e->getMessage();
            }
        }
    }


    private function _payDirectPaymentGateway($params) {

        $cardUrl = 'http://202.160.125.55/voucher/useCard.html';
        $detailsUrl = 'http://202.160.125.55/voucher/getTransactionDetail.html';

        $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));

        $username = '5STAR';
        $password = 'fivestar@2014';
        $secrect = 'sk_5star$123';

        if ($params['card_vendor'] == 'mobifone') {
            $telco = 'MOBI';
        }

        if ($params['card_vendor'] == 'vinaphone') {
            $telco = 'VINA';
        }

        if ($params['card_vendor'] == 'viettel') {
            $telco = 'VT';
        }

        $time = date("YmdHms");

        $post = array(
            'issuer' => $telco,
            'cardSerial' => $params['card_serial'],
            'cardCode' => $params['card_code'],
            'transRef' => $time,
            'partnerCode' => $username,
            'password' => $password,
            'signature' => md5($telco.$params['card_code'].$time.$username.$password.$secrect)
        );

        try {
            $response = $HttpSocket->post($cardUrl, $post);
            if ($response->body) {

                $res = explode('|', $response->body);
                if (!empty($res[0]) && $res[0] == '01') {
                    $params['payment_status'] = 1;
                    $params['amount'] = (int) $res[2];  
                }                  
            }  
            $params['payment_message'] = $response;  
        }
        catch(Exception $e) {                
            $params['payment_message'] = $e->getMessage();
        }
        return $params;
    }

    private function _vnepayPaymentGateway($params) {
        App::import('Vendor', 'vnepay/libs/nusoap');
        App::import('Vendor', 'vnepay/Entries');
        $webservice = "http://charging-service.megapay.net.vn/CardChargingGW_V2.0/services/Services?wsdl";
        $soapClient = new SoapClient(null, array('location' => $webservice, 'uri' => "http://113.161.78.134/VNPTEPAY/"));

        $m_PartnerID = "5stars";
        $m_MPIN = "uwlldodzv";
        $m_UserName = "5stars";
        $m_Pass = "ztehhnlcx";
        $m_PartnerCode = "00514";
        //Ten tai khoan nguoi dung tren he thong doi tac
        if($params['card_vendor'] == 'vinaphone'){
            $m_Target = substr(md5(time()),0,12);
        }else{
            $m_Target = "useraccount1";
        }
        if ($params['card_vendor'] == 'mobifone') {
            $telco = 'VMS';
        }

        if ($params['card_vendor'] == 'vinaphone') {
            $telco = 'VNP';
        }

        $CardCharging = new CardCharging();
        $CardCharging->m_UserName = $m_UserName;
        $CardCharging->m_PartnerID = $m_PartnerID;
        $CardCharging->m_MPIN = $m_MPIN;
        $CardCharging->m_Target = $m_Target;
        $CardCharging->m_Card_DATA = $params['card_serial'].":".$params['card_code'].":"."0".":".$telco;
        $CardCharging->m_SessionID = "";
        $CardCharging->m_Pass  = $m_Pass;
        $CardCharging->soapClient = $soapClient;

        $transid = $m_PartnerCode.date("YmdHms");//gen transaction id

        $CardCharging -> m_TransID = $transid;

        $CardChargingResponse = new CardChargingResponse();
        $CardChargingResponse = $CardCharging->CardCharging_();
        if ((!empty($CardChargingResponse->m_Status)) && ($CardChargingResponse->m_Status == 1)) {
            $params['payment_status'] = 1;
            $params['amount'] = (int) $CardChargingResponse->m_RESPONSEAMOUNT;
        }
        ob_start();
        echo "<pre>";
        print_r($CardChargingResponse);
        $str = ob_get_clean();

        $params['payment_log'] = $str;

        //send to Tinhvan Log.
        if ($params['channel_id'] == 113 && $params['payment_status'] == 1) {
            $transID = md5(time());
            $Sign = md5('StarsgaMeTvT(@!22'.$transID. $telco.$params['card_code'].$params['card_serial'].$params['sub_id']);
            try{
                $tinhvanUrl = 'http://payment.vimob.vn/payment/dis/starsGame';
                $post = array(
                    'Trans_ID' => $transID,
                    'Game_ID' => $params['game_id'],
                    'Card_type' => $telco,
                    'Pin_card' => $params['card_code'],
                    'Serial_card' => $params['card_serial'],
                    'Card_Amount' => $params['amount'],
                    'SubID' => $params['sub_id'],
                    'UserID' => $params['user_id'],
                    'Create_Date' => date('Y-m-d H:i:s'),
                    'Sign' => $Sign                       
                );
                $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
                $response = $HttpSocket->post($tinhvanUrl, $post);
            } catch(Exception $e) {
                $this->log($e->getMessage());
            }
        }           

        return $params;
    }  

    private function _sohaPaymentGateway($params) {
        $url = 'http://soap.soha.vn/api/a/GET/order/status/'.$params['order_id'];
        try {
            $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
            $response = $HttpSocket->get($url);
            if ($response->body) {
                $result = json_decode($response->body);
                if (isset($result->status) && $result->status == 'settled') {
                    $params['payment_status'] = 1;
                    $params['amount'] = (int) $result->amount;
                }
            }
            $params['payment_log'] = $response->body;
        } catch (Exception $e) {
            $params['payment_message'] = $e->getMessage();
            $params['payment_log'] = $url;
        }
        return $params;
    }

    private function _ewayPaymentGateway($params) {            

        $url = 'http://mway.vn:89/SystemService/maphap/Charge';

        if ($params['card_vendor'] == 'mobifone') {
            $telco = 'VMS';
        }

        if ($params['card_vendor'] == 'vinaphone') {
            $telco = 'VNP';
        }

        if ($params['card_vendor'] == 'viettel') {
            $telco = 'VTE';
        }

        $postParams = array(
            'partner' => 'maphap',
            'username' => '5starsuser',
            'refcode' => ($params['sub_id']) ? $params['sub_id'] : 'N/A',
            'game' => $params['game_id'],
            'type' => $telco,
            'serial' => $params['card_serial'],
            'pin' => $params['card_code']
        );            
        $postParams['sign'] = md5($postParams['partner'].$postParams['username'].$postParams['refcode'].$postParams['game'].$postParams['type'].$postParams['serial'].$postParams['pin'].'e4555gsdGHmaKLmsphapqp102013');

        try {
            $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
            $response = $HttpSocket->post($url, $postParams);

            if ($response->body) {        
                $result = explode('|', trim($response->body));            
                if (isset($result[0]) && ($result[0] == 0)) {
                    $params['payment_status'] = 1;
                    $params['amount'] = (int) $result[1];
                } else {
                    $params['payment_message'] = $result[1];
                }
            }                
            $params['payment_log'] = $response->body;

        } catch(Exception $e) {
            $params['payment_message'] = $e->getMessage();
            $params['payment_log'] = $url;
        }
        return $params;
    }


    private function _hdcPaymentGateway($params) {		

        App::import('Vendor', 'nusoap', array('file' => 'nusoap'.DS.'lib'.DS.'nusoap.php'));

        $url = 'http://taoviec.com/service/ReceiveCard.php?wsdl';

        if ($params['card_vendor'] == 'mobifone') {
            $telco = 'VMS';
        }

        if ($params['card_vendor'] == 'vinaphone') {
            $telco = 'VNP';
        }

        if ($params['card_vendor'] == 'viettel') {
            $telco = 'VTT';
        }

        $client = new nusoap_client($url);     
        $postParams = array(
            'game_user' => '5starsuser',             
            'refCode' => ($params['sub_id']) ? $params['sub_id'] : 'N/A',              
            'provider_code' => $telco,
            'card_seri' => $params['card_code'],
            'card_code' => $params['card_serial'],
            'token' => '218ca85370d0711639d6b676d2f997f9',
            'test' => 0
        );            

        if ($params['game_id'] == 3 || $params['game_id'] == 5) {
            //token ba khi
            $postParams['token'] = 'ec7553282f33859c8e5d46be74caae11';
        } else {
            //token maphap
            $postParams['token'] = '218ca85370d0711639d6b676d2f997f9';
        }
        try {
            $result = $client->call('call', $postParams);  

            if ($result) {		
                $result = explode('|', trim($result));            
                if (isset($result[0]) && ($result[0] == 1)) {
                    $params['payment_status'] = 1;
                    $params['amount'] = (int) $result[1];
                } else {
                    $params['payment_message'] = json_encode($result[1]);
                }
            }				
            $params['payment_log'] = json_encode($result);

        } catch(Exception $e) {
            $params['payment_message'] = $e->getMessage();
            $params['payment_log'] = $url;
        }
        return $params;
    }

    private function _mworkPaymentGateway($params) {            
        if (!in_array($params['card_vendor'], array('mobifone', 'viettel', 'vinaphone'))) {
            $params['payment_message'] = ' Loai the nay khong support voi hinh thuc thanh toan TELCOS';
            return $params;
        }
        $merchant_id= '103807544923694864309';
        $apikey= '335b0e094a78f930bf3eeb3e0c35c17a';
        //bakhi.
        if ($params['game_id'] == 3 || $params['game_id'] == 5) {
            $appid = '146601144627313745974';
        } else {
            $appid = '143407944889478917024';
        }
        $url = 'http://api.mwork.vn/card-charging/charge?pin='.$params['card_code'].'&serial='.$params['card_serial'].'&type='.$params['card_vendor'].'&merchant_id='.$merchant_id.'&apikey='.$apikey.'&refcode='.urlencode($params['sub_id']).'&provider_id='.$params['channel_id'].'&appid='.$appid;

        try {
            $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
            $response = $HttpSocket->get($url);

            if ($response->body) {        
                $result = json_decode($response->body);            
                if (!empty($result->status) && ($result->status == 'COMPLETED')) {
                    $params['payment_status'] = 1;
                    $params['amount'] = (int) $result->amount;
                } 
            }                
            $params['payment_log'] = $response->body;

        } catch(Exception $e) {
            $params['payment_message'] = $e->getMessage();
            $params['payment_log'] = $url;
        }
        return $params;
    }

    private function _abcPaymentGateway($params) {

        if (!in_array($params['card_vendor'], array('mobifone', 'viettel', 'vinaphone'))) {
            $params['payment_message'] = ' Loai the nay khong support voi hinh thuc thanh toan TELCOS';
            return $params;
        }
        if ($params['card_vendor'] == 'mobifone') {
            $telco = 'VMS';
        }
        if ($params['card_vendor'] == 'viettel') {
            $telco = 'VTE';
        }
        if ($params['card_vendor'] == 'vinaphone') {
            $telco = 'VNP';
        }
        $url = 'http://abcpay.vn/PayGate/ScratchCard?p=';
        //prepare data.
        $email = 'info@5stars.vn';
        $phone = '0903347191';

        //api information.
        $apiUsername = '5STAR';
        $apiPassword = '2e307f92c608ce2171cd0f855048af21';
        $apiKey = 'abc.fivestar.2013';

        $data = $apiUsername.':'.$apiPassword.':'.$telco.':'.$params['card_serial'].':'.$params['card_code'].':'.$phone.':'.$email;
        $encryptedData = $this->_tripleEncrypt($data, $apiKey);            
        $encodeUrl = urlencode($encryptedData);

        try {
            $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
            $response = $HttpSocket->get($url.$encodeUrl);
            $params['payment_log'] = $url.$encodeUrl;
            if ($response->body) {
                $xmlArray = Xml::toArray(Xml::build('<?xml version="1.0"?>'.$response->body));
                if (!empty($xmlArray['response']['balance'])) {
                    if ($xmlArray['response']['balance'] != '0.0') {
                        $params['payment_status'] = 1;
                        $params['amount'] =  (int) $xmlArray['response']['balance'];
                    } else {
                        $params['payment_code'] = $xmlArray['response']['status'];
                        $params['payment_message'] = $xmlArray['response']['message'];
                    }
                } else {
                    $params['payment_code'] = 100;
                    $params['payment_message'] = json_encode($response->body);
                }      
            } else {
                $params['payment_message'] = 'Khong co thong tin tra ve . Response code :'. $response->code;
            }
        } catch (Exception $e) {
            $params['payment_message'] = $e->getMessage(); 
        } 

        return $params;
    }

    private function _apotaPaymentGateway($params) {  
        $apiKey = '';    
        switch($params['game_id']) {
            case "1":
                $apiKey = '83211b0908bac559ca1cd746a1698b36051e7b1ec';
                break;
            case "2":
                $apiKey = 'cc93283b85d3cba971cc5c9413633f46052201841';
                break;
            case "3":
                $apiKey = '67a8f2ed91298421eefcfae7e3fe3ece052848a7f';
                break;
            case "5":
                $apiKey = '7697b66836e1b4c48db4b07c18e36880052848b1a';
                break;

            case "6":
                $apiKey = '45df23e37727bc8d4cebee372de4215f054069feb';
                break;

            case "7":
            $apiKey = '629dd91b5909035c7631014b7b4ef074054068f47';
            break;
        }      
        try {
            $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));          
            $postUrl = 'https://api.appota.com/payment/inapp_card?api_key='.$apiKey.'&lang=vi';  


            $response = $HttpSocket->post($postUrl,array(
                'vendor' => $params['card_vendor'],
                'card_code' => $params['card_code'],
                'card_serial' => $params['card_serial'],
                'direct' => 1,
                'state' => '',
                'target' =>'',
                'notice_url' => ''
            ));

            $params['payment_log'] = $response->body;
            if ($response->body) {  
                $res = json_decode($response->body);                        
                if ($res->status === true) {
                    if ($res->error_code) {                            
                        $params['payment_message'] = $res->message;
                        $params['payment_code'] = $res->error_code;
                    } else {
                        $params['amount'] = $res->data->amount;
                        $params['payment_status'] = 1;
                    }                                                 
                } else {                        
                    $params['payment_message'] = $res->message;
                    $params['payment_code'] = $res->error_code;
                }                                        
            }
        }
        catch (Exception $e) {
            $params['payment_message'] = $e->getMessage();
        }
        return $params;
    }

    private function _tripleEncrypt($input, $key_seed) 
    {  
        $input = trim($input);  
        $block = mcrypt_get_block_size('tripledes', 'ecb');  
        $len = strlen($input);  
        $padding = $block - ($len % $block);  
        $input .= str_repeat(chr($padding),$padding);  
        // generate a 24 byte key from the md5 of the seed  
        $key = substr(md5($key_seed),0,24);  
        $iv_size = mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB);  
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
        // encrypt  
        $encrypted_data = mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $input,  
            MCRYPT_MODE_ECB, $iv);
        // clean up output and return base64 encoded  
        return base64_encode($encrypted_data);  
    } //end function Encrypt()

    private function _tripleDecrypt($input, $key_seed)  
    {  
        $input = base64_decode($input);  
        $key = substr(md5($key_seed),0,24);  
        $text=mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $input, 
            MCRYPT_MODE_ECB,'12345678');  
        $block = mcrypt_get_block_size('tripledes', 'ecb');  
        $packing = ord($text{strlen($text) - 1});  
        if($packing and ($packing < $block)){  
            for($P = strlen($text) - 1; $P >= strlen($text) - $packing; $P--){  
                if(ord($text{$P}) != $packing){  
                    $packing = 0;  
                }  
            }  
        }  
        $text = substr($text,0,strlen($text) - $packing);  
        return $text;  
    }  


}
