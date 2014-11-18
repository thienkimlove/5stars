<?php
class AddGameNameAndChannelNameToPayment extends CakeMigration {

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
			'create_field' => array(
				'channels' => array(
					'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
				),
				'games' => array(
					'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
				),
				'payments' => array(
					'channel_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'payment_message'),
					'game_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'channel_name'),
				),
			),
			'drop_field' => array(
				'payments' => array('game_track_status',),
			),
			'alter_field' => array(
				'payments' => array(
					'sub_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'channels' => array('name',),
				'games' => array('name',),
				'payments' => array('channel_name', 'game_name',),
			),
			'create_field' => array(
				'payments' => array(
					'game_track_status' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'alter_field' => array(
				'payments' => array(
					'sub_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
				),
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
		if ($direction == 'up') {
			App::uses('ConnectionManager', 'Model');
			$conn = ConnectionManager::getDataSource('default');
			$conn->query("
			Update games t1 set name = (select fullname from users t2 where t2.id = t1.user_id );
			Update channels t1 set name = (select fullname from users t2 where t2.id = t1.user_id );
			Update payments t1 set game_name = (select name from games t2 where t2.id = t1.game_id);
			Update payments t1 set channel_name = (select name from channels t2 where t2.id = t1.channel_id);
			");
		}
		return true;
	}
}
