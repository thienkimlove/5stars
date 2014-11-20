<?php
App::uses('AppController', 'Controller');   
class UsersController extends AppController {
    var $uses = array('User', 'Token', 'Friend', 'History');



    //using for game to check the authenticate, will be remove next time.(support both GET AND POST)
    public function checkLogin() {
        if ($this->_getParam('gameId') && $this->_getParam('channelId') && $this->_getParam('token')) {
            $this->Token->unbindAll();
            $token = $this->Token->find('first',array(
                'order' => array('Token.created' => 'desc'),
                'conditions' =>  array(
                    'Token.game_id' => $this->_getParam('gameId'),
                    'Token.channel_id' => $this->_getParam('channelId'),
                    'Token.token' => $this->_getParam('token')
            )));

            if (!empty($token)) {		
                $this->Token->delete($token['Token']['id']);		
                $this->set(array(
                    'login_status' => 1,
                    'userId' => $token['Token']['user_id'],
                    '_serialize' => array('login_status','userId')
                ));
            } else {
                $this->set(array(
                    'login_status' => 0,                        
                    '_serialize' => array('login_status')
                ));
            }
        } else {
            throw new BadRequestException('Not have enough parametters');
        }
    }
    /*public function send() {
    $this->autoRender = false;
    ini_set('max_execution_time', 0);
    $test = array(
    'manhquan@5stars.vn',
    'tieumaster@yahoo.com',
    'do.manh.quan@spectos.com',
    'donglp@5stars.vn',
    'phongnt@5stars.vn'
    );
    $this->User->unbindAll();
    $users = $this->User->find('all', array('fields' => array('email')));
    $count = 1;
    foreach ($users as $user) {
    if (!empty($user['User']['email'])) {
    $count++;
    try {
    $email = new CakeEmail('stars');                       
    $email->template('marketing')
    ->emailFormat('html')
    ->from(array('info@5stars.vn' => '5Stars'))
    ->to($user['User']['email'])
    //->bcc(array('manhquan@5stars.vn'))
    ->subject('[Bá Khí]Tiểu Long Nữ cần bạn!')
    ->send();
    echo $count.'sent to '.$user['User']['email'].' waiting  10 sec...<br />';
    sleep(10); 
    } catch(Exception $e) {
    echo $e->getMessage();
    echo $count.'cannot send to '.$user['User']['email'].'<br />';
    }

    }
    }



    }*/

    public function check(){     
        $data = array();      
        if ($this->_getParam('gameId') && $this->_getParam('channelId') && $this->_getParam('secret') && $this->_getParam('uid')) {
            $this->User->unbindAll();
            $user = $this->User->findById($this->_getParam('uid'));
            if ($user && $this->_getParam('secret') == md5('bakhi5'.$user['User']['id'])) {
                $data = $user;
                $this->History->create();
                $this->History->save(array('user_id' => $user['User']['id'], 'channel_id' => $this->_getParam('channelId'), 'game_id' => $this->_getParam('gameId'),'action' => 'login')); 
                if ($this->_getParam('channelId') == 114) {
                    $data['User']['sms'] = 'SUB '.$user['User']['id'].'|8762'; 
                } else {
                    $data['User']['sms'] = '';
                }
            }
        }
        $this->set(array(
            'user' => $data,
            '_serialize' => array('user')
        ));
    }

