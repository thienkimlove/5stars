<?php
class AddReportTableAndDropFieldsHistoriesTable extends CakeMigration {

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
			'drop_field' => array(
				'histories' => array('sub_id', 'server_id', 'action', 'time', 'infos',),
			),
			'create_table' => array(
				'reports' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'id'),
					'channel_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'user_id'),
					'game_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'channel_id'),
					'sub_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'game_id'),
					'server_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'sub_id'),
					'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'server_id'),
					'time' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'action'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'histories' => array(
					'sub_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'server_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'time' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'infos' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'drop_table' => array(
				'reports'
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
