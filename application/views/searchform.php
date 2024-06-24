<?php
/* @var $q string */
$q = @$q ? : '';
?>

<div class="plate plate-search">
	<div class="form-group">
		<form action="<?= base_url(PATH_SEARCH) ?>" method="GET">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><?= tag_icon('search') ?></span>
					<input class="form-control" name="q" id="input-search" value="<?= $q ?>" type="text">
					<span class="input-group-btn">
						<button class="btn btn-primary" type="button">検索</button>
					</span>
				</div>
			</div>
		</form>
	</div>
</div>