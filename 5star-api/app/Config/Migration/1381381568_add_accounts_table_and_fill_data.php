<?php
class AddAccountsTableAndFillData extends CakeMigration {

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
			'create_table' => array(
				'accounts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'username' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'password' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'username'),
					'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'password'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'accounts'
			),
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
        App::uses('ConnectionManager', 'Model');
        $conn = ConnectionManager::getDataSource('default');   
        if ($direction == 'up') {
                for ($i = 0; $i< 5000 ; $i ++) {
                    $username = 'dk5star'.$i;
                    $email = $username.'@5stars.vn';
                    $password = strtolower(substr(base_convert(sha1(uniqid(mt_rand().$i)), 16, 36), 0, 8)); 
                    $time = date('Y-m-d H:i:s');                   
                    $conn->query("INSERT INTO `accounts` (`username`, `password`) VALUES
                        ('".$username."', '".$password."')");
                    $conn->query("INSERT INTO `users` (`fullname`, `username`, `email`, `password`, `status`, `created`, `modified`) VALUES
                        ('".$username."', '".$username."', '".$email."', '".md5($password)."', 'active', '".$time."', '".$time."')"); 
                }      
            }
        if ($direction == 'down') {
            $conn->query("DELETE from users where username LIKE 'dk5star%'");
        }    

		return true;
	}
}
