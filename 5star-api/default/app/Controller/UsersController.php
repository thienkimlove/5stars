<?php
	App::uses('AppController', 'Controller');   
	class UsersController extends AppController {
		var $uses = array('User','Token');
		//using for game to check the authenticate, will be remove next time.(support both GET AND POST)
		public function checkLogin() {
			if ($this->_getParam('gameId') && $this->_getParam('channelId') && $this->_getParam('token')) {
				$this->Token->unbindAll();
				$token = $this->Token->find('first',array('conditions' =>  array(
					'Token.game_id' => $this->_getParam('gameId'),
					'Token.channel_id' => $this->_getParam('channelId'),
					'Token.token' => $this->_getParam('token')
				)));

				if (!empty($token)) {					
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
				if(!$check || $check['User']['id'] != $id) {
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
				$this->User->unbindAll();
				if($user = $this->User->save($user)) {					
					$this->set(array(
						'user' => $user,
						'_serialize' => array('user')
					));
				} else {
					throw new BadRequestException($this->User->validationErrors);
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
			->from(array('info@5stars.vn' => '5Stars'))
			->to($user['User']['email'])
			//->bcc(array('hungnt@5stars.vn','manhquan@5stars.vn'))
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
				$propsToUnset = array('id', 'status');
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
					throw new BadRequestException($this->User->validationErrors);
				}                
				//send welcome email.
				$email = new CakeEmail('stars');                       
				$email->template('welcome')
				->emailFormat('both')
				->from(array('info@5stars.vn' => '5Stars'))
				->to($user['User']['email'])
				//->bcc(array('hungnt@5stars.vn','manhquan@5stars.vn'))
				->subject('Chúc mừng bạn gia nhập vào hệ thống StarID')
				->viewVars(array('username' => $user['User']['username'], 'password' => $this->request->data['password']))
				->send();
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






		public function index(){
			$user = $this->authenticate();
			if(!$user) {
				throw new ForbiddenException('Chi cho phep khi dang nhap');
			}
			$conditions = array();
			if($this->request->query('day1User') && $this->request->query('day2User')){
				$conditions = array('created BETWEEN ? AND ?' => array($this->request->query('day1User'),$this->request->query('day2User')));
			}
			if($this->request->query('username')){
				$conditions['username LIKE'] = '%'.$this->request->query('username').'%';
			}
			$this->paginate= array('limit'=>20,'order'=>'id','recursive'=>-1,'conditions'=>$conditions);
			$count = $this->User->find('count',array('order'=>'id','recursive'=>-1,'conditions'=>$conditions));
			$this->set(array('users'=>$this->paginate(),'count'=>$count,'_serialize'=>array('users','count')));
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
	}
