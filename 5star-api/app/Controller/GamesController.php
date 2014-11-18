<?php
App::uses('AppController', 'Controller');
/**
* Games Controller
*
* @property Game $Game
*/
class GamesController extends AppController {

    /**
    * index method
    *
    * @return void
    */
    public function index() {
        $user = $this->crmPermission();
        if ($user['User']['role'] == 'game') {
            $this->set(array(
                'games' => array(),
                'count' => 0,
                '_serialize' => array('games', 'count')
            ));
        } else {
            $this->Game->recursive = 0;
            $options = $this->Game->buildOptions($this->params->query);
            $options['order'] = array('Game.created DESC');
            $games = $this->Game->find('all', $options);
            $count = $this->Game->find('count', $options);
            $this->set(array(
                'games' => $games,
                'count' => $count,
                '_serialize' => array('games', 'count')
            ));
        }

    }

    /**
    * view method
    *
    * @throws NotFoundException
    * @param string $id
    * @return void
    */
    public function view($id = null) {            
        if (!$this->Game->exists($id)) {
            throw new NotFoundException(__('Invalid game'));
        }
        $game = Cache::read('game_id_'.$id, 'long');
        if (!$game) {
            $options = array('conditions' => array('Game.' . $this->Game->primaryKey => $id));
            $this->Game->recursive = 0;
            $game = $this->Game->find('first', $options); 
            
            //write to cache
            Cache::write('game_id_'.$id, $game, 'long');
        } 

        $this->set(array(
            'game' => $game,
            '_serialize' => array('game')
        ));
    } 
    public function edit($id) {            
        if ($this->request->data) {

            $this->adminPermission();                
            $this->Game->recursive = -1;
            $game = $this->Game->findById($id);
            if (!$game) {
                throw new BadRequestException('Không có game tương ứng trong hệ thống');
            }

            $this->User->id = $game['Game']['user_id'];  

            if (empty($this->request->data['User']['password'])) {
                unset($this->request->data['User']['password']);
            }

            $user = $this->User->save($this->request->data['User']);
            if (!$user) {
                throw new BadRequestException($this->errorException($this->User->validationErrors));
            }

            $this->Game->id = $id;
            $this->request->data['Game']['name'] = $user['User']['fullname'];
            $game = $this->Game->save($this->request->data['Game']);
            if (!$game) {
                throw new BadRequestException($this->errorException($this->Game->validationErrors));
            }                
            $this->view($game['Game']['id']);
        }
    }
    public function add() {
        if ($this->request->data) {
            $this->adminPermission();
            //create the user.
            $this->request->data['User']['role'] = 'game';               
            $this->User->create();

            if (empty($this->request->data['User']['password'])) {
                $this->request->data['User']['password'] = substr(md5(time()), 0, 8);
            }

            $user = $this->User->save($this->request->data['User']);
            if (!$user) {
                throw new BadRequestException($this->errorException($this->User->validationErrors));
            }
            $this->Game->create();
            $this->request->data['Game']['user_id'] = $user['User']['id'];
            $this->request->data['Game']['name'] = $user['User']['fullname'];
            $game = $this->Game->save($this->request->data['Game']);
            if (!$game) {
                throw new BadRequestException($this->errorException($this->Game->validationErrors));
            }
            $this->view($game['Game']['id']);
        }
    }
}
