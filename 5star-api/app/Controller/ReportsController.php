<?php
	App::uses('AppController', 'Controller');
	/**
	* Games Controller
	*
	* @property Game $Game
	*/
	class ReportsController extends AppController {

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
			$this->Report->recursive = -1;
			
			$options = $this->Report->buildOptions($this->params->query);
			$reports = $this->Report->find('all', $options);
			$this->set(array(
				'reports' => $reports,
				'_serialize' => array('reports')
			));
		}

		
	}
