<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name=”robots” content=”noindex, follow” />
<meta name="keywords"
	content="5stars,5 stars,3d, online, game, di động, mobile, smartphone,di dong" />
<meta name="description"
	content=" http://wap.5stars.vn/ - 5Stars Mobile Game Portal">
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1,minimum-scale=1,height=device-height,user-scalable=no">
<title>5Stars Mobile Game</title>
<?php
echo $this->Html->css ( 'bootstrap.min' );
echo $this->Html->css ( 'bootstrap-responsive.min' );
echo $this->Html->css ( 'custom' );
echo $this->Html->script ( 'lib/jquery-1.9.1.min' );

echo $this->Html->script ( 'bootstrap' );

echo $this->Html->script ( 'ckeditor/ckeditor' );

?>


</head>
<body>
	<div id="loading"></div>
	<h1 style="display: none">5 Stars Mobile Game</h1>
	<div class="container body-content">
	
		<?php echo $this->Session->flash(); ?>
		
		
		<form action="" class="form-horizontal" method="post">
			<div class="control-group">
				<label><b>Game:</b></label> <select name="game"
					class="input-block-level">
					<option value="maphap">Ma Pháp</option>
					<option value="bakhi">Bá Khí</option>
				</select>
			</div>

			<div class="control-group">
				<label><b>Nội dung</b></label>
				<textarea id="editor1" name="content" class="input-block-level"></textarea>
			</div>

			<div class="control-group">
				<button type="submit" class="btn btn-info btn-block" name="Xác nhận"
					value="Xác nhận">Xác Nhận</button>
			</div>
		</form>
		<form name="multiform" id="multiform" method="POST"
			enctype="multipart/form-data" class="form-horizontal">
			<div class="control-group">
				<label><b>Add image</b></label> <input type="file"
					name="data[photo]" id="upload" value="" />

			</div>
		</form>

		<!-- Footer -->

		<hr>
		<p class="text-center text-info">
			Copyright © 2013 - 5 Stars Mobile Games <br> Hotline: 0934 439 200

		</p>
		<!-- End Footer -->
	</div>

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
	 

$("#upload").on("change", function(e) {
	 var ext = $('#upload').val().split('.').pop().toLowerCase();
	 if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
	     alert('invalid extension!');
	     return;
	 }
	
	$("#multiform").submit();
	 
	 
 	});  
function getDoc(frame) {
    var doc = null;
    
    // IE8 cascading access check
    try {
        if (frame.contentWindow) {
            doc = frame.contentWindow.document;
        }
    } catch(err) {
    }

    if (doc) { // successful getting content
        return doc;
    }

    try { // simply checking may throw in ie8 under ssl or mismatched protocol
        doc = frame.contentDocument ? frame.contentDocument : frame.document;
    } catch(err) {
        // last attempt
        doc = frame.document;
    }
    return doc;
}

$("#multiform").submit(function(e)
			{
				var formObj = $(this);
				var formURL = "<?php echo $this->Html->url(array('controller'=>'auth','action'=>'upload'))?>"

				if(window.FormData !== undefined)  // for HTML5 browsers
				{
				
					var formData = new FormData(this);
					$.ajax({
			        	url: formURL,
						type: "POST",
						data:  formData,
						mimeType:"multipart/form-data",
						
						contentType: false,
			    	    cache: false,
						processData:false,
						beforeSend:function(){
							$('.body-content').addClass('overlay');
			 	      		$('#loading').addClass('loading').append('<div class="ball"></div>');
							},
						success: function(data, textStatus, jqXHR)
					    {
						    console.log(data);
							 $('.body-content').removeClass('overlay');
				 	      		$('#loading').removeClass('loading').contents().remove();
				 	      		data =JSON.parse(data);
				 	      		img = '<img src="'+data.file+'"/>'
				 	      		CKEDITOR.instances.editor1.insertHtml(img);
					    },
					  	error: function(jqXHR, textStatus, errorThrown) 
				    	{  console.log(data);
					  		 $('.body-content').removeClass('overlay');
				 	      		$('#loading').removeClass('loading').contents().remove();
					    	alert('Có lỗi đường truyền');
				    	} 	        
				   });
			        e.preventDefault();
			   }
			   else  //for olden browsers
				{
					//generate a random id
					var  iframeId = "unique" + (new Date().getTime());

					//create an empty iframe
					var iframe = $('<iframe src="javascript:false;" name="'+iframeId+'" />');

					//hide it
					iframe.hide();

					//set form target to iframe
					formObj.attr("target",iframeId);

					//Add iframe to body
					iframe.appendTo("body");
					iframe.load(function(e)
					{
						var doc = getDoc(iframe[0]);
						var docRoot = doc.body ? doc.body : doc.documentElement;
						var data = docRoot.innerHTML;
						//data return from server.
						console.log(data);
						data =JSON.parse(data);
		 	      		img = '<img src="'+data.file+'"/>'
		 	      		CKEDITOR.instances.editor1.insertHtml(img);
					});
				
				}

			});
		
    	  $("#loading").on("click", function(e) {
 	       	 $('.body-content').removeClass('overlay');
 	      		$('#loading').removeClass('loading').contents().remove();
 	      	});


    	  CKEDITOR.on('instanceReady', function(evt){
    		  // Do your bindings and other actions here for example
    		  // You can access each editor that this event has fired on from the event
    		  var editor = evt.editor;
    		});
    	  CKEDITOR.replace( 'content', {
  			toolbar: [
  				[ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'JustifyLeft' , 'JustifyCenter', 'JustifyRight'   ],
  				[ 'TextColor', 'BGColor' ],
  			]
  		});
});	



</script>
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
