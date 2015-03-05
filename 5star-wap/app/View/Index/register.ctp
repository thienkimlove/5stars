<?php if(isset($users)){
	$username = $users['username'];
	$email  = $users['email'];
}else{
	$username = '';
	$email  = '';
}?>
<?php
echo $this->html->script ( 'lib/jquery.validate' );
echo $this->html->script ( 'lib/messages_vi' );
?>
<script type="text/javascript">
	$(window).load(function(){
		 $("#form").validate({
	            errorElement: "p", // Định dạng cho thẻ HTML hiện thông báo lỗi
	            rules: {
	                password: {
	                    equalTo: "#passwordc", // So sánh trường cpassword với trường có id là password
	    	                },
	    	         username:{
	    	        	 remote: {
		 						url: "<?php echo $this->html->url(array('controller'=>'index','action'=>'check_username'));?>",
		 						type: "post"
		 					}
		    	         },
		    	      email:{
		    	    	  remote: {
		 						url: "<?php echo $this->html->url(array('controller'=>'index','action'=>'check_email'));?>",
		 						type: "post"
		 					}
			    	      }   
	             		},
	             		messages:{
	             			username:{
	             				remote: 'Tài khoản đã tồn tại, vui lòng nhập tên khác.'
		             			},
		             		email:{
		             			remote: 'Email đã tồn tại, vui lòng nhập email khác.'
			             		},
		             		}
			        });
		
		
			
	});
</script>
<div class="right-content">
			<div class="heading">
                    	<p><?php echo $this->Html->link(__('Trang Chủ'),array('controller'=>'index','action'=>'index')) ?> &gt;</p>
                        <p class="last-beadcrumb"> Đăng ký</p>
                    </div>
	<form class="acount" id="form" name="userRegisterForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'index', 'action' => 'register')) ?>">
	      Chức năng này hiện tại đang hoàn thiện , vui lòng bạn vào game đăng ký!
		<?php echo $this->Session->flash(); ?>
		<?php if(isset($error['dieukhoan'])):?>
         <p class="error"><?php echo $error['dieukhoan'];?> </p>
        <?php endif;?>
		<!-- <p class="insert-user">
		<label>Tài khoản: <span style="color:red">*</span></label>
		<input name="username" type="text" class="required" id="username" minlength="4" maxlength="50" value="<?php echo $username;?>">
		<?php if(isset($error['username'])):?>
         <p class="error"><?php echo $error['username'];?> </p>
        <?php endif;?>
		</p>
		<p>
		<label>Mật khẩu: <span style="color:red">*</span></label>
		 <input name="password" type="password" class="required">
		 <?php if(isset($error['password'])):?>
         <p class="error"><?php echo $error['password'];?> </p>
        <?php endif;?>
		 </p>
		 <p>
		 <label>Xác nhận mật khẩu: <span style="color:red">*</span></label>
		 <input name="passwordc" type="password" id="passwordc" class="required">
		 </p>
		 
		 <p class="insert-email">
		<label>Email: <span style="color:red">*</span></label>
		<input name="email" type='text' class="required email" id="email" value='<?php echo $email;?>'>
		 <?php if(isset($error['email'])):?>
         <p class="error"><?php echo $error['email'];?> </p>
        <?php endif;?>
		</p>	
		 <p class="luuy">Lưu ý: Nếu điền sai email sẽ không hỗ trợ lấy lại mật khẩu.</p>
         <p class="checkbox"><input type="checkbox" name="dieukhoan" value="yes" <?php if(isset($users['dieukhoan'])) echo 'checked="checked"' ?>/><?php echo $this->html->link(__('Đồng ý các điều khoản khi tham gia StarID'),array('controller'=>'index','action'=>'term'),array('target'=>'_blank'))?><a href="#"></a></p>
         <p class="control"><input type="submit" class="btn selected" value="Xác nhận"/><input type="reset" class="btn" value="Huỷ"/></p>
	 -->
</form>
</div><!--End right-content-->
