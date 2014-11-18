<?php
App::uses('AppModel', 'Model');
/**
 * Payment Model
 *
 * @property User $User
 * @property Game $Game
 * @property Channel $Channel
 */
class Payment extends AppModel {

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
		'amount' => array(
			'numeric' => array(
				'rule' => array('numeric'),				
			),
		),
		'game_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),				
			),
		),
		'channel_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),				
			),
		),
		'payment_info' => array(
			'notempty' => array(
				'rule' => array('notempty'),				
			),
		),
		'send_game_status' => array(
			'numeric' => array(
				'rule' => array('numeric'),				
			),
		),
		'payment_status' => array(
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
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'game_id',			
		),
		'Channel' => array(
			'className' => 'Channel',
			'foreignKey' => 'channel_id',			
		)
	);
}
