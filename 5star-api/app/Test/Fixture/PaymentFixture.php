<?php
/**
 * PaymentFixture
 *
 */
class PaymentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'amount' => array('type' => 'integer', 'null' => false, 'default' => null),
		'game_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'channel_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'payment_info' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'send_game_status' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4),
		'payment_status' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
			'user_id' => 1,
			'amount' => 1,
			'game_id' => 1,
			'channel_id' => 1,
			'payment_info' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'send_game_status' => 1,
			'payment_status' => 1,
			'created' => '2013-06-19 11:58:11',
			'modified' => '2013-06-19 11:58:11'
		),
	);

}
