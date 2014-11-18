<?php
	class AddUserIdFieldAndFillDataToGamesAndChannels extends CakeMigration {

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
				'create_field' => array(
					'channels' => array(
						'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'name'),
					),
					'games' => array(
						'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'id'),
					),
				),
			),
			'down' => array(
				'drop_field' => array(
					'channels' => array('user_id',),
					'games' => array('user_id',),
				),
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
			$User = ClassRegistry::init('User');
			$Channel = ClassRegistry::init('Channel');
			$Game = ClassRegistry::init('Game');
			if ($direction == 'up') {

				$channels = $Channel->find('all', array('recursive' => -1));
				foreach ($channels as $channel) {
					$User->create();
					$user = $User->save(array(
						'username' => $channel['Channel']['username'].substr(md5(String::uuid()),0 ,5),
						'password' => $channel['Channel']['username'].'1234',
						'email' => strtolower($channel['Channel']['username']).'@5stars.vn',
						'status' => 'active',
						'role' => 'channel',
						'fullname' => $channel['Channel']['name']                    
					));
					if ($user) {						
						$Channel->id = $channel['Channel']['id'];
						$Channel->saveField('user_id', $user['User']['id']);
					} 
				}

				$games = $Game->find('all', array('recursive' => -1));
				foreach ($games as $game) {
					$User->create();
					$user = $User->save(array(
						'username' => $game['Game']['username'].substr(md5(String::uuid()),0 ,5),
						'password' => $game['Game']['username'].'1234',
						'email' => strtolower($game['Game']['username']).'@5stars.vn',
						'status' => 'active',
						'role' => 'game',
						'fullname' => $game['Game']['name']                    
					));
					if ($user) {						
						$Game->id = $game['Game']['id'];
						$Game->saveField('user_id', $user['User']['id']);
					} 
				}

			}
			if ($direction == 'down') {
			   $User->query("Delete from users where role is not null");
			}
			return true;
		}
	}
