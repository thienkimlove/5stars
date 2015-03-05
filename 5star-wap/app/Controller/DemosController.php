<?php
    App::uses('AppController', 'Controller');
    /**
    * Users Controller
    *
    * @property User $User
    */
    class DemosController extends AppController {

        public function index() {
            //start test.
            $params = $this->request->query;
            if (empty($params['channelId'])) {
                $params['channelId'] = 1;
            }
            if (empty($params['gameId'])) {
                $params['gameId'] = 1;
            }

            $this->Session->delete('Test'); 
            $this->Session->write('Test.channelId', $params['channelId']);
            $this->Session->write('Test.gameId', $params['gameId']);
            $this->redirect(array('action' => 'login'));
        }

        public function login() {
            if ($this->Session->read('Test.authId')) {
                $this->redirect(array('action' => 'profile'));
            } 
            
        }
        public function checkLogin()
        {
            $this->autoRender = false;
            if ($this->Session->check('Test.authId')) {
                die(json_encode(array('status' => true)));
            } else {
                if ($this->Session->check('Test.token')) {
                    $result =  $this->GameApi->resource('User')->request('/checkLogin',array(
                        'method' => 'POST',
                        'data' => array(
                            'channelId' => $this->Session->read('Test.channelId'),
                            'gameId' => $this->Session->read('Test.gameId'),
                            'token' => $this->Session->read('Test.token') 
                    )));  

                    if (!empty($result['login_status']) && $result['login_status'] == 1) {
                        $this->Session->write('Test.authId',$result['userId']);                         
                        die(json_encode(array('status' => true)));
                    } else {
                        die(json_encode(array('status' => 'error')));
                    }
                } else {
                    $this->Session->write('Test.token', String::uuid());
                    die(json_encode(array('status' => 'open', 'token' => $this->Session->read('Test.token'))));
                }
            }
        }

        public function profile() {
            if (!$this->Session->check('Test.authId')) {
                $this->redirect(array('action' => 'login'));
            } 
        }			
    }