    public function fbservices(){     
        $data = array();      
        if ($this->_getParam('facebookId') && $this->_getParam('email') && $this->_getParam('channelId') && $this->_getParam('gameId') && $this->_getParam('fullname') ) {
            $this->User->unbindAll();
            $user = $this->User->findByEmail($this->_getParam('email'));
            if ($user) {                        
                $existFacebookEmail = $this->User->findByFacebookId($this->_getParam('facebookId'));                    
                if (!empty($existFacebookEmail) && $existFacebookEmail['User']['email'] != $this->_getParam('email')) {                    
                    $existFacebookEmail['User']['facebook_id'] = null;
                    $this->User->save($existFacebookEmail);                    
                }

                $user['User']['facebook_id'] = $this->_getParam('facebookId');
                $data = $this->User->save($user);                    
                $this->History->create();
                $this->History->save(array('user_id' => $user['User']['id'], 'channel_id' => $this->_getParam('channelId'), 'game_id' => $this->_getParam('gameId'),'action' => 'login'));                     
            } else {
                $user = $this->User->findByFacebookId($this->_getParam('facebookId'));                   
                if ($user) {
                    $user['User']['email'] = $this->_getParam('email');
                    $data = $this->User->save($user);   
                } else {
                    $this->User->create();                  
                    $password = time();                  
                    $data = $this->User->save(array(
                        'username' => 'facebook'.time(),
                        'fullname' => $this->_getParam('fullname'),
                        'facebook_id' => $this->_getParam('facebookId'),
                        'email' => $this->_getParam('email'),
                        'status' => 'active',
                        'password' => md5($password)                     
                    ));
                }


                if ($data) {                    
                    //send email.

                    $this->History->create();
                    $this->History->save(array('user_id' => $data['User']['id'], 'channel_id' => $this->_getParam('channelId'), 'game_id' => $this->_getParam('gameId'),'action' => 'register'));
                }
            }
        }
        $this->set(array(
            'user' => $data,
            '_serialize' => array('user')
        ));
    }

    public function checkReturn(){     
        $data = array();      
        if ($this->_getParam('gameId') && 
        $this->_getParam('channelId') && 
        $this->_getParam('secret') && 
        $this->_getParam('uid') && 
        ($this->_getParam('secret') == md5('tdk-login-return'.$this->_getParam('uid')))
        ) {
            $data = array(
                'User' => array('id' => $this->_getParam('uid'))
            ); 
        }
        $this->set(array(
            'user' => $data,
            '_serialize' => array('user')
        ));
    }

    public function index() {
        //not allow normail user.
        $user = $this->crmPermission();
        $this->User->recursive = -1;          
        // $options = $this->Payment->buildOptions($this->params->query);
        $options = array();

        if (!empty($this->params->query['start_date'])) {
            $options['conditions']['User.created > '] = $this->params->query['start_date'];
        }
        if (!empty($this->params->query['end_date'])) {
            $options['conditions']['User.created < '] = $this->params->query['end_date'];
        }
        if (!empty($this->params->query['search'])) {
            $options['conditions']['User.username = '] = $this->params->query['search'];
        }


        $options['joins'] = array(
            array( 
                'table' => 'histories',
                'type' => 'INNER',
                'alias' => 'History',
                'conditions' => array('User.id = History.user_id'), 
                'limit' => 1                       
            ),
            array( 
                'table' => 'channels',
                'type' => 'INNER',
                'alias' => 'Channel',
                'conditions' => array('History.channel_id = Channel.id'),                        
            ),                    
            array(
                'table' => 'games',
                'type' => 'INNER',
                'alias' => 'Game',
                'conditions' => array('History.game_id = Game.id'),                        
            ),
        );

        if ($user['User']['role'] == 'channel') {                    
            $options['conditions']['Channel.user_id = '] =  $user['User']['id'];                
        }
        if (!empty($this->params->query['channel_id'])) {
            $options['conditions']['History.channel_id = '] =  $this->params->query['channel_id'];
        }

        if ($user['User']['role'] == 'game') {
            $options['conditions']['Game.user_id = '] =  $user['User']['id'];
        }    
        if (!empty($this->params->query['game_id'])) {
            $options['conditions']['History.game_id = '] =  $this->params->query['game_id'];
        }           


        $options['group'] = 'User.id';
        $summary =  $this->User->find('count', $options);

        $options['limit'] = (!empty($this->params->query['limit']))? (int) $this->params->query['limit'] : 20; 
        $options['order'] = array('User.created DESC');   

        $users = $this->User->find('all', $options);
        
        $this->log($user);

        if ($users && (in_array($user['User']['role'], array('game', 'channel')))) {
            foreach ($users as &$row) {              
                $row['User']['email'] = $this->_hideEmail($row['User']['email']);
            }
        }

        $this->set(array(
            'users' => $users,
            'summary' => $summary,              
            '_serialize' => array('users', 'summary')
        ));
    }  

