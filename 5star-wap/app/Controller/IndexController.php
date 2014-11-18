<?php
App::uses ( 'AppController', 'Controller' );

App::uses ( 'Sanitize', 'Utility' );

/**
 *
 *
 * Users Controller
 *
 *
 *
 * @property User $User
 *
 */
class IndexController extends AppController {
	public $uses = 'Gift';
	public $layout = 'index';
	public $helpers = array (
			
			'Html' 
	)
	;
	public $components = array (
			
			'Mobile',
			
			'Captcha' 
	)
	;
	function beforeFilter() {
		if ($this->Mobile->isMobile () || $this->Mobile->isTablet ()) {
			
			$this->redirect ( array (
					
					'controller' => 'users' 
			)
			 );
		}
	}
	public function index() {
		if ($this->Session->read ( 'Auth.User.Id' )) {
			
			$this->redirect ( array (
					
					'controller' => 'index',
					
					'action' => 'profile' 
			)
			 );
		} else {
			
			$this->redirect ( array (
					
					'controller' => 'index',
					
					'action' => 'login' 
			)
			 );
		}
	}
	public function profile() {
		if (! $this->Session->check ( 'Auth.User.Id' )) {
			$this->redirect ( array (
					'controller' => 'index',
					'action' => 'login' 
			) );
		}
	}
	public function logout() {
		$this->Session->destroy ();
		$this->redirect ( array (
				'controller' => 'index',
				'action' => 'login' 
		) );
	}
	public function login() {
		if ($this->Session->read ( 'Auth.User.Id' )) {
			
			$this->redirect ( array (
					
					'controller' => 'index',
					
					'action' => 'profile' 
			)
			 );
		}
		
		if ($this->request->is ( 'post' )) {
			
			$this->Session->write ( 'Auth.User.Login', $this->request->data ['login'] );
			
			$this->Session->write ( 'Auth.User.Password', $this->request->data ['password'] );
			
			try {
				
				$user = $this->GameApi->resource ( 'User' )->request ( '/auth' );
			} catch ( Exception $e ) {
				
				$this->Session->delete ( 'Auth' );
				
				$this->Session->setFlash ( $user ['name'] );
				
				return;
			}
			
			if (! empty ( $user ['user'] )) {
				
				if ($user ['user'] ['User'] ['status'] != 'active') {
					
					$this->Session->delete ( 'Auth' );
					
					$this->Session->setFlash ( 'Tai khoản của bạn đã bị khóa' );
					
					return;
				}
				
				$this->Session->write ( 'Auth.User.Id', $user ['user'] ['User'] ['id'] );
				
				// using for authentication.
				
				$this->Session->write ( 'Auth.User.RawPassword', $this->request->data ['password'] );
				
				$this->redirect ( array (
						
						'controller' => 'index',
						
						'action' => 'profile' 
				)
				 );
			} else {
				
				$this->Session->delete ( 'Auth' );
				
				$this->set ( 'error_login', 'Tên Hoặc Mật Khẩu Không Chính Xác' );
			}
		}
	}
	public function register() {
		
		/*
		 * if ($this->Session->read ( 'Auth.User.Id' )) { $this->redirect ( array ( 'controller' => 'index', 'action' => 'profile' ) ); } if ($this->request->is ( 'post' )) { $error = null; if ($this->request->data ['password'] != $this->request->data ['passwordc']) { $error ['password'] = 'Mật khẩu và xác nhận mật khẩu không trùng nhau'; } if (empty ( $this->request->data ['username'] )) { $error ['username'] = ' Không được để trống Tên Tài Khoản'; } if (strlen ( $this->request->data ['username'] ) > 50) { $error ['username'] = 'Độ dài tên tài khoản quá dài '; } if (empty ( $this->request->data ['password'] )) { $error ['password'] = ' Không được để trống Mật Khẩu'; } if (empty ( $this->request->data ['email'] )) { $error ['email'] = ' Không được để trống email'; } if (! filter_var ( $this->request->data ['email'], FILTER_VALIDATE_EMAIL )) { $error ['email'] = 'Email không đúng'; } if (! isset ( $this->request->data ['dieukhoan'] )) { $error ['dieukhoan'] = 'Bạn chưa đồng ý với các điều khoản của chúng tôi'; } if (! empty ( $error )) { $this->set ( 'users', $this->request->data ); $this->set ( 'error', $error ); } else { unset ( $this->request->data ['dieukhoan'] ); try { $user = $this->GameApi->resource ( 'User' )->add ( $this->request->data ); } catch ( Exception $e ) { $this->Session->setFlash ( $user ['name'] ); return; } if (isset ( $user ['user'] )) { $this->Session->setFlash ( __ ( 'Đăng ký thành công mới bạn đăng nhập vào hệ thống' ) ); $this->redirect ( array ( 'controller' => 'index', 'action' => 'index' ) ); } else { $this->set ( 'users', $this->request->data ); if (isset ( $user ['name'] )) { $this->Session->setFlash ( $user ['name'] ); } else { $this->Session->setFlash ( __ ( 'Không thể đăng nhập ,xin hãy thử lại ' ) ); } } } }/* } * * public function payment() { /* $this->autoRender = false; if (!$this->Session->check('Auth.User.Id')) { die(json_encode(array('status' => false, 'message' => 'Please login first before process payment!'))); } else { $objData = json_decode(file_get_contents("php://input"),true); //$this->log($objData); if (isset($objData[0]['coin']) && $objData[0]['coin']) { $result =	$this->GameApi->resource('User')->request('/payment/',array('method' => 'POST','data' => array('coin' => $objData[0]['coin'],'currency' => 'Wcoin'))); if (isset($result['user'])) { die(json_encode(array('status' => true,'message' => $result['user']['User']['coin']))); } else { die(json_encode(array('status' => false, 'message' => $result['name']))); } } else { die(json_encode(array('status' => false, 'message' => 'Invalid coin number'))); } }
		 */
	}
	public function partner() {
		
		/*
		 * $networks = $this->GameApi->resource('Network')->query(array('type' => 'partner')); $this->set('networks',$networks['networks']);
		 */
	}
	public function edit_info() {
		if (! $this->Session->check ( 'Auth.User.Id' )) {
			
			$this->redirect ( array (
					
					'controller' => 'index',
					
					'action' => 'profile' 
			)
			 );
		}
		
		if ($this->request->is ( 'post' )) {
			
			$error = null;
			
			if (empty ( $this->request->data ['fullname'] )) {
				
				$error ['fullname'] = 'Không được để trống Họ tên';
			}
			
			if (strlen ( $this->request->data ['fullname'] ) > 50) {
				
				$error ['fullname'] = 'Độ dài tên vượt quá độ dài cho phép';
			}
			
			if (empty ( $this->request->data ['day'] ) || empty ( $this->request->data ['month'] ) || empty ( $this->request->data ['year'] )) {
				
				$error ['birth'] = 'Bạn Chưa chọn Ngày sinh';
			}
			
			if (empty ( $this->request->data ['gender'] )) {
				
				$error ['gender'] = 'Bạn chưa chọn giới tính';
			}
			
			if (empty ( $this->request->data ['mobile'] )) {
				
				$error ['mobile'] = 'Không được để trống Số Điện Thoại';
			}
			
			if (! preg_match ( '#^0[0-9]{6,10}$#', $this->request->data ['mobile'] )) {
				
				$error ['mobile'] = 'Số điện thoại không đúng ';
			}
			
			if (! empty ( $error )) {
				
				$this->set ( 'error', $error );
			} else {
				
				$this->request->data = Sanitize::clean ( $this->request->data, array (
						
						'escape' => false,
						
						'remove_html' => true,
						
						'encode' => 'utf-8' 
				)
				 );
				
				$data ['id'] = $this->Session->read ( 'Auth.User.Id' );
				
				$data ['password'] = $this->Session->read ( 'Auth.User.RawPassword' );
				
				$data ['fullname'] = $this->request->data ['fullname'];
				
				$data ['information'] = '{ "birth":"' . $this->request->data ['day'] . '-' . $this->request->data ['month'] . '-' . $this->request->data ['year'] . '", "gender": "' . $this->request->data ['gender'] . '", "mobile": "' . $this->request->data ['mobile'] . '"}';
				
				try {
					
					$user = $this->GameApi->resource ( 'User' )->request ( '/edit/' . $data ['id'], array (
							
							'data' => $data 
					)
					 );
				} catch ( Exception $e ) {
					
					$this->Session->setFlash ( $user ['name'] );
					
					return;
				}
				
				if (isset ( $user ['user'] )) {
					
					$this->Session->setFlash ( 'Cập Nhật Thành Công' );
					
					$this->redirect ( array (
							
							'controller' => 'index',
							
							'action' => 'profile' 
					)
					 );
				} else {
					
					$this->set ( 'users', $this->request->data );
					
					if (isset ( $user ['error'] )) {
						
						$this->Session->setFlash ( $user ['error'] ['message'] );
					} else {
						
						$this->Session->setFlash ( __ ( 'Can not update . Please, try again.' ) );
					}
				}
			}
		}
		
		if (empty ( $this->data )) {
			
			$user = $this->GameApi->resource ( 'User' )->request ( '/auth' );
			
			$data = json_decode ( $user ['user'] ['User'] ['information'], true );
			
			$birth = split ( '-', $data ['birth'] );
			
			$user ['user'] ['User'] ['day'] = $birth [0];
			
			if (isset ( $birth [1] )) {
				
				$user ['user'] ['User'] ['month'] = $birth [1];
			} else {
				
				$user ['user'] ['User'] ['month'] = '';
			}
			
			if (isset ( $birth [2] )) {
				
				$user ['user'] ['User'] ['year'] = $birth [2];
			} else {
				
				$user ['user'] ['User'] ['year'] = '';
			}
			
			$user ['user'] ['User'] ['gender'] = $data ['gender'];
			
			$user ['user'] ['User'] ['mobile'] = $data ['mobile'];
			
			$this->data = $user ['user'] ['User'];
		}
	}
	public function edit_password() {
		if (! $this->Session->read ( 'Auth.User.Id' )) {
			
			$this->redirect ( array (
					
					'controller' => 'index',
					
					'action' => 'profile' 
			)
			 );
		}
		
		if ($this->request->is ( 'post' )) {
			
			$error = null;
			
			if (empty ( $this->request->data ['passwordo'] )) {
				
				$error ['passwordo'] = 'Không được Để Trống';
			}
			
			if (empty ( $this->request->data ['password'] )) {
				
				$error ['password'] = 'Không được Để Trống';
			}
			
			if (empty ( $this->request->data ['passwordc'] )) {
				
				$error ['passwordc'] = 'Không được Để Trống';
			}
			
			if ($this->request->data ['passwordo'] != $this->Session->read ( 'Auth.User.RawPassword' )) {
				
				$error ['passwordo'] = 'Mật khẩu cũ không đúng';
			}
			
			if ($this->request->data ['password'] != $this->request->data ['passwordc']) {
				
				$error ['password'] = 'Mật khẩu mới và xác nhận mật khẩu không trùng nhau';
			}
			
			if (empty ( $error )) {
				
				$data ['id'] = $this->Session->read ( 'Auth.User.Id' );
				
				$data ['password'] = $this->Session->read ( 'Auth.User.RawPassword' );
				
				$data ['new_password'] = $this->request->data ['password'];
				
				try {
					
					$user = $this->GameApi->resource ( 'User' )->request ( '/edit/' . $data ['id'], array (
							
							'data' => $data 
					)
					 );
				} catch ( Exception $e ) {
					
					$this->Session->setFlash ( $user ['name'] );
					
					return;
				}
				
				if (isset ( $user ['user'] )) {
					
					$this->Session->write ( 'Auth.User.RawPassword', $data ['new_password'] );
					
					$this->Session->setFlash ( 'Cập Nhật Thành Công' );
					
					$this->redirect ( array (
							
							'controller' => 'index',
							
							'action' => 'profile' 
					)
					 );
				} else {
					
					$this->set ( 'users', $this->request->data );
					
					if (isset ( $user ['error'] )) {
						
						$this->Session->setFlash ( $user ['error'] ['message'] );
					} else {
						
						$this->Session->setFlash ( __ ( 'Can not update . Please, try again.' ) );
					}
				}
			} else {
				
				$this->data = $this->request->data;
				
				$this->set ( 'error', $error );
			}
		}
	}
	public function lost() {
		if ($this->Session->read ( 'Auth.User.Id' )) {
			
			$this->redirect ( array (
					
					'controller' => 'index',
					
					'action' => 'profile' 
			)
			 );
		}
		
		if ($this->request->is ( 'post' )) {
			
			if ($this->Captcha->check ( $this->request->data ['captcha'] ) == false) {
				
				$this->Session->setFlash ( 'Sai mã xác nhận' );
			} else {
				
				$status = $this->GameApi->resource ( 'User' )->request ( '/requestPassword', array (
						
						'data' => array (
								
								'email' => $this->request->data ['email'] 
						)
						 
				)
				 );
				
				if (isset ( $status ['status'] )) {
					
					$this->Session->setFlash ( 'Mật khẩu đã được đổi , xin mời bạn vào mail để lấy mật khẩu mới' );
					
					$this->redirect ( array (
							
							'controller' => 'index',
							
							'action' => 'login' 
					)
					 );
				} else {
					
					$this->Session->setFlash ( $status ['name'] );
				}
			}
		}
	}
	public function captcha() {
		$this->Captcha->text_color = new Securimage_Color ( '#' . substr ( md5 ( rand ( 100000, 999999 ) ), 0, 6 ) );
		
		$this->Captcha->num_lines = 0;
		
		$this->Captcha->perturbation = 0;
		
		$this->Captcha->show ();
	}
	public function faqs() {
	}
	public function term() {
	}
	public function check_username() {
		if ($this->request->is ( 'post' )) {
			
			$user = $this->GameApi->resource ( 'User' )->request ( '/CheckUsername', array (
					
					'data' => array (
							
							'username' => $this->request->data ['username'] 
					)
					 
			)
			 );
			
			if (isset ( $user ['user'] )) {
				
				echo 'false';
			} else {
				
				echo 'true';
			}
		}
		
		$this->autoRender = false;
	}
	public function check_email() {
		if ($this->request->is ( 'post' )) {
			
			$user = $this->GameApi->resource ( 'User' )->request ( '/CheckEmail', array (
					
					'data' => array (
							
							'email' => $this->request->data ['email'] 
					)
					 
			)
			 );
			
			if (isset ( $user ['user'] )) {
				
				echo 'false';
			} else {
				
				echo 'true';
			}
		}
		
		$this->autoRender = false;
	}
	
