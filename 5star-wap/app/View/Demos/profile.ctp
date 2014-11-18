<?php $this->start('script') ?>
<script type="text/javascript">
var Config = {
    params : <?php echo json_encode($this->Session->read('Test')) ?>,
    waplink : <?php echo json_encode(Configure::read('wap')) ?>
} 
</script>
<?php $this->end(); ?>

<div class="users form" ng-app="userProfileApp" ng-controller="userProfileCtrl" ng-cloak>
<h2>Welcome<span style="float: right"><?php echo $this->Html->link('Logout',array('controller'=>'demos', 'action' => 'index')) ?></span></h2>
  <dl>
	 <dt>Coin : {{coin}}</dt>
	 
	 <dd></dd>
	 <dd ng-show="process == 0">
     <p>
       current User : <?php echo $this->Session->read('Test.authId') ?>
     </p>
		 <p>  
			  <button ng-click="doPayment()">Add Wcoin</button>
		 </p> 
		  
	 </dd>
	 <dd ng-show="process == 1">
				
				<p>
				   Done
				</p>
	 </dd>
  </dl>  
</div>