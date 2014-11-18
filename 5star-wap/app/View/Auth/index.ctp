<script type="text/javascript">
var Config =  {};
Config.channelId = <?php echo json_encode($params['channelId']); ?>;
Config.gameId = <?php echo json_encode($params['gameId']); ?>;
Config.token = <?php echo json_encode($params['token']); ?>;
Config.facebookAppId = <?php echo json_encode(Configure::read('fbId')) ?>;
Config.wap = <?php echo json_encode(Configure::read('wap')) ?>;
Config.apiMessage = <?php echo (!empty($params['message']))? json_encode($params['message']) : 'false' ?>;
</script>
<?php echo $this->element('facebook') ?>
    <?php echo $this->Html->image('giaothong.jpg') ?>
	<!-- content -->
	<div ng-app="userLoginApp" ng-controller="userLoginCtrl">
		<div class="alert alert-block alert-error" ng-show="apiMessage" ng-bind="apiMessage">
		</div>
		
		<!-- Start form login -->
		<form action="" class="form-horizontal" ng-show="login && !showConfirm" id="userLoginForm" name="userLoginForm"  method="post" novalidate >
			
			<!-- div show error login form -->
			<div class="alert alert-block alert-error" ng-show="showErrorLogin">
				<ul>                
					<li ng-show="userLoginForm['login'].$error.required">Xin vui lòng nhập email hoặc tên tài khoản</li>
					<li ng-show="userLoginForm['password'].$error.required">Xin vui lòng nhập mật khẩu</li>
				</ul>
			</div>
			
			<!-- form login input -->
			<div class="control-group">
				<label><b>Tài Khoản:</b></label>	
				<input type="text" id="username" placeholder="Tài Khoản" class="input-block-level" name="login"  ng-model="user.login" required autocorrect="off" autocapitalize="off" autocomplete="off"/>          
			</div>
				
			<div class="control-group">
				<label><b>Mật Khẩu:</b></label>
				<input type="password" id="password" placeholder="Mật Khẩu" class="input-block-level" name="password"  required ng-model="user.password" autocorrect="off" autocapitalize="off" autocomplete="off"/>
			</div>

			<div class="control-group">
				 <a href="#lost-pasword"  id="btn-lost" class="pull-right" style="line-height: 30px"> Quên mật khẩu </a> 
				<button type="submit" class="btn btn-info" ng-model="user.submit" name="Xác nhận" value="Xác nhận" ng-click="doLogin($event)">Xác Nhận</button>
			</div>
            
            <div class="control-group" ng-show="showTrialButton">                
                <button type="submit" class="btn btn-block btn-warning" name="Free Trial" value="Free Trial" ng-click="doTrial($event)">Free Trial</button>
            </div>
            
            <div class="control-group" ng-show="showCodinhTaiKhoanButton">                
                <button type="submit" class="btn btn-block btn-warning" name="Cố Định Tài Khoản" value="Cố Định Tài Khoản" ng-click="codingTK()">Cố Định Tài Khoản</button>
            </div>
            
				<input type="hidden" ng-model="user.formType" name="formType" value="login" />
				<input type="hidden" ng-model="user.gameId" name="gameId" value="<?php echo isset($params['gameId']) ? $params['gameId'] : ''; ?>" />
				<input type="hidden" ng-model="user.channelId" name="channelId" value="<?php echo isset($params['channelId']) ? $params['channelId'] :'' ;?>" />
				<input type="hidden" ng-model="user.token" name="token" value="<?php echo isset($params['token']) ? $params['token'] :''; ?>" />
		</form>
		<!-- End form login -->	
		<!-- Start form register -->	
		<form action="" class="form-horizontal" ng-show="!login && !showConfirm" id="userRegisterForm" name="userRegisterForm" novalidate method="post">
			<h3 class="text-info">Đăng ký 5Star ID</h3>
			<!-- div show register error -->
			<div class="alert alert-block alert-error"  ng-show="showErrorRegister">
				<ul>
					<li ng-show="userRegisterForm['username'].$error.required">Xin vui lòng nhập tên tài khoản</li>
					<li ng-show="userRegisterForm['username'].$error.pattern">Tên tài khoản không chứa ký tự đặc biệt</li>
					<li ng-show="userRegisterForm['password'].$error.required">Vui lòng nhập mật khẩu</li>
					<li ng-show="userRegisterForm['passwordc'].$error.equal">Xác nhận mật khẩu không chính xác</li>
					<li ng-show="userRegisterForm['password'].$error.minlength">Mật khẩu phải từ 5 đến 45 ký tự</li>
					<li ng-show="userRegisterForm['password'].$error.maxlength">Mật khẩu phải từ 5 đến 45 ký tự</li>
					<li ng-show="userRegisterForm['email'].$error.required || userRegisterForm['email'].$error.email">Vui lòng nhập đúng email</li>               
				</ul>
			</div>
			<!-- form register input -->		
			<div class="control-group">
				<label><b>Tên Tài Khoản:</b></label>
				<input type="text" placeholder="Tên Tài Khoản" class="input-block-level" name="username"  ng-model="user.username" ng-pattern="/^([a-zA-Z0-9 _-]+)$/" required/>	
			</div>
			
			<div class="control-group">
				<label><b>Nhập Mật Khẩu:</b></label>
				<input type="password"  placeholder="Nhập Mật Khẩu" class="input-block-level" name="password" required ng-model="user.password" ng-minlength="5" ng-maxlength="45" />
			</div>
			
			<div class="control-group">
				<label><b>Nhập lại Mật Khẩu:</b></label>
				<input type="password" placeholder="Nhập Lại Mật Khẩu" class="input-block-level" name="passwordc" required ng-model="user.passwordc" ui-validate-equals="user.password"/>
			</div>
			
			<div class="control-group">
				<label><b>Email:</b></label>
				<input placeholder="Email" class="input-block-level" name="email" type="email" ng-model="user.email"  />
			</div>
			
			<input type="hidden" ng-model="user.formType" name="formType" value="register" />
			<input type="hidden" ng-model="user.gameId" name="gameId" value="<?php echo isset($params['gameId']) ? $params['gameId'] : ''; ?>" />
			<input type="hidden" ng-model="user.channelId" name="channelId" value="<?php echo isset($params['channelId']) ? $params['channelId'] :'' ;?>" />
			<input type="hidden" ng-model="user.token" name="token" value="<?php echo isset($params['token']) ? $params['token'] :'' ;?>" />
			
			<p class="text-error">Lưu ý: Nếu điền sai email sẽ không hỗ trợ lấy lại mật khẩu.</p><br>
			
			<div class="control-group">	
				<button type="submit" class="btn btn-info" ng-model="user.submit" ng-click="doRegister($event)" value="Đăng Ký">Xác Nhận</button>
			</div>
			
		</form>
		<!-- End form register -->
		<div class="clearfix"></div>
		
		<div class="control-group" ng-show="!showConfirm">
			<button type="submit" class="btn btn-success btn-block" id="btn-login" ng-click="onSwitchButton();" ng-bind="btnName"> Đăng Ký</button>	
            <?php if (!empty($params['gameId']) && !in_array($params['gameId'], array('7', '8', '6'))) : ?>			
			<button type="submit" class="btn btn-primary btn-block"  id="fb-button" ng-show="showFacebookLogin" ng-click="facebook($event)" ng-bind="fb.name" ng-disabled="fb.checked"></button>
            <span style="color:red">Vui lòng Set DNS 8.8.8.8 cho kết nối Wifi của bạn để đăng nhập bằng Facebook</span>			 
            <?php endif; ?>			
		</div>
        
        <div ng-show="showConfirm">
           
               <div class="control-group">
                <label><b>Tài Khoản:</b></label>    
                <input type="text" placeholder="Tài Khoản" class="input-block-level" name="login"  ng-model="coding.login" autocorrect="off" autocapitalize="off" autocomplete="off"/>          
               </div>
                
                <div class="control-group">
                    <label><b>Mật Khẩu:</b></label>
                    <input type="password" placeholder="Mật Khẩu" class="input-block-level" name="password"  ng-model="coding.password" autocorrect="off" autocapitalize="off" autocomplete="off"/>
                </div>
            
                 <div class="control-group">
                    <label><b>Email:</b></label>
                    <input type="text" placeholder="Email" class="input-block-level" name="email"  ng-model="coding.email" autocorrect="off" autocapitalize="off" autocomplete="off"/>
                </div>

                <div class="control-group">
                    <button type="submit" class="btn btn-info" ng-model="codinh.submit" name="Xác nhận" value="Xác nhận" ng-click="doCoding($event)">Xác Nhận</button>
                </div>  
            
           
        </div>
        
        
