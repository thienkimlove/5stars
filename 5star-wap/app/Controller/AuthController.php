<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');



/**
* AuthController
*
* @property User $User
*/
class AuthController extends AppController {
    public $layout ='auth';
    public $helpers = array('Html');
    public $components = array('Captcha');
    public function profile()
    {
        //show message only.
        if (!empty($this->request->query['payment'])) {
            $this->set('payment', 1);
        }
        if (isset($this->request->query['share'])) {
            $this->set('share', $this->request->query['share']);
        }
        $this->set('scriptInclude', $this->Session->read('Auth.scriptInclude'));
        $this->set('token', $this->Session->read('Auth.token'));
    }


    public function trial() {
        $this->autoRender = false;
        $time = time();
        $username = '5stars'.$time;
        $email = '5stars'.$time.'@5stars.vn';
        $password = substr(md5($time), 0, 8); 
        $response = array('uid' => null, 'login' => '', 'password' => '');

        try {
            $user = $this->GameApi->resource('User')->add(array(
                'fullname' => '5stars User',
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'status' => 'active'
            ));  
            if (!empty($user['user']) && $user['user']['User']['status'] == 'active') {
                $response['uid'] = $user['user']['User']['id'];  
                $response['login'] = $username;
                $response['password'] = $password;           
            }         
        } catch (Exception $e) {
            //
        } 
        echo json_encode($response);
    }

    public function change() {
        $this->autoRender = false;
        if (!empty($this->request->data)) {
            $data = $this->request->data;
            $response = array('status' => false, 'message' => null, 'debug' => array());  

            $this->Session->write('Auth.User.Login', $data['user']['login']);
            $this->Session->write('Auth.User.Password', $data['user']['password']);

            $uid = null;

            try {
                $result = $this->GameApi->resource('User')->request('/auth');
                if (!empty($result['user']) && $result['user']['User']['status'] == 'active') {
                    $uid = $result['user']['User']['id'];
                }     
            } catch (Exception $e) {

            }

            if ($uid) {
                $request = array(
                    'new_username' => $data['coding']['login'],
                    'new_password' => $data['coding']['password']
                );
                if (!empty($data['coding']['email'])) {
                    $request['new_email'] = $data['coding']['email'];
                }

                try {

                    $result = $this->GameApi->resource('User')->edit($uid, $request);

                    if (!empty($result['user'])) {

                        $response['status'] = true;                                    
                    } 

                } catch (Exception $e) {  
                    $response['message'] = $result['name'];
                } 
            }  else {
                $response['message'] = 'Thông tin đăng nhập ban đầu không chính xác!';
            }



            $this->Session->delete('Auth.User.Login');
            $this->Session->delete('Auth.User.Password');

            echo json_encode($response);   
        } 

    }

    public function social() {
        if (!empty($this->request->query)) {
            $apiParams = $this->request->query;
            if (!empty($apiParams['external'])) {
                $postContent = $this->Session->read('Auth.content'); 
                $game = $this->GameApi->resource('Game')->get($postContent['gameId']);                    
                $gameUrl = ($game['game']['Game']['wakeup_syntax']) ? $game['game']['Game']['wakeup_syntax'] : Configure::read('wap').'/auth/profile?share=1';
                $errorUrl = ($game['game']['Game']['wakeup_syntax']) ? $game['game']['Game']['wakeup_syntax'] : Configure::read('wap').'/auth/profile?share=0';
                switch($postContent['type']) {
                    case "invite":
                        if (empty($apiParams['code'])) {                          
                            $this->set('message', 'Không nhận được phản hồi thông tin từ Facebook. Xin thử lại');  
                        } 
                        $this->set('content', $postContent);                    
                        $this->set('gameUrl', $errorUrl);
                        $this->render('invite');
                        break; 
                    default :
                        if (!empty($apiParams['code'])) {
                            $success = $this->_graphPost($apiParams['code'], $postContent);
                            if ($success == 1) {
                                $this->redirect($gameUrl);
                            } else {
                                $this->set('gameUrl', $errorUrl);
                                $this->set('message', 'Không nhận được phản hồi thông tin từ Facebook. Xin thử lại');                          
                                $this->render('post');
                                return;
                            }
                        } else {
                            $this->set('gameUrl', $errorUrl);
                            $this->set('message', 'Không nhận được phản hồi thông tin từ Facebook. Xin thử lại');                          
                            $this->render('post');
                            return;
                        }
                        break;        
                }
            } else {
                $this->Session->write('Auth.CurrentUrl', $this->_curPageURL());
                $scriptInclude = (empty($apiParams['script'])) ? "" : urldecode($apiParams['script']);                               
                $this->Session->write('Auth.scriptInclude', $scriptInclude);
                $this->Session->write('Auth.content', $apiParams);
                $this->redirect("https://www.facebook.com/dialog/oauth?client_id=" .Configure::read('fbId'). "&redirect_uri=" .Configure::read('wap'). "/auth/social?external=1&scope=email,publish_stream&response_type=code");
            }            
        }
    }

