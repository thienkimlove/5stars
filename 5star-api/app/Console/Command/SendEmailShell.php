<?php      
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http' ); 
class SendEmailShell extends AppShell {
    public $uses = array('User');
    public function main() {
        $this->out('SendEmailShell');        
    }

    public function send() {         
        ini_set('max_execution_time', 0);
        $test = array(
           array('User' => array('email' =>'manhquan@5stars.vn'))
        );
        $this->User->unbindAll();
        $users = $this->User->find('all', array('fields' => array('email'), 'conditions' => array('email not like "%@facebook.com%"'), 'order' => array('id DESC')));
        $count = 1;
        foreach ($users as $user) {
            if (!empty($user['User']['email'])) {
                $count++;
                try {                    
                    $email = new CakeEmail('stars');                       
                    $email->template('open')
                    ->emailFormat('html')
                    ->from(array('info@5stars.vn' => '5Stars MobileGames Ltd'))
                    ->to($user['User']['email'])
                    //->bcc(array('manhquan@5stars.vn'))
                    ->subject('Mỹ Hầu Vương | Tri ân người chơi tặng Gift code 500k nhân ngày Openbeta')
                    ->send();           

                    echo $count.' sent to '.$user['User']['email'].' waiting  10 sec...'. "\n";
                    
                    
                    sleep(1); 
                } catch(Exception $e) {
                    echo $e->getMessage();
                    echo $count.'cannot send to '.$user['User']['email']. "\n";
                }   
            }
        }  
    }
}

?>