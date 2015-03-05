<?php   
	class BillingComponent extends Component {       
		public function __construct(ComponentCollection $collection, $settings = array()) {
			parent::__construct($collection, $settings);
			$this->controller = $collection->getController();  
			$this->Payment = ClassRegistry::init('Payment');
			$this->Game = ClassRegistry::init('Game');
		}
		public function processPayment($channelId, $card_code, $card_serial, $card_vendor) {
			switch($channelId) {    
				//test payment (always pass)            
				case "100":
					$responseData = array(
						'status' => false,
						'amount' => 0,
						'payment_log' => null,
						'payment_code' => null,
						'payment_message' => null
					);
					$responseData['status'] = true;
					$responseData['amount'] = 100000;
					break;
					//apota main payment.
				case "2" :      
					$responseData = $this->_apotaPaymentGateway($card_vendor, $card_code, $card_serial);
					break;
					//channel tu doanh. (ket noi payment ABC)
				case "1":
					$responseData = $this->_abcPaymentGateway($card_vendor, $card_code, $card_serial);
					break;    
			}
			return $responseData;
		}

		public function sendPaymentToGame($payment, $debug = 0) {
			//waiting for GMo API.
			$sendPayment = $payment['Payment'];
			$this->Game->recursive = -1;            
			$game = $this->Game->findById($sendPayment['game_id']);            
			$amountSendToGame =  $game['Game']['exchange_rate'] * $sendPayment['amount']; 
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
			$postData['sign'] = md5($game['Game']['security_key'].$str);
			$HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
			try {
				$response = $HttpSocket->post($game['Game']['billing_url'], json_encode($postData), array('header' => array('Content-Type' => 'application/json')));
				$response = json_decode($response->body);
				$this->Payment->id = $sendPayment['id']; 
				$this->Payment->saveField('game_billing_response', json_encode($response));
				$status = (int) $response->status;
				if ($status === 0) {
					$this->Payment->saveField('send_game_status', 1);
					$this->Payment->saveField('cron', null);
				} else {
					//add to cron.                
					$this->Payment->saveField('cron', 1);
				}

				if ($debug == 1) {
					return $response;
				}
			}  catch (Exception $e) {
				$this->Payment->id = $sendPayment['id']; 
				$this->Payment->saveField('game_billing_response', $e->getMessage());
				$this->Payment->saveField('cron', 1);
			}
		}

		private function _abcPaymentGateway($card_vendor, $card_code, $card_serial) {

			$responseData = array(
				'status' => false,
				'amount' => 0,
				'payment_log' => null,
				'payment_code' => null,
				'payment_message' => null
			);

			if (!in_array($card_vendor, array('mobifone', 'viettel', 'vinaphone'))) {
				$responseData['payment_message'] = ' Loai the nay khong support voi hinh thuc thanh toan TELCOS';
				return $responseData;
			}
			if ($card_vendor == 'mobifone') {
				$telco = 'VMS';
			}
			if ($card_vendor == 'viettel') {
				$telco = 'VTE';
			}
			if ($card_vendor == 'vinaphone') {
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

			$data = $apiUsername.':'.$apiPassword.':'.$telco.':'.$card_serial.':'.$card_code.':'.$phone.':'.$email;
			$encryptedData = $this->_tripleEncrypt($data, $apiKey);            
			$encodeUrl = urlencode($encryptedData);

			try {
				$HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));
				$response = $HttpSocket->get($url.$encodeUrl);
				$responseData['payment_log'] = $url.$encodeUrl;
				if ($response->body) {
					$xmlArray = Xml::toArray(Xml::build('<?xml version="1.0"?>'.$response->body));
					if (!empty($xmlArray['response']['balance'])) {
						if ($xmlArray['response']['balance'] != '0.0') {
							$responseData['status'] = true;
							$responseData['amount'] =  (int) $xmlArray['response']['balance'];
						} else {
							$responseData['payment_code'] = $xmlArray['response']['status'];
							$responseData['payment_message'] = $xmlArray['response']['message'];
						}
					} else {
						$responseData['payment_code'] = 100;
						$responseData['payment_message'] = json_encode($response->body);
					}      
				} else {
					$responseData['payment_message'] = 'Khong co thong tin tra ve . Response code :'. $response->code;
				}
			} catch (Exception $e) {
				$responseData['payment_message'] = $e->getMessage(); 
			}            
			return $responseData;
		}

		private function _apotaPaymentGateway($card_vendor, $card_code, $card_serial) {
			$responseData = array(
				'status' => false,
				'amount' => 0,
				'payment_log' => null,
				'payment_code' => null,
				'payment_message' => null
			);    

			try {
				$HttpSocket = new HttpSocket(array('ssl_verify_peer' => false));          
				$postUrl = 'https://api.appota.com/payment/inapp_card?api_key=83211b0908bac559ca1cd746a1698b36051e7b1ec&lang=vi';  

				//main API key : 83211b0908bac559ca1cd746a1698b36051e7b1ec
				//test API key :695823cdd44933855d6113e83e6e374c051e7b1ec

				$response = $HttpSocket->post($postUrl,array(
					'vendor' => $card_vendor,
					'card_code' => $card_code,
					'card_serial' => $card_serial,
					'direct' => 1,
					'state' => '',
					'target' =>'',
					'notice_url' => ''
				));

				$responseData['payment_log'] = $response->body;
				if ($response->body) {  
					$res = json_decode($response->body);                        
					if ($res->status === true) {
						if ($res->error_code) {
							$responseData['status'] =  false;
							$responseData['payment_message'] = $res->data->message;
							$responseData['payment_code'] = $res->error_code;
						} else {
							$responseData['amount'] = $res->data->amount;
							$responseData['status'] =  true;
						}                                                 
					} else {
						$responseData['status'] =  false;
						$responseData['payment_message'] = $res->message;
						$responseData['payment_code'] = $res->error_code;
					}                                        
				}
			}
			catch (Exception $e) {
				$responseData['payment_message'] = $e->getMessage();
			}
			return $responseData;
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
