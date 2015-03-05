<?php
	App::uses('AppController', 'Controller');
	/**
	* Games Controller
	*
	* @property Game $Game
	*/
	class HistoriesController extends AppController {

		/**
		* index method
		*
		* @return void
		*/
		public function index() {
			$user = $this->authenticate();
			if (!$user || $user['User']['role'] != 'admin') {
				throw new ForbiddenException();
			}
			$this->History->recursive = -1;
			
			$options = $this->History->buildOptions($this->params->query);
			$histories = $this->History->find('all', $options);
			$this->set(array(
				'histories' => $histories,
				'_serialize' => array('histories')
			));
		}

		
	}
