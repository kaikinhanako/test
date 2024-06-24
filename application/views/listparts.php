<?php
/* @var $items array */
/* @var $title string */
/* @var $col int */
/* @var $num int */
/* @var $icon string */
/* @var $more_link string */
if (!isset($num)) {
	$num = 5;
}
?>

<?php
if (isset($col)) {
	echo '<div class="col-md-' . $col . '">';
}
?>
<div class="plate plate-tab">
	<span class="sub-title">
		<?php
		if (isset($icon)) {
			echo tag_icon($icon);
		}
		?>
		<?= $title ?>
	</span>
</div>
<div class="plate plate-wide">
	<ul class="list-min">
		<?php
		foreach ($items as $i => $item) {
			$target_blank = ' target="_blank"';
			if ($item instanceof Gameobj) {
				$item_url = $item->get_link();
				$item_title = $item->get_full_title();
				$target_blank = '';
			} elseif ($item instanceof Itemobj) {
				$item_url = $item->link;
				$item_title = $item->title;
			}
			echo '<li>';
			echo '<p><a href="' . $item_url . '"' . $target_blank . '>' . $item_title . '</a></p>';
			if ($i == $num - 1) {
				break;
			}
		}
		?>
	</ul>
	<?php if (isset($more_link)) { ?>
		<div class="pull-right btn-more-wrap">
			<!--<div class="btn-ribbon-head"> </div>-->
			<a href="<?= $more_link ?>" class="btn btn-default btn-ribbon">もっと見る</a>
		</div>
	<?php } ?>
</div>
<?php
if (isset($col)) {
	echo '</div>';
}