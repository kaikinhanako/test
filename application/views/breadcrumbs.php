<?php
/* @var $list array */
/* @var $rss_link string */
?>

<ul class="breadcrumb">
	<?php
	foreach ($list as $key => $url) {
		if ($url === TRUE) {
			echo '<li class="active">' . $key . '</li>';
			continue;
		}
		echo '<li><a href="' . $url . '">' . $key . '</a></li>';
	}
	if (isset($rss_link)) {
		?>
		<li class="rss-list pull-right">
			<a href="<?= $rss_link ?>"><?= tag_icon_fa('rss') ?></a>
		</li>
	<?php } ?>

</ul>