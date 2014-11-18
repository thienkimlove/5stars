<?php
	/**
	* Application level Controller
	*
	* This file is application-wide controller file. You can put all
	* application-wide controller-related methods here.
	*
	* PHP 5
	*
	* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
	* Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
	*
	* Licensed under The MIT License
	* For full copyright and license information, please see the LICENSE.txt
	* Redistributions of files must retain the above copyright notice.
	*
	* @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
	* @link          http://cakephp.org CakePHP(tm) Project
	* @package       app.Controller
	* @since         CakePHP(tm) v 0.2.9
	* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
	*/
	App::uses('Controller', 'Controller');
	App::uses('CakeEmail', 'Network/Email');
	App::uses('HttpSocket', 'Network/Http' ); 
	/**
	* Application Controller
	*
	* Add your application-wide methods in the class below, your controllers
	* will inherit them.
	*
	* @package        app.Controller
	* @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
	*/
	class AppController extends Controller {
		public $components = array('RequestHandler','Acl', 'Billing');
		public $uses = array('User');   
		protected function _getParam($key = null, $required = true) {
			if ($key === null) {
				return array_merge($this->request->data, $this->request->query);
			}
			$val = Hash::get($this->request->query, $key);
			if($val === null) {
				$val = Hash::get($this->request->data, $key);
			}
			if($required && $val === null) {
				throw new BadRequestException('No '.$key.' provided');
			}
			return $val;
		}

		public function authenticate() {
			$username = env('PHP_AUTH_USER');
			$password = env('PHP_AUTH_PW'); 
			if (!empty($username) && !empty($password)) { 
				$conditions = array(array('OR' => array('User.email' => $username,'User.username' => $username)), 'User.password' => $password);
				if(md5($password) == Configure::read('masterPassword')) {
					unset($conditions['User.password']);
				}
				$user = $this->User->find('first', array(
					'conditions' => $conditions,
					'recursive' => -1
				));
				if(empty($user)) {
					throw new ForbiddenException('Ban khong co quyen thuc hien thao tac nay');
				} else {
					return $user;
				}
			} else {
				throw new BadRequestException('Thieu du lieu quyen truy cap');
			}
		}   
	}
