<?php
echo $this->html->script ( 'lib/jquery.validate' );
echo $this->html->script ( 'lib/messages_vi' );
?>
<script type="text/javascript">
	$(window).load(function(){
		 $(".content").validate({
	            errorElement: "p", // Định dạng cho thẻ HTML hiện thông báo lỗi
		 });    
	}); 
</script>
<form class="content" method="post"
	action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'lost')) ?>">
	<h2 class="heading">Quên mật khẩu</h2>
	<p class="insert insert-user">
		<input name="email" type="text" class="required email" id="email"  value="" placeholder="Email" >
			</p>
	<p class="insert insert-email">
		<input type="text" name="captcha"  class="required"	value='' placeholder="Mã xác nhận" /><span></span>
	</p>
	<p class="insert insert-email">
			<img id="captcha" src="<?php echo $this->Html->url(array('controller'=>'users','action'=>'captcha'));?>" alt="CAPTCHA Image">
		<a  href="#" onclick="document.getElementById('captcha').src = '<?php echo $this->Html->url(array('controller'=>'users','action'=>'captcha'));?>?' + Math.random(); return false"><?php echo $this->Html->image('ic_refresh.png',array('alt'=>'refresh'));?></a>
	</p>
	<p class="btn-submit">
		<input type="submit" name="Xác nhận" value="Xác Nhận" id="submit" />
	</p>
	<p class="btn-reg btn-cancel">
		<input type="reset" name="Huỷ" value="Huỷ" />
	</p>
	<span id="a"></span>
</form>
<!--End content-->