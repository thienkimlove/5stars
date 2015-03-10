<?php
App::uses('AppController', 'Controller');    
/**
* Payments Controller
*
* @property Payment $Payment
*/
class PaymentsController extends AppController {
    var $uses = array('Payment', 'Order', 'Gift', 'Game', 'Channel', 'Flag', 'Store');
    /**
    * index method
    *
    * @return void
    */
    public function index() {
        //not allow normail user.
        $user = $this->crmPermission();
        $this->Payment->recursive = 0;			
        // $options = $this->Payment->buildOptions($this->params->query);
        $options = array();

        if (!empty($this->params->query['start_date'])) {
            $options['conditions']['Payment.created > '] = $this->params->query['start_date'];
        }
        if (!empty($this->params->query['end_date'])) {
            $options['conditions']['Payment.created < '] = $this->params->query['end_date'];
        }
        if (!empty($this->params->query['search'])) {
            $options['conditions']['User.username = '] = $this->params->query['search'];
        }

        if (!empty($this->params->query['channel_id'])) {
            $options['conditions']['Payment.channel_id = '] = $this->params->query['channel_id'];
        }

        if (!empty($this->params->query['game_id'])) {
            $options['conditions']['Payment.game_id = '] = $this->params->query['game_id'];
        }

        if (!empty($this->params->query['send_game_status'])) {
            $options['conditions']['Payment.send_game_status = '] = $this->params->query['send_game_status'];
        }

        if (!empty($this->params->query['amount'])) {
            $options['conditions']['Payment.amount = '] = $this->params->query['amount'];
        }
        if ($user['User']['role'] == 'channel') {                
            $this->Channel->recursive = -1;
            $channel = $this->Channel->findByUserId($user['User']['id']);                
            $options['conditions']['Payment.channel_id = '] = $channel['Channel']['id'];
        }
        if ($user['User']['role'] == 'game') {                
            $this->Game->recursive = -1;
            $game = $this->Game->findByUserId($user['User']['id']);                
            $options['conditions']['Payment.game_id = '] = $game['Game']['id'];
        }

        $this->Payment->virtualFields = array(
            'count' => 'COUNT(*)',
            'count_amount' => 'SUM(Payment.amount)'
        );

        $options['fields'] = array('count', 'count_amount');          
        $summary = $this->Payment->find('all', $options);
        unset($this->Payment->virtualFields);
        unset($options['fields']);
        $options['limit'] = (!empty($this->params->query['limit']))? (int) $this->params->query['limit'] : 20; 
        $options['order'] = array('Payment.created DESC');            
        $payments = $this->Payment->find('all', $options);


        $this->set(array(
            'payments' => $payments,
            'summary' => $summary,              
            '_serialize' => array('payments', 'summary')
        ));
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
        if (!$user) {
            throw new ForbiddenException();
        }
        if (!$this->Payment->exists($id)) {
            throw new NotFoundException(__('Invalid payment'));
        }
        $options = array('conditions' => array('Payment.' . $this->Payment->primaryKey => $id));
        $this->Payment->recursive = 0;

        $payment = $this->Payment->find('first', $options);

        $this->set(array(
            'payment' => $payment,
            '_serialize' => array('payment')
        ));
    }

    public function viewByUser(){
        $user = $this->authenticate();
        if(!$user) {
            throw new ForbiddenException();
        }
        $this->Payment->recursive = 0;
        $options = array();
        $options['conditions']['Payment.user_id ']=$user['User']['id'];
        if (!empty($this->params->query['start_date'])) {
            $options['conditions']['Payment.created > '] = $this->params->query['start_date'];
        }
        if (!empty($this->params->query['end_date'])) {
            $options['conditions']['Payment.created < '] = $this->params->query['end_date'];
        }
        if (!empty($this->params->query['server_id'])) {
            $options['conditions']['Payment.server_id = '] = $this->params->query['server_id'];
        }
        if (!empty($this->params->query['send_game_status'])) {
            $options['conditions']['Payment.send_game_status = '] = $this->params->query['send_game_status'];
        }
        $options['fields'] = array( 'Payment.id','Payment.user_id','Game.name','Payment.server_id','Payment.amount','Payment.payment_info','Payment.created','Payment.send_game_status');
        $options['order'] = array('Payment.created DESC');
        $payments = $this->Payment->find('all', $options);
        unset($options['fields']);
        $this->Payment->virtualFields = array(
            'count' => 'COUNT(*)',
            'count_amount' => 'SUM(Payment.amount)'
        );
        $options['fields'] = array('count','count_amount');
        $summary = $this->Payment->find('all', $options);
        $this->set(array(
            'payments' => $payments,
            'summary' => $summary,
            '_serialize' => array('payments', 'summary')
        ));

    }

