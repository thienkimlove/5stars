<?php
class TokensReportsController extends AppController
{
    public $uses = array('History');

    public function index()
    {
        $user = $this->authenticate();
        if (!$user) {
            throw new ForbiddenException('Chi cho phep khi dang nhap');
        }

        $conditions = array();
        if ($this->request->query('day1Token') && $this->request->query('day2Token')) {
            $day0 = date('Y-m-d', strtotime($this->request->query('day1Token')) - (24 * 60 * 60));
            $day1 = date('Y-m-d', strtotime($this->request->query('day1Token')));
            $day2 = date('Y-m-d', strtotime($this->request->query('day2Token')));
            $conditions = array('History.time BETWEEN ? AND ?' => array(
                $day1, $day2));
        }
        if ($this->request->query('channel_id')) {
            $conditions['channel_id'] = $this->request->query('channel_id');
        }
        if ($this->request->query('game_id')) {
            $conditions['game_id'] = $this->request->query('game_id');
        }
        $group = array();
        if ($this->request->query('group')) {
            $group = array('History.user_id');
        }
        if ($this->request->query('action')) {
            if ($this->request->query('action') == "register") {
                $conditions['action'] = 'register';
            } else if ($this->request->query('action') == "login") {
                $conditions['action'] = array('login', 'relogin');
            }
        }
        // $this->paginate= array('History'=> array('limit'=>20,'order'=>'History.id','recursive'=>-1,'conditions'=>$conditions,'group'=>$group));
        $count = $this->History->find('count', array('recursive' => -1, 'conditions' => $conditions, 'group' => $group));
        if ($this->request->query('game_id')) {
            $conditions['game_id'] = $this->request->query('game_id');
            if ($this->request->query('game_id') == 3 || $this->request->query('game_id') == 5) {
                $conditions['game_id'] = array('3', '5', '');
            }
        }

        $total = $this->History->find('count', array('recursive' => -1, 'conditions' => $conditions, 'group' => $group));
        $this->set(array('histories' => array(), 'count' => $count, 'total' => $total, '_serialize' => array('histories', 'count', 'total')));
    }

    public function a1()
    {
        $user = $this->authenticate();
        if (!$user) {
            throw new ForbiddenException('Chi cho phep khi dang nhap');
        }
        $a1[0] = null;

        if ($this->request->query('day1Token') && $this->request->query('day2Token')) {
            $day0 = date('Y-m-d', strtotime($this->request->query('day1Token')) - (24 * 60 * 60));
            $day1 = date('Y-m-d', strtotime($this->request->query('day1Token')));
            $day2 = date('Y-m-d', strtotime($this->request->query('day2Token')));
            $action = "('login','register','relogin')";
            $subQuery = "SELECT COUNT(distinct t1.user_id) AS a1 FROM histories t1 , histories t2 ,users t3 WHERE
								t3.id=t1.user_id and t3.id=t2.user_id and t1.time BETWEEN '$day1' AND '$day2' AND t2.time BETWEEN
								'$day0' and '$day1' and t2.action IN $action and t1.action IN $action ";
            if ($this->request->query('channel_id')) {
                $subQuery .= " AND t1.channel_id =" . $this->request->query('channel_id');
                $subQuery .= " AND t2.channel_id =" . $this->request->query('channel_id');
            }
            if ($this->request->query('game_id')) {
                if ($this->request->query('game_id') == '4') {
                    $subQuery .= " AND t1.game_id IN ('3','5') ";
                    $subQuery .= " AND t2.game_id IN ('3','5') ";
                } else {
                    $subQuery .= " AND t1.game_id =" . $this->request->query('game_id');
                    $subQuery .= " AND t2.game_id =" . $this->request->query('game_id');
                }


            }

            $a1 = $this->History->query($subQuery);
        }
        $this->set(array('A1' => $a1[0], '_serialize' => array('A1')));
    }

    public function report()
    {
        $user = $this->authenticate();
        if (!$user) {
            throw new ForbiddenException('Chi cho phep khi dang nhap');
        }
        $options = array();
        if (!empty($this->request->query['start_date'])) {
            $options['conditions']['History.time > '] = $this->request->query['start_date'];
        }

        if (!empty($this->request->query['end_date'])) {
            $options['conditions']['History.time < '] = $this->request->query['end_date'];
        }

        if (!empty($this->request->query['channel_id'])) {
            $options['conditions']['History.channel_id'] = $this->request->query('channel_id');
        }
        if (!empty($this->request->query['game_id'])) {
            if ($this->request->query('game_id') == '4') {
                $options['conditions']['History.game_id'] = array('3', '5');
            } else {
                $options['conditions']['History.game_id'] = $this->request->query['game_id'];
            }
        }

        if (!empty($this->request->query['action'])) {
            if ($this->request->query('action') == "register") {
                $options['conditions']['History.action'] = 'register';
            } else if ($this->request->query('action') == "login") {
                $options['conditions']['History.action'] = array('login', 'relogin');
            }
        }
        $options['group'] = 'DATE(History.time)';
        $this->History->virtualFields = array(
            'total_unique_id' => 'Count(DISTINCT History.user_id)',
            'day' => 'DATE(History.time)'
        );
        $options['fields'] = array('total_unique_id', 'day');
        $options['recursive']=-1;
        $histories = $this->History->find('all', $options);
        $this->set(array('histories' => $histories, '_serialize' => array('histories')));
    }

    public function getListUserId()
    {
        $user = $this->authenticate();
        if (!$user) {
            throw new ForbiddenException('Chi cho phep khi dang nhap');
        }
        $options = array();
        if (!empty($this->request->query['start_date'])) {
            $options['conditions']['History.time > '] = $this->request->query['start_date'];
        }

        if (!empty($this->request->query['end_date'])) {
            $options['conditions']['History.time < '] = $this->request->query['end_date'];
        }

        if (!empty($this->request->query['channel_id'])) {
            $options['conditions']['History.channel_id'] = $this->request->query('channel_id');
        }
        if (!empty($this->request->query['game_id'])) {
            if ($this->request->query('game_id') == '4') {
                $options['conditions']['History.game_id'] = array('3', '5');
            } else {
                $options['conditions']['History.game_id'] = $this->request->query['game_id'];
            }
        }
        $options['group'] = 'user_id';
        $options['recursive']=-1;
        $options['fields'] = array('user_id');
        $histories = $this->History->find('all', $options);
        $this->set(array('histories' => $histories, '_serialize' => array('histories')));
    }
}