<?php
	App::uses('AppController', 'Controller');
	/**
	* Payments Controller
	*
	* @property Payment $Payment
	*/
	class PaymentsController extends AppController {
		var $uses = array('Payment');
		/**
		* index method
		*
		* @return void
		*/
		public function index(){
			$user = $this->authenticate();
			if(!$user) {
				throw new ForbiddenException('Chi cho phep khi dang nhap');
			}
			$conditions = array();
			if($this->request->query('day1Payment') && $this->request->query('day2Payment')){
				$conditions = array('Payment.created BETWEEN ? AND ?' => array($this->request->query('day1Payment'),$this->request->query('day2Payment')));
			}
			if($this->request->query('amountStatus')){
				switch ($this->request->query('amountStatus')){
					case "1":
					$conditions['amount >'] =0 ;
					break;
					case "2":
						$conditions['amount'] =0 ;
						break;
				}
			}
			$this->paginate= array('limit'=>20,'order'=>'id','recursive'=>0,'conditions'=>$conditions);
			$count = $this->Payment->find('count',array('order'=>'id','recursive'=>-1,'conditions'=>$conditions));
			$this->set(array('payments'=>$this->paginate(),'count'=>$count,'_serialize'=>array('payments','count')));
		}  
		/**
		* view method
		*
		* @throws NotFoundException
		* @param string $id
		* @return void
		*/
		public function view($id = null) {
			$user = $this->authenticate();
			if (!$this->Payment->exists($id)) {
				throw new NotFoundException(__('Invalid payment'));
			}
			$options = array('conditions' => array('Payment.' . $this->Payment->primaryKey => $id));
			$payment = $this->Payment->find('first', $options);

			$this->set(array(
				'payment' => $payment,
				'_serialize' => array('payment')
			));
		} 
		
		public function add(){
			if ($this->_getParam('userId') && $this->_getParam('card_code') && $this->_getParam('card_serial') && $this->_getParam('card_vendor')) {
				$this->User->unbindAll();
				$user = $this->User->findById($this->_getParam('userId'));
				if (!empty($user)) {                    
					$serverId = ($this->_getParam('serverId', false))? $this->_getParam('serverId') : null;
					$subId = ($this->_getParam('subId', false))? $this->_getParam('subId') : null;
					$responseData = $this->Billing->processPayment($this->_getParam('channelId'),$this->_getParam('card_code'),$this->_getParam('card_serial'), $this->_getParam('card_vendor'));
					$postData = array(
						'user_id' => $this->_getParam('userId'),
						'game_id' => $this->_getParam('gameId'),
						'channel_id' => $this->_getParam('channelId'), 
						'server_id' => $serverId,
						'sub_id' => $subId,                       
						'amount' => $responseData['amount'],
						'payment_info' => json_encode(array('card_code' => $this->_getParam('card_code'), 'card_serial' => $this->_getParam('card_serial'), 'card_vendor' => $this->_getParam('card_vendor'))),
						'send_game_status' => 0,
						'payment_status' => ($responseData['status'] === true)? 1 : 0,
						'payment_log' =>  $responseData['payment_log'],
						'payment_message' =>  $responseData['payment_message'],
						'payment_code' =>  $responseData['payment_code'],
					);                    
					if ($payment = $this->Payment->save($postData)) {
						//if test by demo.5stars.vn dont send the payment to billing NPT.
						if ($responseData['status'] === true) {
							$this->Billing->sendPaymentToGame($payment);
							$this->set(array(
								'payment' =>  $payment,
								'_serialize' => array('payment')
							));
						} else {
							throw new BadRequestException($responseData['payment_message']);
						}
					} else {
						throw new BadRequestException($this->Payment->validationErrors);
					}                    
				} else {
					throw new ForbiddenException('NOt have user Id');
				}
			} else {
				throw new BadRequestException('Not have enough parametters');
			}
		} 
		
		 //this is cron script running
		public function cron()
		{            
			$paymentResend = $this->Payment->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'send_game_status = ' => 0,
					'cron =' => 1
				),
			));
			$count = 0;
			$response = array();
			if ($paymentResend) {
				foreach ($paymentResend as $payment) {
					$log = $this->Billing->sendPaymentToGame($payment, 1);
					$count ++;
					array_push($response, $log);
				}
			}

			$this->set(array(
				'status' =>  'Have '.$count. ' tasks run.',
				'log' => $response,
				'_serialize' => array('status', 'log')
			));
		}

	}
