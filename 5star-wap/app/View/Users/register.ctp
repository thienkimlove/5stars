<?php

if (isset ( $users )) {
	$username = $users ['username'];
	$email = $users ['email'];
} else {
	$username = '';
	$email = '';
}
?>
<?php
echo $this->html->script ( 'lib/jquery.validate' );
echo $this->html->script ( 'lib/messages_vi' );
?>
<script type="text/javascript">
	$(window).load(function(){
		 $(".content").validate({
	            errorElement: "p", // Định dạng cho thẻ HTML hiện thông báo lỗi
	            rules: {
	                password: {
	                    equalTo: "#passwordc", // So sánh trường cpassword với trường có id là password
	    	                },
	    	           username:{
		    	           required:true,
	    	               rangelength: [4, 50],
		   	    	       remote: {
		   		 				url: "<?php echo $this->html->url(array('controller'=>'users','action'=>'check_username'));?>",
		   		 					type: "post"
		   		 					}
		   		    	        },
			   		    email:{
			   		    	required: true,
							email: true,
					    	 remote: {
					 			url: "<?php echo $this->html->url(array('controller'=>'users','action'=>'check_email'));?>",
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
<form class="content" method="post"
	action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'register')) ?>">
	<h2 class="heading">Đăng Ký</h2>
	 Chức năng này hiện tại đang hoàn thiện , vui lòng bạn vào game đăng ký!
	 <!-- 
<?php if(isset($error['dieukhoan'])):?>
	<p class="error"><?php echo $error['dieukhoan'];?> </p>
        	<?php endif;?>
	<p class="insert insert-user">
		<input name="username" id="username"  
			type="text" value="<?php echo $username;?>"
			placeholder="Tên tài khoản" />
			<?php if(isset($error['username'])):?>
	<p class="error"><?php echo $error['username'];?> </p>
        	<?php endif;?>
			</p>
	<p class="insert insert-pass">
		<input name="password" type="password" placeholder="Nhập mật khẩu"
			class="required" />
			<?php if(isset($error['password'])):?>
         	
	<p class="error"><?php echo $error['password'];?> </p>
	
	
       	 	<?php endif;?>
			</p>
	<p class="insert insert-pass">
		<input type="password" name="passwordc" id="passwordc"class="required" placeholder="Nhập lại mật khẩu" />
	</p>
	<p class="insert insert-email last">
		<input type="text" name="email"  id="email"	value='<?php echo $email;?>' placeholder="Email" /><span></span>
			<?php if(isset($error['email'])):?>
	<p class="error"><?php echo $error['email'];?> </p>
       		 <?php endif;?>
			</p>

	<p class="luuy">Lưu ý: Nếu điền sai email sẽ không hỗ trợ lấy lại mật
		khẩu.</p>
	<p class="luuy">
		<input type="checkbox" name="dieukhoan" value="yes" <?php if(isset($users['dieukhoan'])) echo 'checked="checked"' ?>> 
		<?php echo $this->html->link('Đồng ý các điều khoản khi tham gia',array('controller'=>'users','action'=>'term'),array('class'=>'dieukhoan','target'=>'_blank'));?>
			
			</p>
	<p class="btn-submit">
		<input type="submit" name="Xác nhận" value="Đăng Ký" id="submit" />
	</p>
	<p class="btn-reg btn-cancel">
		<input type="reset" name="Huỷ" value="Huỷ" />
	</p>
	<span id="a"></span>
	 -->
</form>
<!--End content-->