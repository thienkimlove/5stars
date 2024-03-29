<?php
	App::uses('AppController', 'Controller');
	/**
	* Payments Controller
	*
	* @property Payment $Payment
	*/
	class TokensController extends AppController {
		var $uses = array('Token');
		
		public function add() {
			$user = $this->authenticate();			
			if(!$user) {
				throw new ForbiddenException('Chi cho phep khi dang nhap');
			}
			//prevent hack.
			$this->request->data['user_id'] = $user['User']['id'];
			if (empty($this->request->data['game_id']) && !empty($this->request->data['gameId'])) {
				$this->request->data['game_id'] = $this->request->data['gameId'];
			}
			if (empty($this->request->data['channel_id']) && !empty($this->request->data['channelId'])) {
				$this->request->data['channel_id'] = $this->request->data['channelId'];
			}
			$token = $this->Token->save($this->request->data);
			if(!$token) {
				throw new BadRequestException($this->Token->validationErrors);
			}
			$this->set(array(
				'token' => $token,                   
				'_serialize' => array('token')
			));

		}
	}
