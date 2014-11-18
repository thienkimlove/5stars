<?php
    App::uses('AppModel', 'Model');
    /**
    * User Model
    *
    * @property Payment $Payment
    * @property Token $Token
    */
    class Event extends AppModel {
        
        public $useDbConfig = 'alternate';
        
        /**
        * Display field
        *
        * @var string
        */
        public $displayField = 'fullname';

        /**
        * Validation rules
        *
        * @var array
        */
        public $validate = array(		
            'email' => array(
                'rule1' => array(
                    'rule'       => array('email', true),
                    'message'    => 'Please supply a valid email address.',
                    'required'   => "create",
                    'allowEmpty' => false
                ),
                'rule2' => array(
                    'rule'    => 'isUnique',
                    'message' => 'This email already exists.'
                ),
            ),
            'username' => array(
                'rule1' => array('rule'       => array('between', 4, 45),                
                    'message'    => 'Username should be at least 4 chars long.',
                    'required'   => "create",
                    'allowEmpty' => false
                ),
                'rule2' => array(
                    'rule'    => 'isUnique',
                    'message' => 'This username already exists.'
                ),
            ),
            'password' => array(
                'rule'       => array('between', 5, 45),
                'message'    => 'Password should be at least 5 chars long',
                'allowEmpty' => false
            ),
        );

        //The Associations below have been created with all possible keys, those that are not needed can be removed

      

        public function beforeFind($queryData) {
            if (! empty($queryData['conditions'][$this->alias.'.password'])) {                
                $queryData['conditions'][$this->alias.'.password'] = md5($queryData['conditions'][$this->alias.'.password']);
            } 
            if (! empty($queryData['conditions']['password'])) {                
                $queryData['conditions']['password'] = md5($queryData['conditions']['password']);
            } 
            return $queryData;
        }
       

    }
