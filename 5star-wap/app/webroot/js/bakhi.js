  window.fbAsyncInit = function() {
    FB.init({
      appId      : '165774193618638', // Set YOUR APP ID
      channelUrl : 'https://wap.5stars.vn/channel.php', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
  }	
  function Login(){
		
    	 FB.login(function(response) {
		   if (response.authResponse) {
 			 getAccount(response);
 	  		 
		   } else {

 			 var str="<ul class='unstyled text-error'>";
              str +="<li> Bạn vui lòng cho ứng dụng xin quyền truy cập";
              str +="</ul>"
              $('#message').html(str);
              $('#getAccount').removeAttr("disabled");
		   }
		 },{scope: 'email,publish_stream'});
    	 
		 }
    function getAccount(response){
    	$.ajax({
            url: "https://wap.5stars.vn/auth/fetch/"+response.authResponse.accessToken,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(!data.error){ 
            	var str="Tài Khoản Alpha test của bạn:<br><ul class='unstyled text-error'>";
                str +="<li><b>username: </b>"+data.username+"<br>";
                str +="<li><b>password: </b>"+data.password+"<br>";
                str +="</ul>"
                $('#message').html(str);
                }
                else{
                	var str="<ul class='unstyled'>";
                    str +="<li>"+data.error;
                    str +="</ul>"
                    $('#message').html(str);
                    $('#getAccount').removeAttr("disabled");
                   }
            }, 
            error: function(data){
                var str ="Có lỗi xảy ra vui lòng thử lại trong giây lát";
            	$('#message').html(str);
                },
            beforeSend: function(){
            	$('#message').html('<p class="text-info">Đang xử lý dữ liệu vui lòng chờ ...</p>');
                }                                               
        })
        };
 
  
$( document ).ready(function() {
		var step= 0;
		$('#btnXem').click(function(){
			$('#account').hide();
			$('#download').hide();
			$('#'+step).hide();
			switch (step){
			case 1:case 2 :case 3:
				step++;
			break;
			default:
				step= 1;
			break;
				};
				$('#'+step).show("pulsate","swing","slow");	
			});
		$('#btnAccount').click(function(){
			$('#'+step).hide();
			$('#download').hide();
			$('#account').show("pulsate","swing","slow");
			step =0;
		});
		$('#btnTest').click(function(){
			$('#'+step).hide();
			$('#download').hide();
			$('#account').show("pulsate","swing","slow");
			step =0;
		});
		$('#btnDownload').click(function(){
			$('#'+step).hide();
			$('#account').hide();
			$('#download').show("pulsate","swing","slow");step =0;
		});
});	