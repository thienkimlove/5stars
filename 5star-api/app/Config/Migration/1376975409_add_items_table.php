<?php
class AddItemsTable extends CakeMigration {

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
				'items' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'title'),
					'image_url' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'description'),
					'product_url' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'image_url'),
					'amount' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'product_url'),
					'price' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'amount'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'price'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'modified'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'item_id' => array('column' => 'title', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'items'
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
