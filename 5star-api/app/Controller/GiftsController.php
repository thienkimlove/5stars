<?php
    App::uses('AppController', 'Controller');
    /**
    * Games Controller
    *
    * @property Game $Game
    */
    class GiftsController extends AppController {

        /**
        * index method
        *
        * @return void
        */
        public function index() {
            $user = $this->authenticate();
            if (!$user || $user['User']['role'] != 'admin') {
                throw new ForbiddenException();
            }
            $this->Gift->recursive = 0;            
            $options = $this->Gift->buildOptions($this->params->query);
            $gifts = $this->Gift->find('all', $options);
            $this->set(array(
                'gifts' => $gifts,
                '_serialize' => array('gifts')
            ));
        }
        public function edit($id) {
            $user = $this->authenticate();
            if (!$user || $user['User']['role'] != 'admin') {
                throw new ForbiddenException();
            }
            if ($this->request->data) {
                $gift = $this->Gift->findById($id);
                if (!$gift) {
                    throw new BadRequestException('Khong co gift ton tai');
                }
                $this->Gift->id = $id;
                $saveGift = $this->request->data;
                //unset the code field.
                if (isset($saveGift['Gift']['code'])) {
                    unset($saveGift['Gift']['code']);
                }
                if (isset($saveGift['code'])) {
                    unset($saveGift['code']);
                }
                $gift = $this->Gift->save($saveGift);
                if (!$gift) {
                    throw new BadRequestException($this->Gift->validationErrors);
                }                
                $this->set(array(
                    'gift' => $gift,                   
                    '_serialize' => array('gift')
                ));
            }
        }
    }
