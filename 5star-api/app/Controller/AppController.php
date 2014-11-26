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
    public $uses = array('User', 'Channel', 'Game', 'History', 'Report');   
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
            if (empty($user)) {
                throw new ForbiddenException('Bạn không có quyền thực hiện thao tác này');  
            }  else {
                return $user;
            }
        } else {
            throw new BadRequestException('Thiếu dữ liệu quyền truy cập');            
        }
    }   
    public function adminPermission() {
        $user = $this->authenticate();
        if (!$user || ($user['User']['role'] != 'admin')) {
            throw new ForbiddenException();
        }

    }
    public function channelPermission($id = null) {
        $user = $this->authenticate();
        if (!$user || !$user['User']['role']) {
            throw new ForbiddenException();
        }
        if (($user['User']['role'] != 'channel') && ($user['User']['role'] != 'admin')) {
            throw new ForbiddenException();
        } 
        if (($user['User']['role'] == 'channel') && $id) {
            $this->Channel->recursive = -1;
            $channel = $this->Channel->findById($id);
            if (!$channel || ($channel['Channel']['user_id'] != $user['User']['id'])) {
                throw new ForbiddenException();
            }
        }
    }

    public function crmPermission() {
        $user = $this->authenticate();
        if (!$user || !$user['User']['role']) {
            throw new ForbiddenException();
        }
        return $user;
    }

    public function gamePermission($id =  null) {
        $user = $this->authenticate();
        if (!$user || !$user['User']['role']) {
            throw new ForbiddenException();
        }
        if (($user['User']['role'] != 'game') && ($user['User']['role'] != 'admin')) {
            throw new ForbiddenException();
        } 
        if (($user['User']['role'] == 'game') && $id) {
            $this->Game->recursive = -1;
            $game = $this->Game->findById($id);
            if (!$game || ($game['Game']['user_id'] != $user['User']['id'])) {
                throw new ForbiddenException();
            }
        }
    }

    public function addHistory($postData) {
        $history = (isset($postData['History']))? $postData['History'] : $postData;

        $this->History->recursive = -1;

        $exist = $this->History->find('count', array('conditions' => array(
            'History.user_id' => $postData['user_id'],
            'History.game_id' => $postData['game_id'],
            'History.channel_id' => $postData['channel_id']  
        )));
        if ($exist == 0) {            
            $this->History->create();    
            $this->History->save($history);   
        }
        //add to Report table.
        $history['time'] = date('Y-m-d H:i:s');

        $this->Report->recursive = -1;
        $this->Report->create();    
        $this->Report->save($history);
       
    }

    public function errorException($validationErrors) {
        if(is_string($validationErrors)) {
            $message = $validationErrors;
        } else if(is_array($validationErrors)) {
            while (is_array($validationErrors)) {
                $validationErrors = array_pop($validationErrors);
            }
            return $validationErrors;
        }
    }
}
