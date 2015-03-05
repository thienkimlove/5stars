<?php
App::uses('AppModel', 'Model');
/**
 * Token Model
 *
 * @property User $User
 * @property Game $Game
 * @property Channel $Channel
 */
class Token extends AppModel {

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
		'token' => array(
			'notempty' => array(
				'rule' => array('notempty'),			
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
