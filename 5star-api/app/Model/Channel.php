<?php
App::uses('AppModel', 'Model');
/**
 * Channel Model
 *
 * @property Payment $Payment
 * @property Token $Token
 */
class Channel extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),			
			),
		),
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),			
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),				
			),
		),	
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Payment' => array(
			'className' => 'Payment',
			'foreignKey' => 'channel_id',
			'dependent' => false,			
		),
		'Token' => array(
			'className' => 'Token',
			'foreignKey' => 'channel_id',
			'dependent' => false,			
		),
        
	);
  public $belongsTo = array(
      'User' => array(
         'className' => 'User',
         'foreignKey' => 'user_id',
         'dependent' => false
      ),
  );  

}
