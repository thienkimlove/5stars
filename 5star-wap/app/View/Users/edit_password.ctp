<?php 
	  echo $this->html->script('lib/jquery.validate');
	  echo $this->html->script('lib/messages_vi');
?>
<script type="text/javascript">
	$(window).load(function(){
		 $(".content").validate({
	            errorElement: "p", // Định dạng cho thẻ HTML hiện thông báo lỗi
	            rules: {
	                password: {
	                    equalTo: "#passwordc", // So sánh trường cpassword với trường có id là password
	    	                }
	            }
			        });
	});
</script>
<form class="content" method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit_password')) ?>">
			<h2 class="heading">Xin chào ( <?php echo $this->Session->read('Auth.User.Login');?> )</h2>
			
			<p class="insert-text"><input type="password" name="passwordo" class="required" <?php if(isset($this->data['passwordo'])) echo 'value="'. $this->data['passwordo'].'"' ?> placeholder="Mật khẩu cũ"/>
				<?php if(isset($error['passwordo'])):?>
                  <p class="error"><?php echo $error['passwordo'];?> </p>
                 <?php endif;?>
			</p>
			<p class="insert-text"><input type="password"  name="password" class="required" <?php if(isset($this->data['password'])) echo 'value="'. $this->data['password'].'"' ?> placeholder="Mật khẩu mới"/>
			  <?php if(isset($error['password'])):?>
                    	<p class="error"><?php echo $error['password'];?> </p>
                    	<?php endif;?>
			</p>
			<p class="insert-text last"><input type="password" name="passwordc" id="passwordc" class="required" <?php if(isset($this->data['passwordc'])) echo 'value="'. $this->data['passwordc'].'"' ?> placeholder="Nhập lại mật khẩu mới"/>
			 <?php if(isset($error['passwordc'])):?>
                    	<p class="error"><?php echo $error['passwordc'];?> </p>
                    	<?php endif;?>
			</p>
			
			
			<p class="btn-submit"><input type="submit" name="Xác nhận" value="Xác nhận"/></p>
		</form><!--End content-->