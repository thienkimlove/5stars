<html>
<head>
    <meta name="keywords"
          content="ba khi, ba khi giang ho, mobile game, online, game, gMO, chiến thuật, rpg, mang xa hoi, slg, simulitation, battle card, di dong, mobile, smartphone ,kim dung, anh hung, hiep khach, bi tich, giang ho, vo lam, thanh co, ap tieu, vuot ai, luan kiem, tranh doat, hao huu, danh dap, an oan," />
    <meta name="description"
          content="Bá Khí Giang Hồ Online - Tuyệt phẩm Game Kiếm hiệp Kim Dung trên Smartphone. Bá Khí Giang Hồ là tựa Game Mobile Online miễn phí tổng hợp nội dung các tác phẩm kiếm hiệp nổi tiếng của Kim Dung như Anh Hùng Xạ Điêu, Thần Điêu Đại Hiệp, Ỷ Thiên Đồ Long Ký, Thiên Long Bát Bộ, Tiếu Ngạo Giang Hồ... Phiêu bạt Giang hồ, luận kiếm, hảo hữu, tranh đoạt bí tịch, bảo vật cùng hàng ngàn Anh hùng Hiệp khách Chính - Tà bất phân." />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    echo $this->Html->css('bootstrap3.min');
    echo $this->Html->css('daily-code');
    ?>
    <style>body{font-size:1.6em}.row{padding:10px;border-bottom:1px solid #ddd;}</style>

    <title>Bá Khí Giang Hồ</title>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=481810968597539";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<?php if(empty($data['page']['liked']) && $data['page']['id']=='514625201965475'){
    echo $this->Html->image('fb1/like_fan.jpg');
}else{?>
    <div id="loading"></div>
    <div class="container"  id="body-content">
        <div id="flash-messenger"></div>
        <?php echo $response?>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.fbAsyncInit=function(){FB.init({appId:"481810968597539",channelUrl:"https://wap.5stars.vn/channel.php",status:true,cookie:true,xfbml:true});};$(document).ready(function(){function t(t,n){t=t||"";n=n||"danger";var r="alert-"+n;e.alert.removeClass().addClass("alert "+r).html(t).show("slow")}function n(){e.body.addClass("overlay");e.loading.addClass("loading").append('<div class="ball"></div>')}function r(){e.body.removeClass("overlay");e.loading.removeClass("loading").contents().remove()}function i(e){FB.login(function(t){if(t.authResponse){s(t,e)}else{var n="<p class='warning'>Bạn vui lòng cho ứng dụng xin quyền truy cập</p>";$("#message").html(n)}},{scope:"email,publish_stream,user_likes"})}function s(e,i){$.ajax({url:"//wap.5stars.vn/fb/getDailyCode/"+e.authResponse.accessToken+"/"+i,type:"GET",dataType:"json",success:function(e){r();if(!e.error){var n='Code của bạn: <b style="color:red">'+e.code+"</b>";if(e.server=="1"){n+=" Server Quỳ Hoa Bảo Điển"}else if(e.server=="2"){n+=" Server Nhất Dương Chỉ"}else if(e.server=="3"){n+=" Server Hấp Tinh Đại Pháp"}t(n,"success")}else{t(e.error)}},error:function(e){r();t("Có lỗi xảy ra vui lòng thử lại trong giây lát")},beforeSend:function(){n()}})}var e={body:$("#body-content"),loading:$("#loading"),alert:$("#flash-messenger")};$("body").on("click",".btn",function(e){e.preventDefault();var t=$(this).attr("data-date");i(t)})});</script>
<?php }?>


</body>

</html>