
<div class="right-content">
<?php echo $this->Session->flash(); ?>
					<h2 class="heading">Xin chào, <?php echo $this->Session->read('Auth.User.Login')?> <span style="float: right"><?php echo $this->Html->link('Đăng Xuất',array('controller'=>'index', 'action' => 'logout')) ?></span></h2>
					
					<ul class="list-new">
						<li><?php echo $this->Html->link(__('Thay Đổi Thông Tin Tài Khoản'),array('controller'=>'index','action'=>'edit_info')) ?></li>
						<li><?php echo $this->Html->link(__('Thay Đổi Mật Khẩu'),array('controller'=>'index','action'=>'edit_password')) ?></li>
					</ul>
					<ul class="list-new">
						<li><a href="#" class="hot-new">Nạp Thẻ </a></li>
						<li><a href="#" class="hot-new">Lịch Sử Nạp Thẻ</a></li>
					</ul>
				</div><!--End right-content-->