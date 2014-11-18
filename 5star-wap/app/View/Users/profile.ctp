<form class="content">
			<h2 class="heading">Xin chào ( <?php echo $this->Session->read('Auth.User.Login')?> )</h2>
			
			<p class="btn-info"><input type="button" name="Cập nhật thông tin tài khoản" value="Cập nhật thông tin tài khoản" onClick="window.location.href='<?php echo $this->html->url(array('controller'=>'users','action'=>'edit_info'));?>'"/></p>
			<p class="btn-info last"><input type="button" name="Thay đổi mật khẩu" value="Thay đổi mật khẩu" onClick="window.location.href='<?php echo $this->html->url(array('controller'=>'users','action'=>'edit_password'));?>'"/></p>
			
			<p class="btn-info"><input type="button" name="Nạp thẻ" value="Nạp thẻ"/></p>
			<p class="btn-info last"><input type="button" name="Xem lịch sử nạp thẻ" value="Xem lịch sử nạp thẻ"/></p>
			
			
			
			<p class="btn-submit"><input type="button" name="Đăng xuất" value="Đăng xuất" onClick="window.location.href='<?php echo $this->html->url(array('controller'=>'users','action'=>'logout'));?>'"/></p>
			<a href="<?php echo $this->html->url(array('controller'=>'users','action'=>'term'));?>" class="forgot-pass dieukhoansudung">Điều khoản sử dụng</a>
		</form><!--End content-->