    public function edit($id) {        

        $this->adminPermission();

        if ($this->request->data) {
            $payment = $this->Payment->findById($id);
            if (!$payment) {
                throw new BadRequestException('Không có thông tin tương ứng');
            }

            $data = array();               
            $this->Payment->id = $id;
            $payment = $this->Payment->save($this->request->data);
            if (!$payment) {
                throw new BadRequestException($this->errorException($this->Payment->validationErrors));
            }                
            $this->view($id);
        }
    }


    private function _checkParams($params) {            
        if (empty($params['channelId']) || empty($params['gameId']) || empty($params['userId']) || empty($params['card_code']) || empty($params['card_serial']) || empty($params['card_vendor'])) {
            return;
        }

        $endParams = array(
            'demo' => (empty($params['demo']))? 0 : 1,
            'server_id' => (empty($params['serverId']))? null : $params['serverId'],
            'sub_id' => (empty($params['subId']))? null : $params['subId'],
            'user_id' => $params['userId'],
            'channel_id' => $params['channelId'],
            'game_id' => $params['gameId'],
            'card_vendor' => $params['card_vendor'],
            'card_serial' => $params['card_serial'],
            'card_code' => $params['card_code'],
            'payment_status' => 0,
            'amount' => 0,
            'payment_log' => null,
            'payment_code'=> null,
            'payment_message' => null,
            'payment_info' => json_encode($params),               
        );            
        return $endParams;
    }		

    private function _checkGoogleParams($params) {            
        if (empty($params['channelId']) || empty($params['gameId']) || empty($params['userId'])  || empty($params['itemId']) || empty($parms['token']) || empty($params['tId'])) {
            return;
        }

        $endParams = array(
            'demo' => (empty($params['demo']))? 0 : 1,
            'server_id' => (empty($params['serverId']))? null : $params['serverId'],
            'sub_id' => (empty($params['subId']))? null : $params['subId'],
            'user_id' => $params['userId'],
            'channel_id' => $params['channelId'],
            'game_id' => $params['gameId'],
            'card_vendor' => '',
            'card_serial' => '',
            'card_code' => '',
            'payment_status' => 1,
            'amount' => (int) $params['amount'],
            'payment_log' => $params['itemId'],
            'payment_code'=> null,
            'payment_message' => null,
            'payment_info' => json_encode($params),
            'token' => $params['token'],
            't_id' => $params['tId']

        );            
        return $endParams;
    }        

    private function _checkGiftcodeParams($params) {            
        if (empty($params['channelId']) || empty($params['gameId']) || empty($params['userId']) || empty($params['giftcode'])) {
            return;
        }

        $endParams = array(
            'demo' => (empty($params['demo']))? 0 : 1,
            'server_id' => (empty($params['serverId']))? null : $params['serverId'],
            'sub_id' => (empty($params['subId']))? null : $params['subId'],
            'user_id' => $params['userId'],
            'channel_id' => $params['channelId'],
            'game_id' => $params['gameId'],
            'giftcode' => $params['giftcode'],               
            'payment_status' => 0,
            'amount' => 0,
            'payment_log' => null,
            'payment_code'=> null,
            'payment_message' => null,
            'payment_info' => json_encode($params),               
        );            
        return $endParams;
    }
    private function convertAmount($amount) {
        $amount = (int) $amount;
        if ($amount == 70) {
            return 21000;
        } else if ($amount == 350) {
            return 106000;
        } else if ($amount == 1470) {
            return 425000;
        } else if ($amount == 3010) {
            return 850000;
        } else if ($amount == 4480) {
            return 1275000;
        } else if ($amount == 7420) {
            return 2126000;
        } else {
            return $amount;
        }                        
    }  
    //true : show 5stars payment.
    //false : hide 5stars payment.
    //in datababse only add channel ID and gameID need to hide.
    public function flag() {
        $channelId = $this->_getParam('channelId');
        $gameId = $this->_getParam('gameId');

        $configs = $this->Flag->find('all');

        $status = true;
        if ($this->_getParam('channelId') && $this->_getParam('gameId')) {
            foreach ($configs as $config) {
                if ($this->_getParam('channelId') == $config['Flag']['channel_id'] && $this->_getParam('gameId') == $config['Flag']['game_id']) {
                    $status = false;
                } 
            } 
        }
        $this->set(array(
            'status' =>  $status,
            '_serialize' => array('status')
        )); 

    }

