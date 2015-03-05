<?php
    App::uses('AppController', 'Controller');
    /**
    * Payments Controller
    *
    * @property Payment $Payment
    */
    class FriendsController extends AppController {
       
       var $uses = array('Friend', 'Facebook');    
        
        public function add() {
            
            $user = $this->authenticate();            
            if(!$user) {
                throw new ForbiddenException('Chi cho phep khi dang nhap');
            }
            //prevent hack.
            
            $this->log($this->request->data);
            
            $this->set(array(
                'success' => true,                   
                '_serialize' => array('success')
            ));

        }
    }
