<script type="text/javascript">
var Config =  {};
Config.channelId = '';
Config.gameId = '';
Config.token = '';
Config.facebookAppId = <?php echo json_encode(Configure::read('fbId')) ?>;
Config.wap = <?php echo json_encode(Configure::read('wap')) ?>;
Config.apiMessage = <?php echo (!empty($params['message']))? json_encode($params['message']) : 'false' ?>;
</script>
<div class="control-group">
<?php if($this->Session->read('Auth.User.Login')):?>
<p>Xin chào: <span style="color: red"><?php echo $this->Session->read('Auth.User.Login');?> (<?php echo $this->Session->read('Auth.User.Id');?>)</span></p>
<?php endif;?>
<?php if ($wakeUpSyntax) : ?>
<a href="<?php echo $wakeUpSyntax ?>" class="btn btn-block btn-success">Quay lại Game</a>
<?php else : ?>
<a href="<?php echo $this->Html->url(array('action' => 'profile')) ?>" class="btn btn-block btn-success">Quay lại Game</a>
<?php endif; ?>
<a href="<?php echo $this->Html->url(array('action' => 'logout')) ?>" class="btn btn-block btn-primary">Đăng xuất</a>
</div>
<hr>
<div class="control-group">
<a class="btn btn-block btn-warning" id="btn-change-password">Thay Đổi Mật Khẩu</a>
</div>
<div ng-app="userLoginApp" ng-controller="userLoginCtrl">
<!--  box modal change password -->
	<div class="modal hide fade" id="change-password">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<h3 class="text-info">Thay Đổi Mật Khẩu</h3>
			</div>
			<form id="userChangePassword" name="userChangePassword" >
			<div class="modal-body">
			<div class="alert alert-block alert-error"  ng-show="showErrorChangePassword">
				<ul>
					<li ng-show="userChangePassword['password'].$error.required ">Vui lòng nhập mật khẩu cũ</li>
					<li ng-show="userChangePassword['passwordn'].$error.required ">Vui lòng nhập mật khẩu mới</li>
					<li ng-show="userChangePassword['passwordr'].$error.required ">Vui lòng nhập xác nhận mật khẩu mới</li>
					<li ng-show="userChangePassword['passwordn'].$error.minlength">Mật khẩu phải từ 5 đến 45 ký tự</li>
					<li ng-show="userChangePassword['passwordn'].$error.maxlength">Mật khẩu phải từ 5 đến 45 ký tự</li>
					<li ng-show="userChangePassword['passwordr'].$error.equal">Xác nhận mật khẩu không chính xác</li>
					
				</ul>
				{{message}}
			</div>
				<div class="control-group">
					<label class="control-label">Nhập mật khẩu cũ:</label>
					
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span>
							<input type="password" name="password" placeholder="Nhập mật khẩu cũ" ng-model="change.password" required/>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Nhập mật khẩu mới:</label>
					
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-star"></i></span>
							<input type="password" name="passwordn" placeholder="Nhập mật khẩu mới" ng-model="change.passwordn" required ng-minlength="5" ng-maxlength="45"/>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Nhập xác nhận mật khẩu mới:</label>
					
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-repeat"></i></span>
							<input type="password" name="passwordr" placeholder="Nhập xác nhận mật khẩu mới" ng-model="change.passwordr" required ui-validate-equals="change.passwordn"/>
						</div>
					</div>
				</div>
				
			</div>
			</form>
			<div class="modal-footer">
				<a class="btn btn-primary" ng-click="changePassword($event)" ng-disabled="change.checked" ng-bind="change.btnName">Xác Nhận</a>
					
			
			</div>
		</div>
		<!-- end box modal change password -->
		
</div>	
<script type="text/javascript">
<!--
$('#btn-change-password').click(function(){
	$('#change-password').modal();
});

//-->
</script>		