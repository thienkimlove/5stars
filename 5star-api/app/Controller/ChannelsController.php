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

            $user = $this->crmPermission();
            if ($user['User']['role'] == 'channel') {
                $this->set(array(
                    'channels' => array(),
                    'count' => 0,
                    '_serialize' => array('channels', 'count')
                ));
            } else {
                $this->Channel->recursive = 0;
                $options = $this->Channel->buildOptions($this->params->query);
                $options['order'] = array('Channel.created DESC');
                $channels = $this->Channel->find('all', $options);
                $count = $this->Channel->find('count', $options);
                $this->set(array(
                    'channels' => $channels,
                    'count' => $count,
                    '_serialize' => array('channels', 'count')
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

            if (!$this->Channel->exists($id)) {
                throw new NotFoundException(__('Invalid channel'));
            }
            $this->channelPermission($id); 

            $options = array('conditions' => array('Channel.' . $this->Channel->primaryKey => $id));
            $this->Channel->recursive = 0;
            $channel = $this->Channel->find('first', $options);

            $this->set(array(
                'channel' => $channel,
                '_serialize' => array('channel')
            ));
        }
        public function edit($id) {            
            if ($this->request->data) {

                $this->adminPermission();                
                $this->Channel->recursive = -1;
                $channel = $this->Channel->findById($id);
                if (!$channel) {
                    throw new BadRequestException('Không có kênh tồn tại');
                }

                $this->User->id = $channel['Channel']['user_id'];  

                if (empty($this->request->data['User']['password'])) {
                    unset($this->request->data['User']['password']);
                }

                $user = $this->User->save($this->request->data['User']);
                if (!$user) {
                    throw new BadRequestException($this->errorException($this->User->validationErrors));
                }

                $this->Channel->id = $id;
                $this->request->data['Channel']['name'] = $user['User']['fullname'];
                $channel = $this->Channel->save($this->request->data['Channel']);
                if (!$channel) {
                    throw new BadRequestException($this->errorException($this->Channel->validationErrors));
                }                
                $this->view($channel['Channel']['id']);
            }
        }
        public function add() {
            if ($this->request->data) {
                $this->adminPermission();
                //create the user.
                $this->request->data['User']['role'] = 'channel';               
                $this->User->create();

                if (empty($this->request->data['User']['password'])) {
                    $this->request->data['User']['password'] = substr(md5(time()), 0, 8);
                }

                $user = $this->User->save($this->request->data['User']);
                if (!$user) {
                    throw new BadRequestException($this->errorException($this->User->validationErrors));
                }
                $this->Channel->create();
                $this->request->data['Channel']['user_id'] = $user['User']['id'];
                $this->request->data['Channel']['name'] = $user['User']['fullname'];
                $channel = $this->Channel->save($this->request->data['Channel']);
                if (!$channel) {
                    throw new BadRequestException($this->errorException($this->Channel->validationErrors));
                }
                $this->view($channel['Channel']['id']);
            }
        }
    }
