<?php echo $this->html->script('jquery.selectBox');
	  echo $this->html->script('lib/jquery.validate');
	  echo $this->html->script('lib/messages_vi');
?>
<script type="text/javascript">
	$(window).load(function(){
		$(".insert-select select").selectBox();
		$('.selectBox-dropdown').css('width','92%');
		$(".insert-birth select").selectBox();
		$(".insert-birth .selectBox-dropdown").css('width','20%');
		
		 $("#form").validate({
	            errorElement: "p", // Định dạng cho thẻ HTML hiện thông báo lỗi
		        });
	});
</script>

<form class="content" id="form" method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit_info')) ?>">
			<h2 class="heading">Xin Chào ( <?php echo $this->Session->read('Auth.User.Login')?> )</h2>
			
			<p class="insert-text"><input type="text" name="data[fullname]" value="<?php echo $this->data['fullname']?>" placeholder="Họ tên" class="required"/>
			<?php if(isset($error['fullname'])):?>
                    	<p class="error"><?php echo $error['fullname'];?> </p>
                    	<?php endif;?>
			</p>
			<p class="insert-select">
				<select name="gender">
					<option value="Nam" <?php if(isset($this->data['gender'])) if($this->data['gender']=='Nam'){ echo 'selected="selected"';}?>>Nam</option>
					<option value="Nữ" <?php if(isset($this->data['gender'])) if($this->data['gender']=='Nữ'){ echo 'selected="selected"';}?>>Nữ</option>
				</select>
				 <?php if(isset($error['gender'])):?>
                    	<p class="error"><?php echo $error['gender'];?> </p>
                   <?php endif;?>
			</p>
			<p class="insert-birth">
			<select name="day">
                    	 <option value="0">Ngày</option>
                        <?php for($i=1;$i<32;$i++):?>
                         <option value='<?php echo $i;?>' <?php if($this->data['day']== $i) echo 'selected="selected"'?>><?php echo $i;?></option>
                        <?php endfor;?>
                        </select>
                        
                         <select name="month">
                    	 <option value="0">Tháng</option>
                        <?php for($i=1;$i<13;$i++):?>
                         <option value='<?php echo $i;?>' <?php if($this->data['month']== $i) echo 'selected="selected"'?>><?php echo $i;?></option>
                        <?php endfor;?>
                        </select>
                        <select name="year">
                    	 <option value="0">Năm</option>
                        <?php for($i=date('Y')-10;$i>date('Y')-90;$i--):?>
                         <option value='<?php echo $i;?>' <?php if($this->data['year']== $i){ echo 'selected="selected"';}?>><?php echo $i;?></option>
                        <?php endfor;?>
                        </select>
                        <?php if(isset($error['birth'])):?>
                    	<p class="error"><?php echo $error['birth'];?> </p>
                    	<?php endif;?>
			</p>
			<p class="insert-text last"><input type="text" name="mobile"  class="digits required" maxlength="12" value="<?php echo $this->data['mobile']; ?>" placeholder="Số điện thoại"/>
			<?php if(isset($error['mobile'])):?>
                    	<p class="error"><?php echo $error['mobile'];?> </p>
            <?php endif;?>
			
			</p>
			
			
			<p class="btn-submit"><input type="submit" name="Xác nhận" value="Xác nhận"/></p>
		</form><!--End content-->