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
	* @license       http://www.opensource.org/licenses/mit-license.php MIT License
	*/
	App::uses('Controller', 'Controller');

	/**
	* Application Controller
	*
	* Add your application-wide methods in the class below, your controllers
	* will inherit them.
	*
	* @package		app.Controller
	* @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
	*/
	class AppController extends Controller {
		// we have to set this to false because we are not using any datasource
		public $uses = null;        
		public $components = array(
			'Session','Cookie', 'GameApi', 'RequestHandler'
		);
		public $helpers = array(            
			'Html' => array('className' => 'StarHtml'),
			'Form', 'Session', 'Time'
		);
		public function beforeFilter() {
			$this->_checkAccount();
		}
		private function _checkAccount() {            
			if ($this->params['action'] != 'login') {               
				if (!$this->Session->check('Auth.User.Id')) {                
					$this->Session->delete('Auth');
					$this->Session->setFlash('Xin vui lòng đăng nhập để sử dụng hệ thống!', 'info');                
					$this->redirect(array('controller' => 'admin' ,'action' => 'login'));
				}
				$user = $this->GameApi->resource('User')->get($this->Session->read('Auth.User.Id'));
				if (!empty($user['user']['User']) && $user['user']['User']['role'] && $user['user']['User']['status'] == 'active') {
					$this->CurrentUser = $user['user']['User'];
				} else {
					$this->Session->delete('Auth');
					$this->Session->setFlash('Bạn không có quyền truy cập hệ thống này!', 'error');                
					$this->redirect(array('controller' => 'admin' ,'action' => 'login'));
				}
			}
		}
	  
	}
