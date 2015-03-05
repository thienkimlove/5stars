<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name=”robots” content=”noindex, follow” />
<meta name="keywords" content="5stars,5 stars,3d, online, game, di động, mobile, smarphone,di dong" />
<meta name="description" content=" http://wap.5stars.vn/ - 5Stars Mobile Game Portal" >
	<title>5Stars Mobile Game</title>
	<?php echo $this->html->css('style');?>
	<?php echo $this->Html->script('lib/jquery-1.9.1.min');;?>
	<script type="text/javascript">
		$(document).ready(function() {
            $('.btn-close').click(function() {
            	$('.xacminh-taikhoan').hide(500);
        	});
        });
	</script>
	<script type="text/javascript">

var fb_param = {};

fb_param.pixel_id = '6009350311851';

fb_param.value = '0.00';

fb_param.currency = 'USD';

(function(){

  var fpw = document.createElement('script');

  fpw.async = true;

  fpw.src = '//connect.facebook.net/en_US/fp.js';

  var ref = document.getElementsByTagName('script')[0];

  ref.parentNode.insertBefore(fpw, ref);

})();

</script>

<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6009350311851&amp;value=0&amp;currency=USD" /></noscript>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<div class="container_12">
				<a href="<?php echo $this->html->url(array('controller'=>'index','action'=>'index'));?>" class="logo"><?php echo $this->Html->image('logo.png',array('alt'=>'5stars'));?></a>
				<div class="banner">
					<a href="http://maphap.5stars.vn/"><?php echo $this->Html->image('banner.png',array('alt'=>'5stars'));?></a>
					
				</div>
			</div>
		</div><!--End Header-->
		
		<div id="navigation">
			<div class="container_12">
				<form class="ctn_search">
					<input type="text" class="search" placeholder="Nhập từ khoá..."/>
					<input type="submit" class="submit-search"/>
				</form>
				
				<ul class="main-nav">
					<li><?php echo $this->html->link('Trang chủ',array('controller'=>'index','action'=>'index'));?></li>
					<li><a href="#" class="">Nạp thẻ</a></li>
					<li><?php echo $this->html->link('Hỏi đáp',array('controller'=>'index','action'=>'faqs'));?></li>
					<li><a href="#" class="">Hướng dẫn</a></li>
				</ul>

				<p class="sub-contact"><a href="tel:+84934439200">Hotline: 0934 439 200</a></p>
				
				<!-- <div class="xacminh-taikhoan">
					<a href="#" class="btn-acount"><img src="images/blank.gif" alt="5stars"/></a>
					<button class="btn-close"><img src="images/blank.gif" alt="5stars"/></button>
				</div> -->
			</div>
		</div><!--End Navigation-->
		
		<div id="content">		
			<div class="container_12">
				<div class="left-content">	
				<?php if (!$this->Session->read('Auth.User.Id')) {?>			
					<form class="ctn_login" method="post" name="userLoginForm" action="<?php echo $this->Html->url(array('controller' => 'index', 'action' => 'login')) ?>">
						<p class="heading">Đăng nhập</p>
						<?php if(isset($error_login))echo '<span class="err">'.$error_login.'</span>';?>
						<input type="text" class="login-user" placeholder="Tên tài khoản / Email" name="data[login]"/>
						<input type="password" class="login-pass" placeholder="************" name="data[password]" />
						<input type="submit" class="btn" value="Đăng nhập"/>
						<input type="button" class="btn selected" value="Đăng ký" onClick="window.location.href='<?php echo $this->html->url(array('controller'=>'index','action'=>'register'))?>'"/>
						<p class="lost_pass"><?php echo $this->html->link('Quên Mật Khẩu',array('controller'=>'index','action'=>'lost'));?></p>
					</form>
					<?php }else{?>
					<div class="ctn_info">
						<p class="heading">Thông tin tài khoản</p>
						<?php echo $this->Html->link(__('Cập nhật thông tin tài khoản'),array('controller'=>'index','action'=>'edit_info'),array('class'=>"update-info")) ?>
						<?php echo $this->Html->link(__('Thay đổi mật khẩu'),array('controller'=>'index','action'=>'edit_password'),array('class'=>'change-pass')) ?>
						
					</div>
					
					<div class="ctn_napthe">
						<p class="heading">Nạp thẻ</p>
						<a href="#" class="napthe">Nạp thẻ</a>
						<a href="#" class="lichsu">Xem lịch sử nạp thẻ</a>
					</div>
					
					<?php echo $this->Html->link('Đăng xuất',array('controller'=>'index', 'action' => 'logout'),array('class'=>'logout')); ?>
					<?php }?>
				</div><!--End left-content-->
				
				<?php echo $this->fetch('content');?>
			</div>
		</div><!--End Content-->
		
		<div id="footer">
        	<div class="container_12">
                <p>Copyright © 2013 - Công ty TNHH Trò chơi Di động 5 Sao<br/>
                Điện thoại: (84)-4-627.516.94<br/>
                Địa chỉ: Tầng 16, Phòng 1605, Khu B Toà nhà M3-M4 Nguyễn Chí Thanh, Đống Đa, Hà Nội</p>
            </div>
		</div><!--End footer-->
	</div>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-42799100-1', '5stars.vn');
  ga('send', 'pageview');
</script>
</body>
</html>