	/*
	 * public function gen_code(){ $this->autoRender = false; $this->autoLayout = false; $name = 'hung@'; $key = '5Stars'; $pk = strtolower($this->Name.'/'.$this->IP); for($i=1;$i<20001;$i++){ list($usec, $sec) = explode(" ",microtime()); $sec = $sec.substr($usec, 2, 3); $tmp = rand(0,1)?'-':''; $tmp = $tmp.rand(1000, 9999).rand(1000, 9999).rand(1000, 9999).rand(100, 999).rand(100, 999); $beforeEncrypt = $pk.':'.$sec.':'.$tmp; $afterEncrypt = md5($beforeEncrypt); $code = substr(strtolower($afterEncrypt),6,8); $this->Gift->create(); $data = array('code'=>$code); if($this->Gift->save($data)){ echo $i.' - ' .$code . '<br>'; } } }
	 */
	public function gift() {
		if ($this->request->is ( 'POST' )) {
			
			$user_id = $this->request->data ['user_id'];
			
			$code = $this->request->data ['code'];
			
			$re = $this->Gift->findByUserId ( $user_id, array (
					
					'user_id' 
			)
			 );
			
			if (empty ( $re )) {
				
				$rs = $this->Gift->find ( 'first', array (
						
						'conditions' => array (
								
								'code' => $code,
								
								'status' => 'inactive' 
						)
						 
				)
				 );
				
				if (! empty ( $rs )) {
					
					echo '<br>';
					
					print_R ( $rs );
				} else {
					
					$this->Session->setFlash ( 'Gift code không đúng !!!!' );
				}
			} else {
				
				$this->Session->setFlash ( 'Bạn Đã nạp Gift Code rồi !!!!' );
			}
		}
	}
}
