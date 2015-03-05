<?php
App::uses('AppController', 'Controller');
class PaymentsReportsController extends AppController
{
    public $uses = array(
        'Payment'
    );

    public function index()
    {
        $user = $this->authenticate();
        if (!$user) {
            throw new ForbiddenException ('Chi cho phep khi dang nhap');
        }
        $oldUserPayment [0] = null;
        // conditions

        $conditions = array();
        if ($this->request->query('day1Payment') && $this->request->query('day2Payment')) {
            $day1 = date('Y-m-d', strtotime($this->request->query('day1Payment')));
            $day2 = date('Y-m-d', strtotime($this->request->query('day2Payment')));
            $conditions = array(
                'Payment.created BETWEEN ? AND ?' => array(
                    $day1,
                    $day2
                )
            );
            if ($this->request->query('mod')) {
                $day0 = date('Y-m-d', strtotime($this->request->query('day1Payment') . ' -1 day'));
                switch ($this->request->query('mod')) {
                    case 'old' :
                        $subQuery = "SELECT COUNT(DISTINCT user_id) AS total FROM payments WHERE
	                                created BETWEEN '$day1' AND '$day2' AND send_game_status =1 AND  user_id IN (
	                                SELECT DISTINCT user_id FROM payments WHERE created BETWEEN
	                                '08-08-2013 11:00:00' and '$day1') ";
                        if ($this->request->query('channel_id')) {
                            $subQuery .= " and channel_id =" . $this->request->query('channel_id');
                        }
                        if ($this->request->query('game_id')) {
                            $subQuery .= " and game_id =" . $this->request->query('game_id');
                        }
                        if ($this->request->query('gift')) {
                            $subQuery .= " and amount != 66800";
                            $subQuery .= " and channel_id != 100";
                        }
                        $oldUserPayment = $this->Payment->query($subQuery);
                        break;
                }
            }
        }
        if ($this->request->query('amountStatus')) {
            switch ($this->request->query('amountStatus')) {
                case "1" :
                    $conditions ['send_game_status'] = 1;
                    break;
                case "2" :
                    $conditions ['send_game_status'] = 0;
                    break;
            }
        }
        if ($this->request->query('username')) {
            $conditions ['User.username LIKE'] = '%' . $this->request->query('username') . '%';
        }
        if ($this->request->query('amount')) {
            $conditions ['amount'] = $this->request->query('amount');
        }
        if ($this->request->query('game_id')) {
            $conditions ['game_id'] = $this->request->query('game_id');
        }
        if ($this->request->query('channel_id')) {
            $conditions ['channel_id'] = $this->request->query('channel_id');
        }
        if ($this->request->query('gift')) {
            $conditions ['amount !='] = 66800;
            $conditions['channel_id !='] = 100;
        }
        if ($this->request->query('limit')) {
            $limit = $this->request->query('limit');
        } else {
            $limit = 20;
        }
        // query sql
        // all record paging
        /* $this->paginate = array(
             'Payment' => array(
                 'limit' => $limit,
                 'recursive' => 0,
                 'conditions' => $conditions
             )
         );*/
        $payments = $this->Payment->find('all', array('limit' => $limit, 'recursive' => 0, 'conditions' => $conditions, 'order' => 'Payment.created DESC'));
        // $this->autoRender = false;
        // count record

        $count = $this->Payment->find('count', array('order' => 'Payment.id', 'recursive' => 0, 'conditions' => $conditions));
        // count distinct user pay
        $countUser = $this->Payment->find('count', array('recursive' => 0, 'conditions' => $conditions, 'group' => 'Payment.user_id'));
        // sum amount payment
        $sum = $this->Payment->find('first', array('recursive' => 0, 'conditions' => $conditions, 'fields' => array('sum(amount) as total')));

        $this->set(array('payments' => $payments, 'count' => $count, 'sum' => $sum, 'oldUserPayment' => $oldUserPayment[0], 'countUser' => $countUser, '_serialize' => array('payments', 'count', 'sum', 'oldUserPayment', 'countUser')));
    }

    public function report()
    {
        $user = $this->authenticate();
        if (!$user) {
            throw new ForbiddenException ('Chi cho phep khi dang nhap');
        }
        $options = array();
        if (!empty($this->request->query['start_date'])) {
            $options['conditions']['Payment.created > '] = $this->request->query['start_date'];
        }

        if (!empty($this->request->query['end_date'])) {
            $options['conditions']['Payment.created < '] = $this->request->query['end_date'];
        }
        if (!empty($this->request->query['channel_id'])) {
            $options['conditions']['Payment.channel_id = '] = $this->request->query['channel_id'];
        }

        if (!empty($this->request->query['server_id'])) {
            $options['conditions']['Payment.server_id = '] = $this->request->query['server_id'];
        }
        if (!empty($this->request->query['game_id'])) {
            if ($this->request->query('game_id') == '4') {
                $options['conditions']['Payment.game_id = '] = array('3','5');
            }else{
            $options['conditions']['Payment.game_id = '] = $this->request->query['game_id'];
            }
        }

        $options['conditions']['Payment.amount >'] = 0;
        $options['conditions']['Payment.send_game_status'] = 1;
        $options['conditions']['Payment.payment_status'] = 1;
        $options['group'] = 'DATE(Payment.created)';

        $this->Payment->virtualFields = array(
            'sum_amount' => 'SUM(Payment.amount)',
            'day' => 'DATE(Payment.created)'
        );
        $options['fields'] = array('sum_amount', 'day');
        $payments = $this->Payment->find('all', $options);

        $this->set(array(
            'payments' => $payments,
            '_serialize' => array('payments')
        ));
    }


}