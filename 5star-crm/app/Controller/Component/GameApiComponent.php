<?php
App::uses('HttpSocket', 'Network/Http');

class Resource {

	protected $_resource;
	
	protected $_ext = '.json';

	protected $_socket;

	public function __construct($name, $user = null, $password = null) {
		$this->_resource = $name;
		$this->_socket = new HttpSocket(array('ssl_verify_peer' => false));

		if(isset($user) && isset($password)) {
			$this->_socket->configAuth('Basic', $user, $password);
		}
	}

	public function request($uri, $options = array()) {
		if(!isset($options['method'])) {
			$options['method'] = 'POST';
		}
		if(!isset($options['data'])) {
			$options['data'] = array();
		}
		$response = $this->_socket->request(array(
			'method' => $options['method'],
			'body' => $options['data'],
			'uri' => array(
				'scheme' => Configure::read('Api.scheme'),
				'host' => Configure::read('Api.host'),
				'path' => Configure::read('Api.path') . $this->_resource . $uri. $this->_ext,
			)
		));
		$result = json_decode($response->body, true);
		if($result == null) {
			throw new FatalErrorException('Could not parse api server response: ' . $response->body);
		}
		return $result;
	}

	public function query(array $params = array()) {
		$response = $this->_socket->request(array(
			'method' => 'GET',
			'uri' => array(
				'scheme' => Configure::read('Api.scheme'),
				'host' => Configure::read('Api.host'),
				'path' => Configure::read('Api.path') . $this->_resource. $this->_ext,
				'query' => $params
			)
		));
		$result = json_decode($response->body, true);
		if($result == null) {
			throw new FatalErrorException('Could not parse api server response: ' . $response->body);
		}
		return $result;
	}

	public function get($id) {
		$response = $this->_socket->request(array(
			'method' => 'GET',
			'uri' => array(
				'scheme' => Configure::read('Api.scheme'),
				'host' => Configure::read('Api.host'),
				'path' => Configure::read('Api.path') . $this->_resource . '/' . $id  . $this->_ext
			)
		));
		$result = json_decode($response->body, true);
		if($result == null) {
			throw new FatalErrorException('Could not parse api server response: ' . $response->body);
		}
		return $result;
	}

	public function delete($id) {
		$response = $this->_socket->request(array(
			'method' => 'DELETE',
			'uri' => array(
				'scheme' => Configure::read('Api.scheme'),
				'host' => Configure::read('Api.host'),
				'path' => Configure::read('Api.path') . $this->_resource . '/' . $id  . $this->_ext
			)
		));
		$result = json_decode($response->body, true);
		if($result == null) {
			throw new FatalErrorException('Could not parse api server response: ' . $response->body);
		}
		return $result;
	}

	public function add(array $data = array()) {
		$response = $this->_socket->request(array(
			'method' => 'POST',
			'body' => $data,
			'uri' => array(
				'scheme' => Configure::read('Api.scheme'),
				'host' => Configure::read('Api.host'),
				'path' => Configure::read('Api.path') . $this->_resource   . $this->_ext
			)
		));      
		$result = json_decode($response->body, true);
		if($result == null) {
			throw new FatalErrorException('Could not parse api server response: ' . $response->body);
		}
		return $result;
	}

	public function edit($id, array $data = array()) {
		$response = $this->_socket->request(array(
			'method' => 'POST',
			'body' => $data,
			'uri' => array(
				'scheme' => Configure::read('Api.scheme'),
				'host' => Configure::read('Api.host'),
				'path' => Configure::read('Api.path') . $this->_resource . '/' . $id . $this->_ext
			)
		));
		$result = json_decode($response->body, true);
		if($result == null) {
			throw new FatalErrorException('Could not parse api server response: ' . $response->body);
		}
		return $result;
	}
}

class GameApiComponent extends Component {
	
	public $components = array('Session');
	
	public function resource($name) {
		$resource = new Resource(
			Inflector::underscore(Inflector::pluralize($name)), 
			$this->Session->read('Auth.User.Login'), 
			$this->Session->read('Auth.User.Password')
		);
		
		return $resource;
	}
}