<!-- Lost password section -->
	<div class="modal hide fade" id="lost-password">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<h3 class="text-info">Quên Mật Khẩu</h3>
			</div>
			<form id="userLostPassword" name="userLostPassword" >
			<div class="modal-body">
			<div class="alert alert-block alert-error"  ng-show="showErrorLostPassword">
				<ul>
					<li ng-show="userLostPassword['email'].$error.required || userLostPassword['email'].$error.email">Vui lòng nhập đúng email</li>               
					<li ng-show="userLostPassword['captcha'].$error.required">Vui lòng nhập mã xác nhận</li>
				
				</ul>
					{{message}}
			</div>
				<div class="control-group">
					<label class="control-label">Nhập Email:</label>
					
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-envelope"></i></span>
							<input type="email" name="email" placeholder="Email" ng-model="lost.email" required/>
						</div>
					
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Mã xác nhận:</label>
					
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-ok"></i></span>
							<input type="text" name="captcha" placeholder="Mã xác nhận" ng-model="lost.captcha" required/>
							<br/>
							<img id="captcha" src="<?php echo $this->Html->url(array('controller'=>'auth','action'=>'captcha'));?>" alt="CAPTCHA Image">
		<a  href="#" onclick="document.getElementById('captcha').src = '<?php echo $this->Html->url(array('controller'=>'auth','action'=>'captcha'));?>?' + Math.random(); return false" ><?php echo $this->Html->image('ic_refresh.png',array('alt'=>'refresh'));?></a>
						</div>
							
					</div>
				</div>
			</div>
			</form>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" ng-click="getLostPassword($event)" ng-disabled="lost.checked" ng-bind="lost.btnName">Xác Nhận</a>
			</div>
			
		</div>
		
	<!-- Lost password section -->

		
			<!-- pop up section -->
			<?php
			if($this->request->query('gameId') == 1 || $this->request->query('gameId') == 2 ){	
			if(is_file(APP.WEBROOT_DIR.DS.'files'.DS.'maphap-popup.txt')) {
					if(!isset($_COOKIE['popup']) || $_COOKIE['popup'] !='maphap'){
						$file = APP.WEBROOT_DIR.DS.'files'.DS.'maphap-popup.txt';
						$f = fopen($file,"r");
						while (!feof($f)){
							echo fgetc($f);
						}
						fclose($f);
						setcookie('popup','maphap',time()+10800);
					}		
				}
			}

			if($this->request->query('gameId') == 3 || $this->request->query('gameId') == 5 ){
				if(is_file(APP.WEBROOT_DIR.DS.'files'.DS.'bakhi-popup.txt')) {
					if(!isset($_COOKIE['popup']) || $_COOKIE['popup'] !='bakhi'){
						$file = APP.WEBROOT_DIR.DS.'files'.DS.'bakhi-popup.txt';
						$f = fopen($file,"r");
						while (!feof($f)){
							echo fgetc($f);
						}
						fclose($f);
						setcookie('popup','bakhi',time()+10800);
						}
				}
			}
	?>	

			<!-- pop-up section -->
		
		
</div><!-- End content -->
<script type="text/javascript">
<!--
$('#btn-lost').click(function(){
	$('#lost-password').modal();
});
//-->

</script>	