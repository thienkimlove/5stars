<?php 
	  echo $this->html->script('lib/jquery.validate');
	  echo $this->html->script('lib/messages_vi');
?>
<script type="text/javascript">
	$(window).load(function(){
		 $("#form").validate({
	            errorElement: "p", // Định dạng cho thẻ HTML hiện thông báo lỗi
	            rules: {
	                password: {
	                    equalTo: "#passwordc", // So sánh trường cpassword với trường có id là password
	    	                }
	            }
			        });
	});
</script>
<div class="right-content">

					<div class="heading">
                    	<p><?php echo $this->Html->link(__('Trang Chủ'),array('controller'=>'index','action'=>'index')) ?> &gt;</p>
                         <p class="last-beadcrumb"> Thay đổi mật khẩu</p>
                    </div>
					<?php echo $this->Session->flash(); ?>
					<form class="acount" id="form" name="userEditForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'index', 'action' => 'edit_password')) ?>">
                    	<p><label>Nhập mật khẩu cũ : <span>*</span></label><input type="password" name="passwordo" class="required" <?php if(isset($this->data['passwordo'])) echo 'value="'. $this->data['passwordo'].'"' ?>/>
                    	<?php if(isset($error['passwordo'])):?>
                    	<p class="error"><?php echo $error['passwordo'];?> </p>
                    	<?php endif;?>
                    	</p>
                        <p><label>Nhập mật khẩu mới : <span>*</span></label><input type="password" name="password" class="required" <?php if(isset($this->data['password'])) echo 'value="'. $this->data['password'].'"' ?>/>
                        <?php if(isset($error['password'])):?>
                    	<p class="error"><?php echo $error['password'];?> </p>
                    	<?php endif;?>
                        </p>
                        <p><label>Nhập lại mật khẩu mới : <span>*</span></label><input type="password" name="passwordc" class="required" id="passwordc" <?php if(isset($this->data['passwordc'])) echo 'value="'. $this->data['passwordc'].'"' ?>/>
                        <?php if(isset($error['passwordc'])):?>
                    	<p class="error"><?php echo $error['passwordc'];?> </p>
                    	<?php endif;?>
                        </p>
                       
                        <p class="control"><input type="submit" class="btn selected" value="Xác nhận"/><input type="button" class="btn" value="Huỷ"/></p>
                    </form>
					
				</div><!--End right-content-->