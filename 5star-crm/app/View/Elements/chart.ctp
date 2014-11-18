<?php $this->Html->script('lib/highcharts/highcharts', array('inline' => false)); ?>
<?php $this->Html->script('lib/highcharts/themes/gray', array('inline' => false)); ?>

<div class="content-box" ng-show="workingPart == 'chart'">
	<div class="box-header clear">
		<ul class="tabs clear">
			<li><a href="#chart-bar" class="selected">Pie</a></li>
			<li><a href="#chart-pie">Bar</a></li>
			<li><a href="#chart-line">Line</a></li>
			<li><a href="#chart-area">Area</a></li>
		</ul>

		<h2>Charts</h2>
	</div><!-- box-body -->

	<div class="box-body clear">

	</div><!-- /.box-body -->
</div>
