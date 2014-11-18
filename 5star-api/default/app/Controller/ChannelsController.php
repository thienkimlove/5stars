<?php
	App::uses('AppController', 'Controller');
	/**
	* Channels Controller
	*
	* @property Channel $Channel
	*/
	class ChannelsController extends AppController {

		/**
		* index method
		*
		* @return void
		*/
		public function index() {
			$this->Channel->recursive = 0;
			//$this->Channel->unbindAll(false);
			$options = $this->Channel->buildOptions($this->params->query);
			$channels = $this->Channel->find('all', $options);
			$this->set(array(
				'channels' => $channels,
				'_serialize' => array('channels')
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
			if (!$this->Channel->exists($id)) {
				throw new NotFoundException(__('Invalid channel'));
			}
			$options = array('conditions' => array('Channel.' . $this->Channel->primaryKey => $id));
			$channel = $this->Channel->find('first', $options);
			
			$this->set(array(
				'channel' => $channel,
				'_serialize' => array('channel')
			));
		}  
	}
