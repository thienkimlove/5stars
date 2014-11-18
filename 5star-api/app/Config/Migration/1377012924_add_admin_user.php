<?php
class AddAdminUser extends CakeMigration {

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
		$User = ClassRegistry::init('User');
		if ($direction == 'up') {
			$User->create();
			$User->save(array(
			  'username' => 'admin',
			  'fullname' => '5stars Admin',
			  'password' => 'manhquandongca',
			  'email' => 'info@5stars.vn',
			  'status' => 'active',
			  'role' => 'admin'
			));
		}
		if ($direction == 'down') {
			$User->query("Delete from users where role = 'admin'");
		}
		return true;
	}
}
