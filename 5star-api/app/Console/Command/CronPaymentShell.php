<?php 
	App::uses('ComponentCollection', 'Controller');
	App::uses('BillingComponent', 'Controller/Component');
	App::uses('CakeEmail', 'Network/Email');
	App::uses('HttpSocket', 'Network/Http' ); 
	class CronPaymentShell extends AppShell {
		public $uses = array('Payment');
		public function main() {
			$this->out('Payment Cron');        
		}

		//this is cron script running
		public function cron()
		{ 
			$collection = new ComponentCollection();
			$this->Billing = new BillingComponent($collection);
			$paymentResend = $this->Payment->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'send_game_status = ' => 0,
					'cron =' => 1
				),
			));
			
			if ($paymentResend) {
                $count = 0;
                $content = '';
				foreach ($paymentResend as $payment) {
					$count ++;
					$content .= 'Payment :' .$count. '\n';
                    ob_start();
                    print_r($payment);
                    $content .= ob_get_clean();
					$log = $this->Billing->sendPaymentToGame($payment, 1);	
					$content .= 'Response :' .json_encode($log). '\n';
				}
                $content .=  'Done';
            
            //send email
            $email = new CakeEmail('stars');                       
            $email->template('log')
            ->emailFormat('text')
            ->from(array('info@5stars.vn' => '5Stars'))
            ->to('do.manh.quan@spectos.com')
            ->bcc(array('hungnt@5stars.vn','manhquan@5stars.vn'))
            ->subject('Cron log')
            ->viewVars(array('log' => $content))
            ->send();

			}
			
		}
	}

?>