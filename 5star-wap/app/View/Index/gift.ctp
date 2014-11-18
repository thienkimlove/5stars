<?php echo $this->Session->flash(); ?>
<form method="post" action ="<?php echo $this->Html->url(array('controller' => 'index', 'action' => 'gift')) ?>">
<input type="text" name="data[code]">
<br>
<input type="text" name="data[user_id]">
<br>
<input type="submit">
</form>