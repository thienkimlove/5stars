<?php 
	  echo $this->html->script('lib/jquery.validate');
	  echo $this->html->script('lib/messages_vi');
?>
<script type="text/javascript">
	$(window).load(function(){
		 $(".content").validate({
	            errorElement: "p", // Định dạng cho thẻ HTML hiện thông báo lỗi
			        });
	});
</script>
<form class="content" method="post" name="userLoginForm" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')) ?>">
			<h2 class="heading">Đăng Nhập</h2>
			<?php if(isset($error_login))echo '<p class="error">'.$error_login.'</p>';?>
			<p class="insert insert-user"><input type="text" class="required" placeholder="Tên tài khoản / Email" name="data[login]"/></p>
			<p class="insert insert-pass last"><input type="password" class="required" name="data[password]" placeholder="Nhập mật khẩu"/></p>
			<p class="btn-submit"><input type="submit" name="Xác nhận" value="Đăng nhập"/></p>
			<p class="btn-reg"><input type="button" name="Đăng ký" value="Đăng ký" onClick="window.location.href='<?php echo $this->html->url('/dang-ky.html')?>'"/></p>
			<?php echo $this->html->link('Quên mật khẩu',array('controller'=>'users','action'=>'lost'),array('class'=>'forgot-pass'));?>
</form><!--End content-->