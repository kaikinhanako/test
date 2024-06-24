<?php
/* @var $game Gameobj */
/* @var $user Userobj */
?>

<div class="content">
	<?php
	if (!empty($user)) {
		$this->load->view('makeform', array('game' => @$game));
	} else {
		?>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<div class="plate plate-wide plate-center">
					<p class="sub-title"><strong>言えるかなを作成するにはログインが必要です</strong></p>
					<p><a class="btn btn-warning btn-lg" href="<?= base_url(PATH_AUTH_LOGIN) ?>">Twitterログイン</a></p>
				</div>
			</div>
			<div class="col-md-offset-1 col-md-10">
				<div class="plate plate-wide">
					<strong>ログインユーザは以下の機能を使用できます</strong>
					<ul class="list">
						<li>言えるかなの作成
						<li>言えるかなのブックマーク
					</ul>
					<strong>安心してご利用下さい</strong>
					<ul class="list">
						<li>このサービスは無料です
						<li>勝手にツイート・フォローを行うことはありません(それらの権限は当サイトに与えられません)
					</ul>
				</div>
			</div>
		</div>
		<?php
	}
	?>
</div>