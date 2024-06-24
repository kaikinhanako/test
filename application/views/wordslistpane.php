<?php
/* @var $games Gameobj[] */
/* @var $title string */
/* @var $icon string */
/* @var $is_worst boolean */
/* @var $category string[] */
if (!isset($is_worst)) {
	$is_worst = FALSE;
}
?>
<div class="words-list-pane plate plate-left">
	<p class="sub-title">
		<?php
		if (isset($icon)) {
			echo tag_icon($icon);
		}
		?>
		<?= $title ?>
	</p>
	<ul class="words-list">
		<?php
		foreach ($games as $game) {
			if (!$is_worst) {
				$words = $game->get_words_popular();
			} else {
				$words = $game->get_words_abord();
			}

			list($k, $word) = each($words);
			?>
			<li>
				<p class="word" data-toggle="tooltip" title="<?= $game->get_full_title(TRUE) ?>"><a href="<?= $game->get_link() ?>"><?= $word->text ?></a></p>
			</li>
			<?php
		}
		?>
	</ul>
</div>
