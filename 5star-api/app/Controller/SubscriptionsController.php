<?php
    App::uses('AppController', 'Controller');
    /**
    * Games Controller
    *
    * @property Game $Game
    */
    class SubscriptionsController extends AppController {

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
            $this->Subscription->recursive = -1;
            
            $options = $this->Subscription->buildOptions($this->params->query);
            $subscriptions = $this->Subscription->find('all', $options);
            $this->set(array(
                'subscriptions' => $subscriptions,
                '_serialize' => array('subscriptions')
            ));
        }

        
    }
