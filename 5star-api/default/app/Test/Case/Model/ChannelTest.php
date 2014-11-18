<?php
App::uses('Channel', 'Model');

/**
 * Channel Test Case
 *
 */
class ChannelTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.channel',
		'app.payment',
		'app.user',
		'app.game',
		'app.token'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Channel = ClassRegistry::init('Channel');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Channel);

		parent::tearDown();
	}

}
