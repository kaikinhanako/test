<div id="topjumbo" class="jumbotron">
	<h1 itemscope itemtype="http://schema.org/SoftwareApplication"><span itemprop="name">言えるかな</span><img itemprop="image" class="log-img" src="<?= base_url(PATH_IMG . 'logo.png') ?>" alt="言えるかなロゴ" /></h1>
	<p class="description">
		<span class="strong-s">腕試し</span>に<span class="strong-s">暇つぶし</span>に<span class="strong-s">学習</span>に<br />
		<strong>言えるかな？</strong>とは、お題に沿った単語のリストの中でいくつ答えられるかを試すゲームです<br />
		このサイトはいろいろな人が作った<strong>言えるかな？</strong>で遊ぶことが出来るサイトです<br />
		あなたの作りたい言えるかな？を作ることも出来ます
	</p>
	<div class="row sub-btns">
		<div class="col-md-3">
			<a class="btn btn-primary btn-lg btn-block" href="<?= base_url(PATH_MAKE) ?>">言えるかな？を作る</a>
		</div>
		<div class="col-md-3">
			<button id="btn-please" class="btn btn-primary btn-lg btn-block">誰か作ってと頼む</button>
		</div>
		<div class="col-md-3">
			<div class="tweet-btn">
				<?php
				$text = '言えるかな？ゲームに挑戦しよう';
				sharebtn_twitter($text, base_url(), TRUE, TRUE);
				?>
			</div>
		</div>
	</div>
</div>
