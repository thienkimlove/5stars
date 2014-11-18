<?php
	App::uses('AppController', 'Controller');
	/**
	* Users Controller
	*
	* @property User $User
	*/
	class AdminController extends AppController {

		public function index() {            
			$this->layout = 'manage';
		
			$this->set('currentUser', $this->CurrentUser);
		}
		
		public function login(){
			$this->layout = 'login';
			$this->Session->delete('Auth');
			$this->CurrentUser = null;
			if ($this->request->is('post')) {
				$this->Session->write('Auth.User.Login', $this->request->data['login']);
				$this->Session->write('Auth.User.Password', $this->request->data['password']);
				try {
					$result = $this->GameApi->resource('User')->request('/auth');
				} catch (Exception $e) {
					$this->Session->delete('Auth');
					$this->Session->setFlash('Thông tin đăng nhập không chính xác', 'error');
				}
				if (empty($result['user'])) {
					$this->Session->delete('Auth');
					 $this->Session->setFlash('Thông tin đăng nhập không chính xác', 'error');
				} else {
					if ($result['user']['User']['status'] != 'active') {
						$this->Session->delete('Auth');
						$this->Session->setFlash('Tài khoản của bạn đã bị khóa', 'error');
					} else {
						if (!$result['user']['User']['role']) {
							$this->Session->delete('Auth');
							$this->Session->setFlash('Tài khoản của bạn không có quyền truy cập vào hệ thống này', 'info');
						} else {
							$this->Session->write('Auth.User.Id', $result['user']['User']['id']);
							$this->redirect(array('action' => 'index'));
						}
					}
				}
				
			}
		}
	
	}
