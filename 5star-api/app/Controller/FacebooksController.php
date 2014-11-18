<?php
    App::uses('AppController', 'Controller');
    /**
    * Payments Controller
    *
    * @property Payment $Payment
    */
    class FacebooksController extends AppController {

        var $uses = array('Friend', 'Facebook');    

        public function add() {

            $user = $this->authenticate();            
            if(!$user) {
                throw new ForbiddenException('Chi cho phep khi dang nhap');
            }
            //prevent hack.
            if (empty($this->request->data)) {
                throw new BadRequestException('Thiếu dữ liệu'); 
            }

            $saveArray = array();
            foreach ($this->request->data as $id) {
                array_push($saveArray, array('user_id' => $user['User']['id'], 'facebook_id' => $id));
            }
            $this->Facebook->saveMany($saveArray);

            $friendList = $this->Facebook->find('list', array(
                    'fields' => array('id','user_id'),
                    'recursive' => -1,
                    'conditions' => array('facebook_id' => $user['User']['facebook_id'])
                ));

            $friends = array();
            if ($friendList) {
                $friendList = array_unique($friendList);
                foreach ($friendList as $friend) {
                    array_push($friends, array('user_id' => $user['User']['id'], 'friend_id' => $friend));
                    array_push($friends, array('user_id' =>$friend, 'friend_id' => $user['User']['id']));
                }
                $this->Friend->saveMany($friends);
            }

            $this->set(array(
                'success' => true,                   
                '_serialize' => array('success')
            ));

        }
    }
