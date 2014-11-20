<script type="text/javascript">
	$(function(){
		InitMenuEffects();
	});
	function InitMenuEffects () {
		/* Sliding submenus */
		$('.sidebar .menu ul ul').hide();
		$('.sidebar .menu ul li.active ul').show();

		$('.sidebar .menu ul li').click(function () {
			submenu = $(this).find('ul');
			if (submenu.is(':visible'))
				submenu.slideUp(150);                    
			else
				submenu.slideDown(200);                                    
			return false;
		});

		/* Hover effect on links */
		$('.sidebar .menu li a').hover(
			function () { $(this).stop().animate({'paddingLeft': '18px'}, 200); },
			function () { $(this).stop().animate({'paddingLeft': '12px'}, 200); }
		)
	}
	var I18n = {};
	var Config = {};
	
	Config.currentUser = <?php echo isset($currentUser) ? json_encode($currentUser) : 'false' ?>;

</script>

<div class="clear" ng-app="boardApp" ng-controller="boardCtrl" ng-cloak>    
<div class="sidebar"> <!-- *** sidebar layout *** -->
	<div class="logo clear">
		<a href="<?php echo $this->Html->url(array('controller' => 'admin', 'action' => 'index')) ?>" title="View dashboard">
			<img src="<?php echo $this->Html->imageUrl('logo_earth.png') ?>" alt="" class="picture" />
			<span class="title">5Stars</span>
			<span class="text">CRM System</span>
		</a>
	</div>

	<div class="menu">
		<ul>            
			<li ng-show="permission == 'admin'" ng-class="{'active' : workingPart == 'channelList' || workingPart == 'addChannel'}"><a href="javascript:void(0)">Kênh</a>
				<ul>
					<li style="cursor: pointer;" ng-class="{'active' : workingPart == 'channelList'}"><a ng-click="workingPart = 'channelList'">Xem danh sách kênh</a></li> 

					<li style="cursor: pointer;" ng-class="{'active' : workingPart == 'addChannel'}"><a ng-click="c.prepareAddChannel()">Thêm kênh mới</a></li>                     
				</ul>
			</li>
			
			<li ng-show="permission == 'admin'" ng-class="{'active' : workingPart == 'gameList' || workingPart == 'addGame'}"><a href="javascript:void(0)">Game</a>
				<ul>
					<li style="cursor: pointer;" ng-class="{'active' : workingPart == 'gameList'}"><a ng-click="workingPart = 'gameList'">Xem danh sách game</a></li> 

					<li style="cursor: pointer;" ng-class="{'active' : workingPart == 'addGame'}"><a ng-click="g.prepareAddGame()">Thêm game mới</a></li>                     
				</ul>
			</li>
			

			<li ng-show="permission != 'user'" ng-class="{'active' : workingPart == 'paymentList'}"><a href="javascript:void(0)">Thanh toán</a>
				<ul>
					<li style="cursor: pointer;" ng-class="{'active' : workingPart == 'paymentList'}"><a ng-click="workingPart = 'paymentList'">Lịch sử thanh toán</a></li> 

				</ul>
			</li>
			
			<li  ng-class="{'active' : workingPart == 'userList'}"><a href="javascript:void(0)">Thành viên</a>
				<ul>
					<li style="cursor: pointer;" ng-class="{'active' : workingPart == 'userList'}"><a ng-click="workingPart = 'userList'">Danh sách thành viên</a></li> 

				</ul>
			</li>
			
			<li ng-show="permission != 'user'" ng-class="{'active' : workingPart == 'chart'}"><a href="javascript:void(0)">Biểu mẫu thống kê</a>
				<ul>
					<li style="cursor: pointer;" ng-class="{'active' : workingPart == 'chart'}"><a ng-click="workingPart = 'chart'">Lịch sử đăng nhập</a></li> 

				</ul>
			</li>

		</ul>
	</div>
</div>        
<div class="main"> <!-- *** mainpage layout *** -->
	<div class="main-wrap">
		<div class="header clear">
			<ul class="links clear">
				<li><strong>Xin chào, <?php echo $currentUser['fullname'] ?> :</strong></li>            
				<li><a href="http://wap.5stars.vn"><img src="<?php echo $this->Html->imageUrl('ico_view_24.png') ?>" alt="" class="icon" /> <span class="text">Xem WAPsite</span></a></li>
				<li><a href="<?php echo $this->Html->url(array('action' => 'login')) ?>"><img src="<?php echo $this->Html->imageUrl('ico_logout_24.png') ?>" alt="" class="icon" /> <span class="text">Logout</span></a></li>
			</ul>
		</div>

		<div class="page clear">
			<h1>Chào mừng đến hệ thống CRM của 5stars.vn</h1>
			<p>Các tác vụ cần thiết</p>

			<div class="main-icons clear">
				<ul class="clear">
					<li style="cursor: pointer;" ng-show="permission == 'admin'"><a ng-click="c.prepareAddChannel()"><img src="<?php echo $this->Html->imageUrl('ico_folder_64.png') ?>" class="icon" alt="" /><span class="text">Tạo Kênh mới</span></a></li>                    
					
					<li style="cursor: pointer;" ng-show="permission == 'admin'"><a ng-click="g.prepareAddGame()"><img src="<?php echo $this->Html->imageUrl('ico_page_64.png') ?>" class="icon" alt="" /><span class="text">Tạo Game mới</span></a></li>
                    
                    
					<li style="cursor: pointer;" ng-show="permission != 'user'"><a ng-click="workingPart = 'paymentList'"><img src="<?php echo $this->Html->imageUrl('ico_picture_64.png') ?>" class="icon" alt="" /><span class="text">Lịch sử nạp tiền</span></a></li>                        
					<li style="cursor: pointer;"><a ng-click="workingPart = 'userList'"><img src="<?php echo $this->Html->imageUrl('ico_users_64.png') ?>" class="icon" alt="" /><span class="text">Danh sách thành viên</span></a></li>
					<li style="cursor: pointer;"><a ng-click="workingPart = 'chart'"><img src="<?php echo $this->Html->imageUrl('ico_chat_64.png') ?>" class="icon" alt="" /><span class="text">Thống kê</span></a></li>
				</ul>
			</div>

			<!-- MODAL WINDOW -->

			<div class="notification" style="display:none" id="message">                
				<span class="icon"></span>
				<p id="msgcontent"></p>
			</div>

			<?php echo $this->element('add_channel') ?>

			<?php echo $this->element('channel') ?>
			
			
			<?php echo $this->element('add_game') ?>

			<?php echo $this->element('game') ?>
			

			<?php echo $this->element('payment'); ?>
			
			<?php echo $this->element('user') ?>
			
			
			<?php echo $this->element('chart') ?>


			<div class="footer clear">
				<span class="copy"><strong>© 2013 Copyright by 5Stars.vn</strong></span>
			</div>
		</div>
	</div>
</div>



