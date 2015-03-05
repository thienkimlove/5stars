<?php 

App::uses('SmtpTransport', 'Network/Email');
App::uses('CakeLog', 'Log');

class StarsSmtpTransport extends SmtpTransport {

	public function send(CakeEmail $email) {
		if(Configure::read('debug')) {
			$allowedAdr = array();
			foreach ($email->to() as $adr => $name) {
				if(strpos($adr, '@5stars.vn') !== false) {
					$allowedAdr[$adr] = $name;
				} else {
					CakeLog::debug('Development mode, sending email to '.$adr.' ['.$name.'] will be skipped');                    
				}
			}
			if(sizeof($allowedAdr) > 0) {
				$email->to($allowedAdr);
				parent::send($email);
				$this->_content['skipped'] = false;
			} else {
				$this->_content['skipped'] = true;
			}
		} else {
			parent::send($email);
			$this->_content['skipped'] = false;
		}
		return $this->_content;
	}

}
?>