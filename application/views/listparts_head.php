<?php
/* @var $is_hide bool */
if (!isset($is_hide)) {
	$is_hide = FALSE;
}
?>

<div class="row" <?= $is_hide ? 'style="display:none;"' : '' ?>>