<?php
/* @var $tags Tagobj[] */
?>
<div class="tag-box plate plate-left">
	<p class="sub-title"><?= tag_icon('tags') ?> 注目のタグ</p>
	<?php
	foreach ($tags as $tag) {
		echo '<span class="tag">';
		echo wrap_taglink_only($tag);
		echo '</span>';
	}
	?>
</div>