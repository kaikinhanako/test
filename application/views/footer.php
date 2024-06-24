<?php ?>

<div id="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<div class="row">
					<div class="col-md-4">
						<b>メニュー</b>
						<ul>
							<li><a href="<?= base_url() ?>">トップページ</a>
							<li><a href="<?= base_url(PATH_HELP) ?>">言えるかなを作る</a>
							<li><a href="<?= base_url(PATH_USER) ?>">マイページ</a>
							<li><a href="<?= base_url(PATH_HELP) ?>">ヘルプ</a>
						</ul>
					</div>
					<div class="col-md-4">
						<b>リスト</b>
						<ul>
							<li><a href="<?= base_url(PATH_HOT) ?>">人気の言えるかな</a>
							<li><a href="<?= base_url(PATH_NEW) ?>">新着言えるかな</a>
						</ul>
					</div>
					<div class="col-md-4">
						<b>リンク</b>
						<ul>
							<li><a href="//tohyomaker.com/" target="_blank">投票メーカー</a>
							<li><a href="https://twitter.com/ieru_kana" class="twitter-follow-button" data-show-count="false">Follow @ierukana</a>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<b>言えるかな？をシェアしよう</b>
						<ul>
							<li>
								<div class="tweet-btn">
									<?php
									$text = '言えるかな？ゲームに挑戦しよう';
									sharebtn_twitter($text, base_url(), TRUE, TRUE);
									?>
								</div>
							</li>
							<li>
								<div class="fb-share-button" data-href="https://ierukana.elzup.com" data-layout="button_count"></div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
