<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name=”robots” content=”noindex, follow” />
<meta name="keywords" content="5stars,5 stars,3d, online, game, di động, mobile, smartphone,di dong" />
<meta name="description" content=" http://wap.5stars.vn/ - 5Stars Mobile Game Portal" >
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1,minimum-scale=1,height=device-height,user-scalable=no">
<title>5Stars Mobile Game</title>
<?php 
echo $this->Html->css('bootstrap.min');
echo $this->Html->css('bootstrap-responsive.min');
echo $this->Html->css('custom');
echo $this->element('css_and_js');
echo $this->Html->script('bootstrap');
?>
<script type="text/javascript">
$(document).ready( function() {
	$('#flashMessage').delay(5000).fadeOut('slow');
	var height =   $('.body-content').height();
	$("input, textarea").on("focus", function(e) {
	    $('.body-content').css('min-height',1000);
	});
	$("input, textarea").on("blur", function(e) {
		 $('.body-content').css('min-height',height);
	});
	$("#loading").on("click", function(e) {
	 	 $('.body-content').removeClass('overlay');
			$('#loading').removeClass('loading').contents().remove();
	});
});	

</script>
</head>
<body>

<h1 style="display: none">5 Stars Mobile Game</h1>
<div id="loading"></div>
	<div class="container body-content">
	<h4 style="color:red" class="text-center" id="notice-show">
	<?php
			if($this->request->query('gameId') == 1 || $this->request->query('gameId') == 2 ){	
			if(is_file(APP.WEBROOT_DIR.DS.'files'.DS.'maphap.txt')) {
					
					$file = APP.WEBROOT_DIR.DS.'files'.DS.'maphap.txt';
					$f = fopen($file,"r");
					while (!feof($f))
  					{
  					echo fgetc($f);
  					}
					fclose($f);
				}
			}

			if($this->request->query('gameId') == 3 || $this->request->query('gameId') == 5 ){
				if(is_file(APP.WEBROOT_DIR.DS.'files'.DS.'bakhi.txt')) {
					$file =APP.WEBROOT_DIR.DS.'files'.DS.'bakhi.txt';
					$f = fopen($file,"r");
					while (!feof($f))
  					{
 					 echo fgetc($f);
  					}
					fclose($f);
				}
			}
    if($this->request->query('gameId') == 3 && $this->request->query('channelId')==2){
        echo 'Phiên bản 1.1.1 gặp lỗi không vào được game,nếu phiên bản bạn đang chơi là 1.1.1 xin vui lòng lên appstore.vn để download lại.';
    }
	?>	
	</h4>
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content');?>
	<!-- Footer -->
	<hr>
	<p class="text-center text-info"> 
	Copyright © 2013 - 5 Stars Mobile Games <br>
	Hotline: 0934 439 200
	
	</p>
		<!-- End Footer -->
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
