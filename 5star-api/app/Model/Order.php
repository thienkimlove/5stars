<?php
App::uses('AppModel', 'Model');
/**
 * Payment Model
 *
 * @property User $User
 * @property Game $Game
 * @property Channel $Channel
 */
class Order extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),                
            ),
        ),        
        'item_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),                
            ),
        ),                      
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',            
        ),        
    );
    public $hasOne = array(
        'Item' => array(
            'className' => 'Item',
            'foreignKey' => 'item_id',
            'dependent' => false,            
        ),        
    );
}