    //facebook register.
    public function register() {
        $response = array (
            'status' => 'error',
            'message' => 'Thông tin không hợp lệ'
        );
        $apiParams = $this->Session->read('Auth.ApiParams');
        $userProfile = $this->Session->read('Auth.Facebook');

        if (!empty ($apiParams)) {
            $user = $this->GameApi->resource('User')->add(array(
                'fullname' => $userProfile['information']->name,
                'username' => 'facebook'.time(),
                'email' => $userProfile['information']->email,
                'password' => substr(md5(time()), 0, 8),
                'facebook_id' => $userProfile['information']->id
            ));                                    
            if (!empty($user['user'])) {
                $response['status'] = 'success';
                $response['link'] = $this->_setupUserAndAccountFacebook($user['user']['User'], $apiParams['channelId'], $apiParams['gameId'], $apiParams['token'], true);   
                if (!empty($userProfile['friends']->data)) {
                    $friends = array();
                    foreach ($userProfile['friends']->data as $friend) {
                        array_push($friends, $friend->id);
                    }
                    $this->GameApi->resource('Facebook')->add($friends);
                }                  
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Không thể tạo tài khoản mới bằng thông tin Facebook';
            }
        }
        if ($response['status'] == 'error') {
            $this->Session->setFlash($response['message']);
            $this->redirect($this->Session->read('Auth.CurrentUrl'));
        } else {
            $this->redirect($response['link']);
        }
    }

    protected function _facebook($userProfile, $apiParams) {

        $response = array (
            'status' => 'error',
            'message' => 'Thông tin không hợp lệ'
        );
        $facebookUser = $this->GameApi->resource('User')->request('/viewByFacebookId', array (
            'data' => array (
                'facebook_id' => $userProfile['information']->id
            )
        ));
        if (!empty ($facebookUser['user']['User'])) {
            $user = $facebookUser['user']['User'];
            if ($user['status'] != 'active') {
                $response['status'] = 'error';
                $response['message'] = 'Tài khoản của bạn đã bị khóa';

            } else {
                $response['status'] = 'success';
                $response['link'] = $this->_setupUserAndAccountFacebook($user, $apiParams['channelId'], $apiParams['gameId'], $apiParams['token'], false);
            }                
        } else {
            if (!empty($userProfile['information']->email)) {
                $emailUser = $this->GameApi->resource('User')->request('/viewByFacebookEmail', array (
                    'data' => array (
                        'email' => $userProfile['information']->email
                    )
                ));
                if (!empty ($emailUser['user']['User'])) {
                    $user = $emailUser['user']['User'];
                    if ($user['status'] != 'active') {
                        $response['status'] = 'error';
                        $response['message'] = 'Tài khoản của bạn đã bị khóa';
                    }  else {
                        $response['status'] = 'success';
                        $response['link'] = $this->_setupUserAndAccountFacebook($user, $apiParams['channelId'], $apiParams['gameId'], $apiParams['token'], false, $userProfile['information']->id);
                    }
                } else {                    
                    $response['status'] = 'nonExistFacebookId';              
                } 
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Không lấy được email từ tài khoản facebook của bạn. Xin kiểm tra lại trong phần Setting của tài khoản Facebook!';
            }

        }
        return $response;   
    }


    protected function _setupUserAndAccountFacebook($user, $channelId, $gameId, $token, $isRegister, $fbId = '') {

        $this->Session->write('Auth.User.Id', $user['id']);
        $this->Session->write('Auth.User.ChannelId', $channelId);
        $this->Session->write('Auth.User.GameId', $gameId);
        $this->Session->write('Auth.User.Login', $user['username']);
        $this->Session->write('Auth.User.Password', Configure :: read('channelPassword'));

        if ($fbId) {
            $result = $this->GameApi->resource('User')->edit($this->Session->read('Auth.User.Id'), array (
                'data' => array (
                    'facebook_id' => $fbId
                )
            ));
        }
        $action  = ($isRegister) ? 'register' : 'relogin';
        $token = $this->GameApi->resource('Token')->add(array(
            'gameId' => $gameId,
            'channelId' => $channelId,
            'action' => $action,
            'token' => $token
        ));
        $game = $this->GameApi->resource('Game')->get($gameId);
        $wakeUpSyntax = $game['game']['Game']['wakeup_syntax'];     
        if ($wakeUpSyntax) {
            return $wakeUpSyntax;
        }  else {
            return 'profile';
        }    
    }

