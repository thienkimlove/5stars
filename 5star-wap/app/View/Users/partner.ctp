<div class="users form">
  <dl>
	 <dt>Partner List</dt>
	  <?php foreach ($networks as $row) : ?>
			<dd>
			   <strong><?php echo $row['Network']['name'] ?></strong>
				ID = <?php echo $row['Network']['id'] ?>
			</dd>
	  <?php endforeach; ?>
  </dl>

</div>