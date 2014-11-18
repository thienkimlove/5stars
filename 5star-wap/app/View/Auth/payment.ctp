	<div  ng-app="userPaymentApp" ng-controller="userPaymentCtrl">
		
		<!-- Start form payment -->
		<form action="" class="form-horizontal" id="userPaymentForm" name="userPaymentForm" novalidate method="post" ng-show="normalForm == 1">
			<h3 class="text-info">Nạp tiền vào tài khoản</h3>
			<!-- div show error login form -->
			<div class="alert alert-block alert-error" ng-show="showErrorPayment == true">
			 <ul >
                <li ng-show="userPaymentForm['data[card_code]'].$error.required">Xin vui lòng nhập mã số thẻ cào</li>
                <li ng-show="userPaymentForm['data[card_serial]'].$error.required">Xin vui lòng nhập mã serial thẻ cào</li>
                <li ng-show="userPaymentForm['data[card_vendor]'].$error.required">Xin vui lòng chọn loại thẻ cào</li>
            </ul>
			</div>
			
			<!-- form payment input -->
			<div class="control-group">
				<label><b>Chọn Loại Thẻ :</b></label>	
				 <select name="data[card_vendor]" required ng-model="user.card_vendor" class="input-block-level">
                	<option value="">-- Chọn Loại Thẻ --</option>
               	 	<option value="viettel">Viettel</option>
                	<option value="mobifone">MobiFone</option>
                	<option value="vinaphone">VinaPhone</option>
               		 <!--  <option value="fpt">FPT Gate</option>
               				 <option value="vtc">VTC VCoin</option>
               			 <option value="mega">MegaCard</option> --> 
            	</select>
			</div>
				
			<div class="control-group">
				<label><b>Mã Số Thẻ Cào :</b></label>
				<input name="data[card_code]" type="text" ng-model="user.card_code" required placeholder="Mã số thẻ cào" class="input-block-level">  
           		<input type="hidden" ng-model="user.userId" name="data[userId]" value="<?php echo isset($params['userId']) ? $params['userId'] : '' ?>" /> 
            	<input type="hidden" ng-model="user.gameId" name="data[gameId]" value="<?php echo isset($params['gameId']) ? $params['gameId'] : '' ?>" />  
            	<input type="hidden" ng-model="user.channelId" name="data[channelId]" value="<?php echo isset($params['channelId']) ? $params['channelId'] : '' ?>" />
            	<input type="hidden" ng-model="user.serverId" name="data[serverId]" value="<?php echo isset($params['serverId'])? $params['serverId'] : ""; ?>" />
            	<input type="hidden" ng-model="user.demo" name="data[demo]" value="<?php echo isset($params['demo'])? $params['demo'] : ""; ?>" />
            	<input type="hidden" ng-model="user.subId" name="data[subId]" value="<?php echo (isset($params['subId']))? $params['subId'] : "" ?>" />   
			</div>
			<div class="control-group">
			<label><b>Mã Serial Thẻ Cào :</b></label>
				<input name="data[card_serial]" type="text" required ng-model="user.card_serial" placeholder="Mã serial thẻ cào" class="input-block-level">  
			</div>
			<div class="control-group">
				<button ng-model="user.nap" type="submit" ng-click="doPayment($event)" class="btn btn-info" >Xác nhận</button>
           		<?php if ($wakeUpSyntax) : ?>
            	<button ng-model="user.back" type="submit" class="btn btn-primary pull-right"  onClick="window.location.href='<?php echo $wakeUpSyntax ?>'">Quay về game</button>
            	<?php else : ?>
            	<button ng-model="user.back" type="submit" class="btn btn-primary pull-right"  onClick="window.location.href='<?php echo $this->Html->url(array('action' => 'profile?payment=1')) ?>'">Quay về game</button>
            	<?php endif; ?>
			
			</div>
		</form>
		<?php if($this->request->query('gameId') == 1 ||$this->request->query('gameId') == 2):?>
		<!-- End form payment -->	
		<!-- Start form giftcode -->
		<form id="userPaymentForm1" name="userPaymentForm1" novalidate method="post" action="" ng-show="normalForm == 0">
			<h3 class="text-info">Nạp Gift code</h3>
       		 <div class="alert alert-block alert-error" ng-show="showErrorPayment1 == true">
           		 <ul>
           		     <li ng-show="userPaymentForm1['data[giftcode]'].$error.required">Xin vui lòng nhập mã giftcode</li>
          		 </ul>
        	</div>
			<div class="control-group">
				<label><b>Mã giftcode:</b></label>	
            	<input name="data[giftcode]" type="text" ng-model="user.giftcode" required placeholder="Mã giftcode" class="input-block-level">  
            	<input type="hidden" ng-model="user.userId" name="data[userId]" value="<?php echo isset($params['userId']) ? $params['userId'] : '' ?>" /> 
           		<input type="hidden" ng-model="user.gameId" name="data[gameId]" value="<?php echo isset($params['gameId']) ? $params['gameId'] : '' ?>" />  
            	<input type="hidden" ng-model="user.channelId" name="data[channelId]" value="<?php echo isset($params['channelId']) ? $params['channelId'] : '' ?>" />
            	<input type="hidden" ng-model="user.serverId" name="data[serverId]" value="<?php echo isset($params['serverId'])? $params['serverId'] : ""; ?>" />
            	<input type="hidden" ng-model="user.demo" name="data[demo]" value="<?php echo isset($params['demo'])? $params['demo'] : ""; ?>" />
            	<input type="hidden" ng-model="user.subId" name="data[subId]" value="<?php echo (isset($params['subId']))? $params['subId'] : "" ?>" />   
			</div>
        <div class="control-group">
            <button ng-model="user.nap" type="submit" ng-click="doPayment1($event)" class="btn btn-info">Xác nhận</button>
            <?php if ($wakeUpSyntax) : ?>
            <button ng-model="user.back" type="submit" class="btn btn-primary pull-right" onClick="window.location.href='<?php echo $wakeUpSyntax ?>'">Quay về game</button>
            <?php else : ?>
            <button ng-model="user.back" type="submit" class="btn btn-primary pull-right" onClick="window.location.href='<?php echo $this->Html->url(array('action' => 'profile?payment=1')) ?>'">Quay về game</button>
            <?php endif; ?>

        </div>
    </form>
    <!-- End form giftcode -->
	<div class="control-group">
		 <button ng-click="normalForm = 1" ng-class="{'active' : normalForm == 1}" class="btn btn-success btn-block">Nạp Thẻ</button>
		 <button ng-click="normalForm = 0" ng-class="{'active': normalForm == 0}" class="btn btn-warning btn-block">Giftcode</button>
		</div>
		<?php endif;?>
</div><!-- End content -->
