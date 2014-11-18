<?php
echo $this->html->script ( 'lib/jquery.validate' );
echo $this->html->script ( 'lib/messages_vi' );
?>
<script type="text/javascript">
	$(window).load(function(){
		 $("#form").validate({
	            errorElement: "p", // Định dạng cho thẻ HTML hiện thông báo lỗi
		});
	});
</script>
<div class="right-content">        
	<form class="acount" id="form" name="userRegisterForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'lost')) ?>">
	
		<?php echo $this->Session->flash(); ?>
		<p class="insert-user">
		
		<label>Email: <span style="color:red">*</span></label>
		<input name="email" type="text" class="required email" id="email"  value="">
		</p>
		<p class="insert-user">
		
		<label>Mã xác nhận: <span style="color:red">*</span></label>
		<input name="captcha" type="text" class="required"  minlength="6" maxlength="6" value="">
		</p>
		<p class="insert-user">
		
		<label><span style="color:red"></span></label>
		<img id="captcha" src="<?php echo $this->Html->url(array('controller'=>'index','action'=>'captcha'));?>" alt="CAPTCHA Image">
		<a  href="#" onclick="document.getElementById('captcha').src = '<?php echo $this->Html->url(array('controller'=>'index','action'=>'captcha'));?>?' + Math.random(); return false"><?php echo $this->Html->image('ic_refresh.png',array('alt'=>'refresh'));?></a>
		</p>
		
         <p class="control"><input type="submit" class="btn selected" value="Xác nhận"/><input type="reset" class="btn" value="Huỷ"/></p>
	
</form>
</div><!--End right-content-->
