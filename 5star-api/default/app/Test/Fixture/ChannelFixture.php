<?php
/**
 * ChannelFixture
 *
 */
class ChannelFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'exchange_rate' => array('type' => 'float', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'inactive', 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'login_connection' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4),
		'reg_connection' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4),
		'payment_connection' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'username' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'exchange_rate' => 1,
			'created' => '2013-06-19 11:56:26',
			'modified' => '2013-06-19 11:56:26',
			'status' => 'Lorem ip',
			'login_connection' => 1,
			'reg_connection' => 1,
			'payment_connection' => 1
		),
	);

}
