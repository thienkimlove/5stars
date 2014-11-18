<?php
class AddGiftsTable extends CakeMigration {

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
				'gifts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'code' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 8, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'channel_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index', 'after' => 'code'),
					'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'channel_id'),
					'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'user_id'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'status'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'code' => array('column' => 'code', 'unique' => 1),
						'channel_id' => array('column' => array('channel_id', 'user_id'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'gifts'
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
		return true;
	}
}
