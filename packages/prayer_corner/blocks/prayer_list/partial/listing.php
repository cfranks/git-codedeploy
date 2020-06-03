<div class="panel panel-default">
  <div class="panel-body" style="max-height: <?php echo $bHeight; ?>px; overflow: auto;">
	<?php if (count($data)) { ?>
	<?php foreach($data as $item) { 
	$location = '';
	$location = $item['city'] . ' '. (isset($countries[$item['country']]) ? t($countries[$item['country']]) : t($item['country']));
	?>
		<blockquote>
			<p><?php echo $item['prayer_request']; ?></p>
			<div class="text-right">
				- <?php echo $item['first_name']; ?><?php echo !empty(trim($location)) ? ', '. $location : ''; ?>
			</div>
		</blockquote>
	<?php } ?>
	<?php } else { ?>
	     <p>No prayers found.</p>
	<?php } ?>
  </div>
</div>	