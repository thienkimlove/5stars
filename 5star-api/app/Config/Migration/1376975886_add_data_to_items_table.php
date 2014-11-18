<?php
class AddDataToItemsTable extends CakeMigration {

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
            App::uses('ConnectionManager', 'Model');
            $conn = ConnectionManager::getDataSource('default');
            $conn->query("INSERT INTO `items` (`id`, `title`, `description`, `image_url`, `product_url`, `amount`, `price`, `created`, `modified`, `status`) VALUES
(1, '100 kim cương', 'Mua 100 kim cương trong ma pháp online', 'http://maphap.5stars.vn/img/diamond1.png', '', 10000, 10, '2013-08-19 15:24:00', '2013-08-19 15:24:00', '1'),
(2, '200 kim cương', 'Mua 200 kim cương trong ma pháp online', 'http://maphap.5stars.vn/img/diamond1.png', '', 20000, 20, '2013-08-19 15:25:00', '2013-08-19 15:24:00', '1'),
(3, '500 kim cương', 'Mua 500 kim cương trong ma pháp online', 'http://maphap.5stars.vn/img/diamond1.png', '', 50000, 50, '2013-08-19 15:26:00', '2013-08-19 15:26:00', '1'),
(4, '1000 kim cương', 'Mua 1000 kim cương trong ma pháp online', 'http://maphap.5stars.vn/img/diamond1.png', '', 100000, 100, '2013-08-19 15:27:00', '2013-08-19 15:27:00', '1'),
(5, '5000 kim cương', 'Mua 5000 kim cương trong ma pháp online', 'http://maphap.5stars.vn/img/diamond1.png', '', 500000, 500, '2013-08-19 15:28:00', '2013-08-19 15:28:00', '1');");
        }
        
		return true;
	}
}
