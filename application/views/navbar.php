<?php
/* @var $user Userobj */
/* @var $meta Metaobj */
$user;
if ($user == null) {
	$user = false;
}
?>
<nav class="navbar navbar-default" id="navbar">
	<div class="navbar-header">
		<button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-categlyes">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>

		<a class="navbar-brand" href="<?= base_url() ?>">言えるかな<img class="log-img" src="<?= base_url(PATH_IMG . 'logo.png')?>" alt="言えるかなロゴ" /></a>
	</div>
	<div class="navbar-collapse collapse navbar-categlyes">
		<ul class="nav navbar-nav navbar-right">
			<li>
				<a <?= attr_href(base_url(PATH_MAKE)) ?>><?= tag_icon('pencil')?>作成</a>
			<li>
				<a <?= attr_href(base_url(PATH_SEARCH)) ?>><?= tag_icon('search')?>検索</a>
			<li>
				<a <?= attr_href(base_url(PATH_HELP)) ?>><?= tag_icon('question-sign')?>ヘルプ</a>
				<?php
				if (empty($user)) {
					?>
				<li>
					<a <?= attr_href(base_url(PATH_AUTH_LOGIN)) ?>>Twitterでログイン</a>
					<?php
				} else {
					?>
				<li class="img">
					<img src="<?= $user->img_url ?>" alt="言えるかな アイコン">
				<li>
					<a <?= attr_href(base_url(PATH_USER)) ?>><?= $user->screen_name ?></a>
				<li>
					<a <?= attr_href(base_url(PATH_AUTH_LOGOUT)) ?>>ログアウトする</a>
				<?php } ?>

		</ul>
	</div>
</nav>