    private function _graphPost($code, $postContent) {
        $success = 0;
        $url = 'https://graph.facebook.com/oauth/access_token?client_id='.Configure::read('fbId').'&redirect_uri='.urlencode(Configure::read('wap').'/auth/social?external=1').'&client_secret='.Configure::read('fbSecret').'&code='.$code;
        $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false, 'timeout' => 200));
        $response = $HttpSocket->get($url);

        if (strpos($response->body, 'access_token=') !== FALSE) {
            $temp = explode('&', $response->body);
            $accessToken = str_replace('access_token=', '', $temp[0]);
            $response = $HttpSocket->get("https://graph.facebook.com/me?access_token=". $accessToken);
            $facebook['information'] = json_decode($response->body);  

            $url = 'https://graph.facebook.com/'.$facebook['information']->id.'/feed';
            switch($postContent['content_type']) {
                default :
                    $content =  $facebook['information']->name.' trong Bá Khí Giang Hồ lấy danh hiệu '. $postContent['name'].' xuất sắc chinh phục '.$postContent['map'].' thành công với số điểm '.$postContent['point'].'.'.$facebook['information']->name.' hô to: Ai dám vào Bá Khí Giang Hồ tỷ thí cùng ta?';
                    break;
            }

            $params = array(
                'access_token' => $accessToken,
                'link' => 'http://bakhi.5stars.vn',
                'name' => 'Bá Khí Giang Hồ',
                'description' => $content,
                'picture' => 'http://bakhi.5stars.vn/img/feed.png',
                'caption' => ''
            );
            $response = $HttpSocket->post($url, $params);                         
            $success = 1;
        } 
        return $success;
    }

    private function _graph($code) {
        $facebook = array();
        $url = 'https://graph.facebook.com/oauth/access_token?client_id='.Configure::read('fbId').'&redirect_uri='.urlencode(Configure::read('wap').'/auth/?external=1').'&client_secret='.Configure::read('fbSecret').'&scope=email&code='.$code;

        $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false, 'timeout' => 200));
        $response = $HttpSocket->get($url);			
        if (strpos($response->body, 'access_token=') !== FALSE) {
            $temp = explode('&', $response->body);
            $accessToken = str_replace('access_token=', '', $temp[0]);
            $response = $HttpSocket->get("https://graph.facebook.com/me?access_token=". $accessToken);
            $facebook['information'] = json_decode($response->body);    

            //get friend lists.
            $response = $HttpSocket->get("https://graph.facebook.com/me/friends?access_token=". $accessToken);
            $facebook['friends'] = json_decode($response->body);
        } 
        return $facebook;
    }

    protected  function _curPageURL() {
        $pageURL = 'http';            
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    public function index() {

        if (!empty($this->request->query))
        {  
            $apiParams = $this->request->query;    
            if (!empty($apiParams['external'])) {
                $apiParams = $this->Session->read('Auth.ApiParams');                    
                if (!empty($this->request->query['code'])) {
                    //success from facebook.
                    // See if there is a user from a cookie
                    $userProfile = $this->_graph($this->request->query['code']);
                    if ($userProfile) {
                        $response = $this->_facebook($userProfile, $apiParams);                            
                        if ($response['status'] == 'success') {                                   
                            $this->redirect($response['link']);
                        } elseif ($response['status'] == 'error') {
                            $apiParams['message'] = $response['message'];
                        } else {
                            //show confim.        
                            $this->Session->write('Auth.Facebook', $userProfile);  
                            $this->Session->write('Auth.ApiParams', $apiParams);                      
                            $this->render('confirm');
                            return;
                        }

                    } else {
                        $apiParams['message'] = 'Không nhận được phản hồi thông tin từ Facebook. Xin thử lại';
                    }                         

                } else {
                    $apiParams['message'] = 'Không nhận được phản hồi thông tin từ Facebook. Xin thử lại';
                }

            } else {
                $this->Session->write('Auth.ApiParams', $apiParams);
                $this->Session->write('Auth.CurrentUrl', $this->_curPageURL()); 
                $scriptInclude = (empty($apiParams['script'])) ? "" : urldecode($apiParams['script']);  
                $token = (empty($apiParams['token'])) ? "" : $apiParams['token'];               
                $this->Session->write('Auth.scriptInclude', $scriptInclude);
                $this->Session->write('Auth.token', $token);

                $game = $this->GameApi->resource('Game')->get($apiParams['gameId']);
                $wakeUpSyntax = $game['game']['Game']['wakeup_syntax'];                


                //if user login already then create the login token and redirect to game.
                if ($this->Session->read('Auth.User.Id') && ($this->Session->read('Auth.User.ChannelId') == $apiParams['channelId']) && ($this->Session->read('Auth.User.GameId') == $apiParams['gameId'])) {
                    $apiParams['action'] = 'relogin';
                    $token = $this->GameApi->resource('Token')->add($apiParams);
                    if (!empty($token['token']['Token']['token'])) {
                        $this->set('wakeUpSyntax', $wakeUpSyntax);
                        $this->render('error');                   
                        return;
                    } else {
                        $this->Session->delete('Auth');
                    }   
                }
            }             


            $this->set('params',$apiParams); 
            if ($this->request->is('post')) {
                $params = $this->request->data;
                if (empty($params['formType']) || ($params['formType'] == 'login')) {
                    $this->Session->write('Auth.User.Login',$params['login']);
                    $this->Session->write('Auth.User.Password',$params['password']);
                    try {
                        $result = $this->GameApi->resource('User')->request('/auth');
                    } catch (Exception $e) {
                        $this->Session->delete('Auth');
                        $this->Session->setFlash($result['name']);
                        return;
                    }

                    if(empty($result['user'])) {
                        $this->Session->delete('Auth');
                        $this->Session->setFlash('Thông tin đăng nhập không chính xác');
                        return;
                    }

                    if($result['user']['User']['status'] != 'active') {
                        $this->Session->delete('Auth');
                        $this->Session->setFlash('Tai khoản của bạn đã bị khóa');
                        return;
                    }


                    $this->Session->write('Auth.User.Id', $result['user']['User']['id']);
                    $this->Session->write('Auth.User.ChannelId', $params['channelId']);
                    $this->Session->write('Auth.User.GameId', $params['gameId']);
                    //add token.
                    $params['action'] = 'login';
                    $token = $this->GameApi->resource('Token')->add($params);
                    if (!empty($token['token'])) {  
                        if ($wakeUpSyntax) {
                            $this->redirect($wakeUpSyntax); 
                        }  else {
                            $this->redirect('profile'); 
                        }         
                        return;
                    } else {
                        $this->Session->delete('Auth');
                        $this->Session->setFlash($token['name']);
                    }   

                } else {
                    if (empty($params['email'])) {
                        $params['email'] = '5stars'.time().'@5stars.vn';
                    }
                    $user = $this->GameApi->resource('User')->add($params);                        
                    if (!empty($user['user'])) {
                        $this->Session->write('Auth.User.Id',$user['user']['User']['id']);
                        $this->Session->write('Auth.User.Login',$user['user']['User']['username']);
                        $this->Session->write('Auth.User.Password',$params['password']);

                        $this->Session->write('Auth.User.ChannelId', $params['channelId']);
                        $this->Session->write('Auth.User.GameId', $params['gameId']);
                        //add token.
                        $params['action'] = 'register';
                        $token = $this->GameApi->resource('Token')->add($params);
                        if (!empty($token['token'])) {
                            if ($wakeUpSyntax) {
                                $this->redirect($wakeUpSyntax); 
                            }  else {
                                $this->redirect('profile'); 
                            }  
                            return;
                        } else {
                            $this->Session->delete('Auth');
                            $this->Session->setFlash($token['name']);
                        }   
                    } else {
                        $this->Session->setFlash($user['name']);
                    }
                }
            } 
        }  else {
            throw new BadRequestException();
        }

    }          


    public function logout($redirect = null) {
        if ($this->Session->check('Auth.CurrentUrl')) {
            $redirect = $this->Session->read('Auth.CurrentUrl');
        } else {
            $redirect = "/";
        }
        $this->Session->delete('Auth');
        $this->redirect($redirect);

    }

    public function payment() {
        if (!empty($this->request->query)) {
            $apiParams = $this->request->query; 
            
            //remove it after google processs.
            /*if ($apiParams['gameId'] == 9 && $apiParams['channelId'] == 1) {
                $this->redirect('http://muauto.5stars.vn');
                return;
            }  */

            $scriptInclude = (empty($apiParams['script'])) ? "" : urldecode($apiParams['script']);  
            $token = (empty($apiParams['token'])) ? "" : $apiParams['token'];               
            $this->Session->write('Auth.scriptInclude', $scriptInclude);
            $this->Session->write('Auth.token', $token);

            $game = $this->GameApi->resource('Game')->get($apiParams['gameId']);
            $wakeUpSyntax = $game['game']['Game']['wakeup_syntax'];
            $this->set('wakeUpSyntax', $wakeUpSyntax);
            $this->set('params',$apiParams);
            if ($this->request->is('post')) {
                try {
                    $payment = $this->GameApi->resource('Payment')->add($this->request->data);                 
                    if (!empty($payment['payment']['Payment']) && $payment['payment']['Payment']['payment_status'] == 1) { 
                        //for replace IOS payment in submit time.
                        if (!empty($this->request->query['web'])) { 
                            $this->redirect('http://myhauvuong.5stars.vn/payments?success=1');
                        } else {                             
                            if ($wakeUpSyntax) {
                                $this->redirect($wakeUpSyntax); 
                            }  else {
                                $this->redirect('profile?payment=1'); 
                            }        
                        }      
                    } else {
                        $this->Session->setFlash($payment['name']);                    
                    }
                } catch (Exception $e) {
                    $this->Session->setFlash($e->getMessage());
                }

            }
        }

    }
    //draw captcha image 
    public function captcha() {
        $this->autoLayout = false;
        $this->Captcha->text_color = new Securimage_Color ( '#' . substr ( md5 ( rand ( 100000, 999999 ) ), 0, 6 ) );
        $this->Captcha->num_lines = 0;
        $this->Captcha->perturbation = 0;
        $this->Captcha->show ();
    }
    //get password
    public function lost(){
        $this->autoLayout = false;
        $this->autoRender = false; 
        if ($this->request->is ( 'post' )) {
            if ($this->Captcha->check ( $this->request->data ['captcha'] ) == false) {
                $rs['status'] =false;
                $rs['message'] = 'Sai mã xác nhận';
                return json_encode($rs);
            } else {
                $status = $this->GameApi->resource ( 'User' )->request ( '/requestPassword', array (
                    'data' => array (
                        'email' => $this->request->data ['email']
                    )
                    )
                ); 
                if (isset ( $status ['status'] )) {
                    $rs['status'] =true;
                    $rs['message'] = 'Mật khẩu đã được đổi , xin mời bạn vào mail để lấy mật khẩu mới';
                    return json_encode($rs);
                } else {
                    $rs['status'] =false;
                    $rs['message'] = $status ['name'];
                    return json_encode($rs);
                }
            }
        }
    }

    //change user password
    public function changePassword() {
        $this->autoLayout =false;
        $this->autoRender = false;
        if ($this->request->is ( 'post' )) {
            $error = null;
            if (empty($this->request->data ['password'])) {
                $error ['message'] = 'Mật khẩu cũ không được Để Trống';
            }
            if (empty($this->request->data ['passwordn'])) {
                $error['message'] = 'Mật khẩu mới không được Để Trống';
            }
            if (empty($this->request->data['passwordr'])){
                $error ['message'] = 'Xác nhận mật khẩu Không được Để Trống';
            }
            if ($this->request->data ['password'] != $this->Session->read ('Auth.User.Password')) {
                $error ['message'] = 'Mật khẩu cũ không đúng';
            }
            if ($this->request->data ['passwordn'] != $this->request->data ['passwordr']) {
                $error ['message'] = 'Mật khẩu mới và xác nhận mật khẩu không trùng nhau';
            }
            if (empty($error)){
                $data ['id'] = $this->Session->read ( 'Auth.User.Id' );
                $data ['new_password'] = $this->request->data ['passwordn'];
                try {
                    $user = $this->GameApi->resource ('User')->request( '/edit/'.$data['id'], array('data' => $data));
                } catch ( Exception $e ) {
                    return '{"message":"Có lỗi xảy ra, vui lòng thử lại sau","status":0}';
                }
                if (isset($user ['user'])){
                    $this->Session->write('Auth.User.Password',$this->request->data ['passwordn']);
                    return '{"message":"Bạn đã đổi mật khẩu thành công","status":1}';
                } else {
                    if (isset ($user['error'])){
                        return '{"message":"'.json_decode($user['error']['message']).'","status":0}';
                    } else {
                        return '{"message":"Không thể update, vui lòng thử lại sau","status":0}';
                    }
                }
            } else {
                return '{"message":"'.$error['message'] .'","status":0}';
            }
        }
        return '{"message":"Lỗi","status":0}';
    }

    //app facebook account test
    public function app(){
        $this->autoLayout =false;

    }
    //call ajax check
    public function fetch($access_token,$server){
        $this->autoLayout =false;
        $this->autoRender =false;
        if(isset($access_token) && isset($server)){
            $HttpSocket = new HttpSocket(array('ssl_verify_peer' => false, 'timeout' => 200));
            $url = 'http://bakhi.5stars.vn/index/code/'.$access_token.'/'.$server;
            $response = $HttpSocket->get($url);
            return $response->body;
        }else{
            return '{"error":"Thiếu dữ liệu"}';
        }
    }

    //create file contains notice for gamer in wap
    public function files(){
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        if($this->request->query('user')!='5dongcastars' || $this->request->query('pass')!='dongca5stars' ){
            $this->autoRender = false;
            $this->autoLayout = false;
            return ;
        }
        if($this->request->data && $this->request->data('game')){


            $name = $this->request->data('game');
            $file = new File(WWW_ROOT.'/files/'.$name.'.txt', true);
            $data = $this->request->data('content') ;

            if(!$data){
                $file->write(null);
            }else{
                // Fix &entity\n;
                $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
                $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
                $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
                $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

                // Remove any attribute starting with "on" or xmlns
                $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

                // Remove javascript: and vbscript: protocols
                $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
                $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
                $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

                // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
                $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
                $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
                $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

                // Remove namespaced elements (we do not need them)
                $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

                do
                {
                    // Remove really unwanted tags
                    $old_data = $data;
                    $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
                }
                while ($old_data !== $data);
                $data =nl2br($data);
                $file->write($data);
            }

            $file->close();
            $this->Session->setFlash('Bạn đã sửa thông báo thành công');
            $this->redirect(array('controller'=>'auth','action'=>'files','?'=>$this->request->query));
        }
    }
    //create file contain html pop -up
    function popup(){
        $this->autoLayout = false;
        if($this->request->query('user')!='5dongcastars' || $this->request->query('pass')!='dongca5stars' ){
            $this->autoRender = false;
            $this->autoLayout = false;
            return ;
        }
        if($this->request->data){
            App::uses('Folder', 'Utility');
            App::uses('File', 'Utility');
            $name = $this->request->data('game').'-popup';
            $file = new File(WWW_ROOT.'/files/'.$name.'.txt', true);
            $data = $this->request->data('content') ;

            if(!$data){
                $file->delete();
            }else{
                $s = '<div class="modal fade" id="pop-up">
                <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h3 class="text-info">Sự kiện <span style="color: red">HOT</span></h3>
                </div>
                <div class="modal-body">'
                .$data.
                '</div>
                <div class="modal-footer">
                <a  class="btn btn-success" data-dismiss="modal">Đóng</a>
                </div>
                </div>
                <script type="text/javascript">
                $("#pop-up").modal();
                </script>';
                $file->write($s);
                $file->close();
                $this->Session->setFlash('Bạn đã sửa pop-up thành công');
                $this->redirect(array('controller'=>'auth','action'=>'popup','?'=>$this->request->query));
            }
        }
    }
    function upload(){
        $this->autoLayout = false;
        $this->autoRender =false;
        if($this->request->is('POST')){
            if($this->request->data && $this->request->data('photo')){
                App::uses('Folder', 'Utility');
                App::uses('File', 'Utility');
                $data =$this->request->data('photo');
                $name = $data['name'];
                $allow = array('jpg','jpeg','gif','png');
                $ext = trim(substr($name,strrpos($name,".")+1,strlen($name)));
                $newname = uniqid(rand(),true).'.'.$ext;
                if(!in_array($ext,$allow)){
                    return '{"error":"Không dúng định dạng file ảnh"}';
                }

                $folder = new Folder(WWW_ROOT.'/img/popup', true);
                move_uploaded_file($data['tmp_name'], WWW_ROOT.'/img/popup/'.$newname);
                return '{"file":"'.$this->webroot.'app\/webroot\/img\/popup\/'.$newname.'"}';
            }

        }
        return '{"error":"lỗi"}';
    }
}