    public function generate(){

        $this->Store->create(); 
        $this->set(array(
            'payment' =>  $this->Store->save(array('token' => md5(time()))),
            '_serialize' => array('payment')
        ));

    }

    public function party(){
        $params = $this->request->data;
        if (!empty($params['userId']) && !empty($params['serverId']) && !empty($params['subId']) && !empty($params['channelId']) && !empty($params['gameId']) && !empty($params['amount'])) {
            $params['amount'] = $this->convertAmount($params['amount']);
            $payment = $this->Payment->save(array(
                'user_id' => $params['userId'],
                'game_id' => $params['gameId'],
                'server_id' => $params['serverId'],
                'channel_id' => $params['channelId'],
                'sub_id' => $params['subId'],
                'amount' => (int) $params['amount'],
                'send_game_status' => '1',
                'payment_status' => '1'
            ));	
            if ($payment) {
                $this->set(array(
                    'payment' =>  $payment,
                    '_serialize' => array('payment')
                ));
            } else {
                throw new BadRequestException('Thanh toán không thành công');
            } 
        } else {
            throw new BadRequestException('Không lưu được dữ liệu thanh toán');
        }
    }

    public function add(){         
        if (!empty($this->request->data['giftcode']))  {
            $params = $this->_checkGiftcodeParams($this->request->data);
        } else if (!empty($this->request->data['itemId'])) {
            $params = $this->_checkGoogleParams($this->request->data);
        }  else {
            $params = $this->_checkParams($this->request->data);
        }			 
        if (!$params) {
            throw new BadRequestException('Không đủ tham số truyền vào hoặc tham số truyền vào không hợp lệ');
        }
        if (!empty($params['game_id']) && ($params['game_id'] == 1 ||  $params['game_id'] == 2)) {
            throw new BadRequestException('Hệ thống payment của game Maphap Online dừng hoạt động');
        }
        $this->User->unbindAll();
        $user = $this->User->findById($params['user_id']);
        if (empty($user)) {
            throw new ForbiddenException('5Star ID không tồn tại trong hệ thống');
        }   
        if (!empty($this->request->data['giftcode'])) {
            $response = $params;
            $giftcode = $this->Gift->findByCode($params['giftcode']);
            if ($giftcode && ($giftcode['Gift']['status'] == 'inactive')) {	
                //check if this user got gift code or not.
                $userGiftcode = $this->Gift->findByUserId($params['user_id']);	
                if (!$userGiftcode) {
                    $response['payment_status'] = 1;
                    $response['amount'] = 66800;
                    $this->Gift->id = $giftcode['Gift']['id'];
                    $this->Gift->saveField('status','active');
                    $this->Gift->saveField('channel_id',$params['channel_id']);
                    $this->Gift->saveField('user_id',$params['user_id']);
                } else {
                    throw new ForbiddenException('Bạn đã nhận được giftcode rồi');
                }
            } else {
                throw new ForbiddenException('Giftcode không tồn tại trong hệ thống hoặc đã được sử dụng');
            }
        } else if (!empty($this->request->data['itemId'])) {
            //google.
            $tokenData = $this->Store->findById($params['t_id']);
            if ($tokenData && (md5($tokenData['id'].'5stars'.$tokenData['token']) == $params['token'])){
                $response = $params; 
            } else {
                throw new BadRequestException('Token invalid');  
            }

        } else {
            $response = $this->Billing->processPayment($params);
        }        

        if ($payment = $this->Payment->save($response)) {                        
            if ($payment['Payment']['payment_status'] == 1) {
                //if demo dont send the payment to billing NPT.
                //if google play dont send payment to NPT, when cron = 1 will send.
                if ($response['demo'] == 0) {
                    if ( empty($this->request->data['itemId'])) {
                        $this->Billing->sendPaymentToGame($payment);  
                    }                      
                }                    
                $this->set(array(
                    'payment' =>  $payment,
                    '_serialize' => array('payment')
                ));
            } else {
                throw new BadRequestException('Thanh toán không thành công');
            }
        } else {
            $this->log($response);
            throw new BadRequestException('Không lưu được dữ liệu thanh toán');
        }     
    } 
}
