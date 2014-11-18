<?php
    class FillDataToGiftTable extends CakeMigration {

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
                for ($i = 0; $i< 20000 ; $i ++) {
                    $code = strtolower(substr(base_convert(sha1(uniqid(mt_rand().$i)), 16, 36), 0, 8));
                    $time = date('Y-m-d H:i:s');
                    $conn->query("INSERT INTO `gifts` (`code`, `channel_id`, `user_id`, `status`, `created`, `modified`) VALUES
                        ('".$code."', NULL, NULL, 'inactive', '".$time."', '".$time."')");

                }      
            }

            return true;
        }
    }