    private function _hideEmail($email) {
        $count = 0;
        $response = '';
        $stop = false;
        for ($i = 0; $i < strlen($email);  $i ++) {
            $count ++;
            if ($email[$i] == '@' || $count >= 5) {
                $stop = true;                    
            } 
            if ($stop == false) {
                $response .= '*';
            } else {
                $response .= $email[$i];
            }
        }
        return $response;
    }


    public function view($id) {   
        $user = $this->authenticate();
        if(!$user || $user['User']['id'] != $id) {
            throw new ForbiddenException();
        }           
        $this->set(array(
            'user' => $user,
            '_serialize' => array('user')
        ));
    }    

    public function edit($id) {
        if (!empty($this->request->data)) { 
            $check = $this->authenticate();
            if(!$check || ($check['User']['id'] != $id && $check['User']['role'] != 'admin')) {
                throw new ForbiddenException();
            }  
            $postData = $this->request->data;
            $user = (isset($postData['User']))? $postData['User'] : $postData;  
            //prevent hack
            $user['id'] = $id;
            //unset to not allow update directly.
            unset($user['email']);
            unset($user['username']);
            unset($user['password']);
            //for change password.
            if (!empty($user['new_password'])) {
                $user['password'] = $user['new_password'];
            } 
            if (!empty($user['new_username'])) {                 
                $find = $this->User->findByUsername($user['new_username']);
                if ($find) {
                    throw new BadRequestException('User da ton tai trong he thong. Xin chon user khac!');
                } else {
                    $user['username'] = $user['new_username']; 
                }                                
            }
            if (!empty($user['new_email'])) {                 
                $find = $this->User->findByEmail($user['new_email']);
                if ($find) {
                    throw new BadRequestException('Email da ton tai trong he thong. Xin chon email khac!');
                } else {
                    $user['email'] = $user['new_email']; 
                }                                
            }
            $this->User->unbindAll();
            if($user = $this->User->save($user)) {					
                $this->set(array(
                    'user' => $user,
                    '_serialize' => array('user')
                ));
            } else {
                throw new BadRequestException($this->errorException($this->User->validationErrors));
            }
        } else {
            throw new BadRequestException('Thieu du lieu');
        }
    }    

    public function requestPassword() {
        $this->User->recursive = -1;
        $user = $this->User->findByEmail($this->_getParam('email'));
        if(empty($user['User']['id'])){
            throw new BadRequestException('Khong tim thay thanh vien voi email ' . $this->_getParam('email'));
        }
        $user['User']['password'] = substr(base64_encode(md5(microtime())),-9,-1);
        if(!$this->User->save($user)) {
            throw new BadRequestException('Khong reset duoc mat khau');   
        }
        $email = new CakeEmail('stars');                       
        $email->template('request_password')
        ->emailFormat('both')
        ->from(array('redmine@5stars.vn' => '5Stars'))
        ->to($user['User']['email'])
        //->bcc(array('manhquan@5stars.vn'))
        ->subject('Mật khẩu tài khoản của bạn tại 5Stars đã được reset')
        ->viewVars(array('new_pass' => $user['User']['password']))
        ->send();

        $this->set(array(
            'status' => true,
            '_serialize' => array('status')
        ));
    }

