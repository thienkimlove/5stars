<script type="text/javascript" src="<?php echo $scriptInclude ?>"></script>
<?php if (!empty($payment)) : ?>
<script type="text/javascript">
onClose();
</script>
<?php elseif (isset($share)) : ?>
<script type="text/javascript">
<?php  if ($share == 1) : ?>
onSuccess();
<?php else : ?>
onClose();
<?php endif; ?>
</script>
<?php else : ?>
<script type="text/javascript">
var token = "<?php echo $token ?>";
onSuccess(token);
</script>
<?php endif; ?>
 <div class="progress progress-striped active" ng-hide="showFacebookButton">
    <div class="bar" style="width: 100%;"></div>
 </div>