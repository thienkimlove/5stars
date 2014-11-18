<?php 
	  echo $this->html->script('lib/jquery.validate');
	  echo $this->html->script('lib/messages_vi');
?>
<script type="text/javascript">
	$(window).load(function(){
		 $("#form").validate({
	            errorElement: "p", // Định dạng cho thẻ HTML hiện thông báo lỗi
		        });
	});
</script>
<div class="right-content">
					<div class="heading">
                    	<p><?php echo $this->Html->link(__('Trang Chủ'),array('controller'=>'index','action'=>'index')) ?> &gt;</p>
                        
                        <p class="last-beadcrumb"> Cập nhật thông tin tài khoản</p>
                    </div>
					
					<form class="acount" id="form" name="userEditForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'index', 'action' => 'edit_info')) ?>">
                    	<p><label>Họ tên : <span>*</span></label><input type="text" name="data[fullname]" value="<?php echo $this->data['fullname']?>" class="required"/>
                    	<?php if(isset($error['fullname'])):?>
                    	<p class="error"><?php echo $error['fullname'];?> </p>
                    	<?php endif;?>
                    	</p>
                    	<p><label>Ngày Sinh : <span>*</span></label>
                    	
                    	 <select name="day">
                    	 <option value="">Ngày</option>
                        <?php for($i=1;$i<32;$i++):?>
                         <option value='<?php echo $i;?>' <?php if($this->data['day']== $i) echo 'selected="selected"'?>><?php echo $i;?></option>
                        <?php endfor;?>
                        </select>
                        
                         <select name="month">
                    	 <option value="">Tháng</option>
                        <?php for($i=1;$i<13;$i++):?>
                         <option value='<?php echo $i;?>' <?php if($this->data['month']== $i) echo 'selected="selected"'?>><?php echo $i;?></option>
                        <?php endfor;?>
                        </select>
                        <select name="year">
                    	 <option value="">Năm</option>
                        <?php for($i=date('Y')-10;$i>date('Y')-90;$i--):?>
                         <option value='<?php echo $i;?>' <?php if($this->data['year']== $i){ echo 'selected="selected"';}?>><?php echo $i;?></option>
                        <?php endfor;?>
                        </select>
                        <?php if(isset($error['birth'])):?>
                    	<p class="error"><?php echo $error['birth'];?> </p>
                    	<?php endif;?>
                       </p>
                        <p class="gioitinh"><label>Giới tính : <span>*</span></label>
                        	<input type="radio" name="gender" value="Nam" <?php if(isset($this->data['gender'])) if($this->data['gender']=='Nam'){ echo 'checked="checked"';}?> />Nam
                            <input type="radio" name="gender" value="Nữ" <?php if(isset($this->data['gender'])) if($this->data['gender']=='Nữ'){ echo 'checked="checked"';}?>/>Nữ
                            <span>(*)</span>
                            <?php if(isset($error['gender'])):?>
                    	<p class="error"><?php echo $error['gender'];?> </p>
                    	<?php endif;?>
                        </p>
                        <p><label>Số điện thoại : <span>*</span></label><input type="text" name="mobile" class="digits required" value="<?php echo $this->data['mobile']; ?>"/>
                        <?php if(isset($error['mobile'])):?>
                    	<p class="error"><?php echo $error['mobile'];?> </p>
                    	<?php endif;?>
                        </p>
                        <p class="luuy">Lưu ý: Bạn phải nhập đầy đủ vào các trường có dấu(*)</p>
                        <p class="control"><input type="submit" class="btn selected" value="Xác nhận"/><input type="button" class="btn" value="Huỷ"/></p>
                    </form>
				
				</div><!--End right-content-->