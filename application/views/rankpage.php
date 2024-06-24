<?php
/* @var $game Gameobj */
/* @var $is_owner bool */
?>

<div class="content">
	<?php $this->load->view('gameinfo', array("game" => $game, 'gamemode' => GAME_MODE_RANK)); ?>
	<div class="rank-container">
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<div class="row">
					<div class="col-md-6 rank-box">
						<span class="sub-title">人気ワード</span>
						最初に答えられやすい順
						<ul class="item-rank">
							<?php
							$rank = 1;
							$p_pre = NULL;
							foreach ($game->get_words_popular() as $i => $word) {
								if (isset($p_pre) && $p_pre != $word->point_positive) {
									$rank = $i + 1;
								}
								$p_pre = $word->point_positive;
								?>
 								<li class="rank-<?= $rank ?>">
									<div class="row">
										<div class="col-md-2 rank">
											<?= $rank ?>
										</div>
										<div class="col-md-10 text">
											<span><?= $word->text ?></span>
										</div>
										<div class="col-md-12 graph">
											<div class="progress progress-striped">
												<div class="progress-bar progress-bar-warning" style="width: <?= $word->get_rate_point_positive(TRUE) ?>%"></div>
											</div>
										</div>
									</div>
								</li>
								<?php } ?>
						</ul>
					</div>
					<div class="col-md-6 rank-box">
						<span class="sub-title">残念ワード</span>
						よく忘れられている順
						<ul class="item-rank">
							<?php
							$rank = 1;
							$p_pre = NULL;
							foreach ($game->get_words_abord() as $i => $word) {
								if (isset($p_pre) && $p_pre != $word->point_negative) {
									$rank = $i + 1;
								}
								$p_pre = $word->point_negative;
								?>
 								<li class="rank-<?= $rank ?>">
									<div class="row">
										<div class="col-md-2 rank">
											<?= $rank ?>
										</div>
										<div class="col-md-10 text">
											<span><?= $word->text ?></span>
										</div>
										<div class="col-md-12 graph">
											<div class="progress progress-striped">
												<div class="progress-bar progress-bar-info" style="width: <?= $word->get_rate_point_negative(TRUE) ?>%"></div>
											</div>
										</div>
									</div>
								<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>

	</div>
	<?php
	if ($is_owner) {
		$this->load->view('ownerpanel', array('game' => $game));
	}
	?>
</div>