<?php
class Add100ChannelForTest extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
        if ($direction == 'up') {
            $Channel = ClassRegistry::init('Channel');
            $User = ClassRegistry::init('User');
            
            $User->create();
                    $user = $User->save(array(
                        'username' => 'test'.substr(md5(String::uuid()),0 ,5),
                        'password' => 'test1234',
                        'email' => strtolower('test').substr(md5(String::uuid()),0 ,5).'@5stars.vn',
                        'status' => 'active',
                        'role' => 'channel',
                        'fullname' => 'Test Channel'                    
                    ));
            
            $Channel->save(array(
               'id' => 100,
               'user_id' =>  $user['User']['id'],
               'status' => 'active',
               'login_connection' => 1,
               'payment_connection' => 1,
               'exchange_rate' => 1
            ));
        }
		return true;
	}
}
