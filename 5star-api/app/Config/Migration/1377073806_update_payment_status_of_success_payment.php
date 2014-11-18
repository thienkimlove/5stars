<?php
    class UpdatePaymentStatusOfSuccessPayment extends CakeMigration {

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
                $conn->query("Update payments set payment_status = 1 where send_game_status = 1");
            }
            return true;
        }
    }
