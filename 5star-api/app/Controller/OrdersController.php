<?php
    App::uses('AppController', 'Controller');
    /**
    * Games Controller
    *
    * @property Game $Game
    */
    class OrdersController extends AppController {

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
            $this->Order->recursive = 0;
            //$this->Order->unbindAll(false);
            $options = $this->Order->buildOptions($this->params->query);
            $orders = $this->Order->find('all', $options);
            $this->set(array(
                'orders' => $orders,
                '_serialize' => array('orders')
            ));
        }

        /**
        * view method
        *
        * @throws NotFoundException
        * @param string $id
        * @return void
        */
        public function view($id = null) {
            if (!$this->Order->exists($id)) {
                throw new NotFoundException(__('Invalid order'));
            }
            $options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
            $this->Order->unbindAllExcept('Item');
            $order = $this->Order->find('first', $options);

            $this->set(array(
                'order' => $order,
                '_serialize' => array('order')
            ));
        }  

        public function add() {      
            if ($this->request->data) {
                $order = $this->Order->save($this->request->data);
                if(!$order) {
                    throw new BadRequestException($this->Order->validationErrors);
                }
                $this->set(array(
                    'order' => $order,                   
                    '_serialize' => array('order')
                ));
            }   else {
                throw new BadRequestException('Thieu du lieu');
            } 
        }
        public function edit($id) {            
            if ($this->request->data) {
                $order = $this->Order->findById($id);
                if (!$order) {
                    throw new BadRequestException('Khong co order ton tai');
                }
                $this->Order->id = $id;
                $order = $this->Order->save($this->request->data);
                if (!$order) {
                    throw new BadRequestException($this->Order->validationErrors);
                }                
                 $this->set(array(
                    'order' => $order,                   
                    '_serialize' => array('order')
                ));
            }
        }
    }
