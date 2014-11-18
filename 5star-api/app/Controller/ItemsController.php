<?php
    App::uses('AppController', 'Controller');
    /**
    * Games Controller
    *
    * @property Game $Game
    */
    class ItemsController extends AppController {

        /**
        * index method
        *
        * @return void
        */
        public function index() {
            $this->Item->recursive = 0;
            //$this->Order->unbindAll(false);
            $options = $this->Item->buildOptions($this->params->query);
            $items = $this->Item->find('all', $options);
            $this->set(array(
                'items' => $items,
                '_serialize' => array('items')
            ));
        }
        public function view($id = null) {
            if (!$this->Item->exists($id)) {
                throw new NotFoundException(__('Invalid item'));
            }
            $options = array('conditions' => array('Item.' . $this->Item->primaryKey => $id));
            $this->Item->recursive =  -1;
            $item = $this->Item->find('first', $options);

            $this->set(array(
                'item' => $item,
                '_serialize' => array('item')
            ));
        }  
    }
