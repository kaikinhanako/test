<?php
/* @var $games_maked Gameobj[] */
/* @var $games_favorited Gameobj[] */
?>

<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<h3 class="sub-title">お気に入り登録した言えるかな</h3>
				<?php
				if (!empty($games_favorited)) {
					foreach ($games_favorited as $i => $game) {
						$i++;
						?>
						<div class="plate plate-game">
							<p class="name"><span class="index"><?= $i ?></span><a href="<?= $game->get_link() ?>"><?= $game->get_full_title() ?></a></p>
							<p class="description"><?= $game->get_wraped_description() ?></p>
							<div class="tag-box">
								<?php
								foreach ($game->tag_list as $tag) {
									echo '<div class="tag">' . wrap_taglink_only($tag) . '</div>';
								}
								?>
							</div>
							<p class="info">
								<span class="info-item">プレイされた回数: <?= $game->play_count ?></span>
								<span class="info-item">単語数: <?= $game->words_num ?></span>
							</p>
						</div>
						<?php
					}
				} else {
					?>
					あなたがお気に入り登録した言えるかなはありません
					<?php
				}
				?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<h3 class="sub-title">作成した言えるかな</h3>
				<?php
				if (!empty($games_maked)) {
					foreach ($games_maked as $i => $game) {
						$i++;
						?>
						<div class="plate plate-game">
							<p class="name"><span class="index"><?= $i ?></span><a href="<?= $game->get_link() ?>"><?= $game->get_full_title() ?></a></p>
							<p class="description"><?= $game->get_wraped_description() ?></p>
							<div class="tag-box">
								<?php
								foreach ($game->tag_list as $tag) {
									echo '<div class="tag">' . wrap_taglink_only($tag) . '</div>';
								}
								?>
							</div>
							<p class="info">
								<span class="info-item">プレイされた回数: <?= $game->play_count ?></span>
								<span class="info-item">単語数: <?= $game->words_num ?></span>
							</p>
						</div>
						<?php
					}
				} else {
					?>
					あなたが作成した言えるかな？はまだありません
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>