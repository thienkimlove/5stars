<?php
    App::uses('AppController', 'Controller');
    
    class AccountsController extends AppController {
        
        public function index() {
            $this->adminPermission(); 
                $options = $this->Account->buildOptions($this->params->query);                
                $accounts = $this->Account->find('all', $options);
                $count = $this->Account->find('count', $options);
                $this->set(array(
                    'accounts' => $accounts,
                    'count' => $count,
                    '_serialize' => array('accounts', 'count')
                ));
        }
    }
