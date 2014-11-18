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
			$this->Game->recursive = 0;
			//$this->Game->unbindAll(false);
			$options = $this->Game->buildOptions($this->params->query);
			$games = $this->Game->find('all', $options);
			$this->set(array(
				'games' => $games,
				'_serialize' => array('games')
			));
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
			$options = array('conditions' => array('Game.' . $this->Game->primaryKey => $id));
			$game = $this->Game->find('first', $options);

			$this->set(array(
				'game' => $game,
				'_serialize' => array('game')
			));
		}  
	}
