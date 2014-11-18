<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name=”robots” content=”noindex, follow” />
<meta name="keywords" content="5stars,5 stars,3d, online, game, di động, mobile, smarphone,di dong" />
<meta name="description" content=" http://wap.5stars.vn/ - 5Stars Mobile Game Portal" >
<?php 
echo $this->Html->css('style_m');
echo $this->element('css_and_js');
?>
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
		<div class="header">
			<?php echo $this->Html->image("logo.png", array("alt" => "5Stars"));?>
			
		</div><!--End header-->
		<?php echo $this->Session->flash(); ?>
				
		<?php echo $this->fetch('content');?>
		
		<div class="footer">
			<ul class="foot-nav">
				<li><?php echo $this->html->link('Trang Chủ',array('controller'=>'users','action'=>'login'),array('class'=>'link-footer so1'))?> </li>
				<li><a href="#" class="link-footer so2">Nạp thẻ</a></li>
				<li><?php echo $this->html->link('Hỏi Đáp',array('controller'=>'users','action'=>'faqs'),array('class'=>'link-footer so3'))?></li>
				<li><a href="#" class="link-footer so4">Hướng dẫn</a></li>
			</ul>
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