    //add user Main API, only alow POST OR PUT method. 
    public function add() {
        if (!empty($this->request->data)) {
            //unset to prvent hack.
            //$propsToUnset = array('id', 'status');
            $propsToUnset = array('id');
            foreach ($propsToUnset as $field) {
                unset($this->request->data[$field]);   
                unset($this->request->data['User'][$field]);   
            }  
            $checkExist = $this->User->find('first', array(
                'conditions' => array(array('OR' => array('User.email' => $this->request->data['email'], 'User.username' => $this->request->data['username']))),                
                'recursive' => -1
            ));  
            if(!empty($checkExist)) {
                throw new BadRequestException('Username hoac email da ton tai trong he thong');
            }
            $user = $this->User->save($this->request->data);
            if(!$user) {
                throw new BadRequestException($this->errorException($this->User->validationErrors));
            }                
            //send welcome email.
            /*$email = new CakeEmail('stars');                       
            $email->template('welcome')
            ->emailFormat('both')
            ->from(array('info@5stars.vn' => '5Stars'))
            ->to($user['User']['email'])
            //->bcc(array('hungnt@5stars.vn','manhquan@5stars.vn'))
            ->subject('Chúc mừng bạn gia nhập vào hệ thống StarID')
            ->viewVars(array('username' => $user['User']['username'], 'password' => $this->request->data['password']))
            ->send();*/
            $this->set(array(
                'user' => $user,                   
                '_serialize' => array('user')
            ));

        }  else {
            throw new BadRequestException('Thieu du lieu');
        }
    }

    public function auth($token = null) {
        try {
            $user = $this->authenticate();
        } catch (Exception $e) {
            $user = false;
        }
        $this->set(array(
            'user' => $user,
            '_serialize' => array('user')
        ));
    }

    //using for login with channel.
    public function viewByChannel() {
        $params = $this->request->data;
        if ($params && !empty($params['channelIdentify']) && !empty($params['channelUserId'])) {
            //kiem tra dinh danh cua channel va id tuong ung co ton tai trong bang user hay ko.
            $identify = $params['channelIdentify'].':'.$params['channelUserId'];
            $this->User->recursive = -1;
            $user = $this->User->findByIdentify($identify);
            if ($user) {
                $this->set(array(
                    'user' => $user,
                    '_serialize' => array('user')
                ));
            } else { 
                $token = md5(time());
                $data  = array(
                    'identify' => $identify,
                    'username' => $params['channelIdentify'].$token,
                    'email' => $token.'@5stars.vn',
                    'password' => $token,
                    'information' => 'Channel User',
                    'status' => 'active' 
                );
                $this->User->create();
                if ($user = $this->User->save($data)) {
                    $this->set(array(
                        'user' => $user,
                        '_serialize' => array('user')
                    ));
                } else {
                    throw new BadRequestException($this->errorException($this->User->validationErrors));
                }
            }
        } else {
            throw new BadRequestException('Thieu du lieu');
        }
    }
    /////change or revemo this.
    //check username exists 
    public function checkUsername(){
        if($this->_getParam('username')){
            $user = $this->User->find('first',array('conditions'=>array('User.username'=>$this->_getParam('username')),'recursive'=>-1,'fields'=>array('username')));
            if(!empty($user)){
                $this->set(array('user'=>$user,'_serialize'=>array('user')));
            }else{
                throw new BadRequestException('invalid user');
            }
        }
        else{
            throw new BadRequestException('Missing Parametters');
        }
    }
    //check email exists
    public function checkEmail(){
        if($this->_getParam('email')){
            $user = $this->User->find('first',array('conditions'=>array('User.email'=>$this->_getParam('email')),'recursive'=>-1,'fields'=>array('email')));
            if(!empty($user)){
                $this->set(array('user'=>$user,'_serialize'=>array('user')));
            }else{
                throw new BadRequestException('invalid email');
            }
        }
        else{
            throw new BadRequestException('Missing Parametters');
        }
    }
    public function viewByFacebookId() {
        $this->User->recursive = -1;
        $this->set(array(
            'user' => $this->User->findByFacebookId($this->_getParam('facebook_id')),
            '_serialize' => array('user')
        ));
    }

    public function viewByFacebookEmail() {
        $this->User->recursive = -1;
        $this->set(array(
            'user' => $this->User->findByEmail($this->_getParam('email')),  
            '_serialize' => array('user')
        ));
    }
    public function friend(){
        $this->set(array(
            'friends' => $this->Friend->find('list', array('fields' => array('friend_id'),'conditions' => array('user_id' => $this->_getParam('userId')))),
            '_serialize' => array('friends')
        ));
    }
}
