<!-- payment -->
<div class="content-box" ng-show="workingPart == 'paymentList'">
	<div class="box-header clear">
		<ul class="tabs clear">
			<li style="cursor: pointer;"><a ng-click="p.switchTime('today')"  ng-class="{'selected' : p.filter.time == 'today'}">Today</a></li>
			<li style="cursor: pointer;"><a ng-click="p.switchTime('thisweek')"  ng-class="{'selected' : p.filter.time == 'thisweek'}">This week</a></li>
			<li style="cursor: pointer;"><a ng-click="p.switchTime('thismonth')"  ng-class="{'selected' : p.filter.time == 'thismonth'}">This month</a></li>
			<li style="cursor: pointer;"><a ng-click="p.switchSendGameStatus('1')"  ng-class="{'selected' : p.filter.send_game_status == '1'}">Thành công</a></li>
			<li style="cursor: pointer;"><a ng-click="p.switchSendGameStatus('0')"  ng-class="{'selected' : p.filter.send_game_status == '0'}">Không thành công</a></li>
			<li style="cursor: pointer;"><a ng-click="p.switchGiftcode()"  ng-class="{'selected' : p.filter.giftcode ==  1}">GiftCode</a></li>
		</ul>

		<h2>Lịch sử thanh toán (Total : <span ng-bind="p.paymentCount"></span> - Money : <span ng-bind="p.paymentAmount | dot"></span>)</h2>
	</div>
	<!--Edit Payment--> 
	<div class="box-body clear" ng-show="p.showEditPaymentForm">
		<div id="formPaymentEdit">
			<form name="paymentEditForm" class="form" novalidate>
				<div class="form-field clear">
					<label for="textfield" class="form-label fl-space2">Amount:</label>
					<input type="text" ng-model="p.paymentEdit.Payment.amount" class="text fl"  />
				</div><!-- /.form-field -->

				<div class="form-field clear">
					<label for="select" class="form-label fl-space2">Trạng thái thanh toán</label>
					<select class="fl" ng-model="p.paymentEdit.Payment.payment_status" ng-options="b.value as b.name for b in [{ value : '1', name : 'Thành công' }, { value : '0', name : 'Không thành công' }]"></select>
				</div><!-- /.form-field -->  

				<div class="form-field clear">
					<label for="select" class="form-label fl-space2">Đã gửi tới game?</label>
					<select class="fl" ng-model="p.paymentEdit.Payment.send_game_status" ng-options="c.value as c.name for c in [{ value : '1', name : 'Đã gửi' }, { value : '0', name : 'Chưa gửi' }]"></select>
				</div><!-- /.form-field -->  

				<div class="form-field clear">
					<label for="select" class="form-label fl-space2">Thiết lập cron?</label>
					<select class="fl" ng-model="p.paymentEdit.Payment.cron" ng-options="d.value as d.name for d in [{ value : '1', name : 'Có' }, { value : '0', name : 'Không' }]"></select>
				</div><!-- /.form-field -->   



				<div class="form-field clear">
					<input ng-model="p.paymentEdit.submit" ng-click="p.updatePayment($event)" type="submit" class="submit fr" value="Submit" />
				</div><!-- /.form-field -->     
                
                <div class="form-field clear">
                    <input ng-model="p.paymentEdit.cancel" ng-click="p.cancelPayment($event)" type="submit" class="submit fr" value="Cancel" />
                </div><!-- /.form-field -->                                                                                            
			</form>
		</div>
	</div>
	<!--List Payment-->
	<div class="box-body clear">
		<!-- TABLE -->
		<div id="tablePayment">
			<p></p> 
			<div class="dataTables_wrapper">				
				<div class="dataTables_length"> Chọn Kênh <select ng-change="p.renderPayment();" ng-model="p.filter.channel" ng-options="s.Channel.id as s.Channel.name for s in c.channels"><option value="">-- All --</option></select>
				</div>
				&nbsp;
				<div class="dataTables_length"> Chọn Game <select ng-change="p.renderPayment();" ng-model="p.filter.game" ng-options="s.Game.id as s.Game.name for s in g.games"><option value="">-- All --</option></select>
				</div>
				
				&nbsp;
				<div class="dataTables_length">Custom Date: <input placeholder="ngày bắt đầu" datepicker ng-model="p.filter.start_date" type="text"> &nbsp; <input placeholder="ngày kết thúc" datepicker ng-model="p.filter.end_date" type="text"> &nbsp; <button ng-click="p.renderPayment()">Tìm</button>&nbsp;&nbsp;<button ng-click="p.endCustom()">Bỏ Custom</button></div>
				
				<div class="dataTables_filter">Tìm theo Username: <input ng-model="p.filter.textFilter" type="text"> &nbsp; <button ng-show="p.paymentSearchButton == false;" ng-click="p.startSearch()">Tìm</button><button ng-click="p.endSearch()" ng-show="p.paymentSearchButton">Bỏ tìm</button></div>
				
				
				
				<table class="datatable" style="width: 984px;">

					<thead>
						<tr>    
							<th>Payment ID</th>                                        
							<th>Thành viên</th>
							<th>Kênh</th>                                            
							<th>Game</th>
							<th>ServerId</th>
							<th>SubId</th>
							<th>Amount</th>
							<th>Trạng thái</th>
							<th>Đã gửi tới Game?</th>
							<th>Thời gian</th>
							<th>Cron</th>
							<th>Thiết lập</th>
						</tr>
					</thead>

					<tbody>
						<tr ng-repeat="payment in p.currentPayments | limitTo:p.filter.limit">   
							<td ng-bind="payment.Payment.id"></td>                                        
							<td ng-bind="payment.User.username"></td>
							<td ng-bind="payment.Channel.name"></td>
							<td ng-bind="payment.Game.name"></td>
							<td ng-bind="payment.Payment.server_id"></td>
							<td ng-bind="payment.Payment.sub_id"></td>
							<td ng-bind="payment.Payment.amount | dot"></td>
							<td ng-bind="payment.Payment.payment_status"></td>
							<td ng-bind="payment.Payment.send_game_status"></td>
							<td ng-bind="payment.Payment.created | timeAgo"></td> 
							<td ng-bind="payment.Payment.cron"></td>                                        
							<td>
								<a style="cursor: pointer;" ng-show="permission == 'admin'" ng-click="p.editPayment(payment)"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" class="icon16 fl-space2" alt="" title="edit" /></a>
								<a style="cursor: pointer;display:none" href="#" ><img src="<?php echo $this->Html->imageUrl('ico_delete_16.png') ?>" class="icon16 fl-space2" alt="" title="delete" /></a>
								<a style="cursor: pointer;display:none" href="#"><img src="<?php echo $this->Html->imageUrl('ico_settings_16.png') ?>" class="icon16 fl-space2" alt="" title="settings" /></a>
							</td>
						</tr>

					</tbody>
				</table>




				<div class="tab-footer clear fl" ng-show="p.paymentCount >= p.filter.limit">
					<div class="fl">                                
						<input type="submit" ng-model="payment.loadmore" class="submit fl-space" ng-click="p.filter.limit = p.filter.limit + 20; p.renderPayment()" value="Load More">
					</div>
				</div>

			</div><!-- /#table -->


		</div> <!-- end of box-body -->


	</div>

</div>
