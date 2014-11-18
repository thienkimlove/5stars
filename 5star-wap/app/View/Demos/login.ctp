<?php $this->start('script') ?>
<script type="text/javascript">
var Config = {
    params : <?php echo json_encode($this->Session->read('Test')) ?>,
    waplink : <?php echo json_encode(Configure::read('wap')) ?>
} 
</script>
<?php $this->end(); ?>

<div class="users form" ng-app="userLoginApp" ng-controller="userLoginCtrl">

	 <div>{{message}}</div>
	<button ng-click="checkLogin()">{{buttonName}}</button>


</div>