<?php
/* @var $game Gameobj */
/* @var $gamemode string */

$title = $game->get_full_title(TRUE);
switch ($gamemode) {
	case GAME_MODE_EASY:
		$title .= '(やさしい)';
		break;
	case GAME_MODE_SO_EASY:
		$title .= '(超やさしい)';
		break;
	case GAME_MODE_TYPING:
		$title .= '(タイピング)';
		break;
	case GAME_MODE_RANK:
		$title .= '(ランキング)';
		break;
	default:
		break;
}
?>
<div id="game-info" class="plate">
	<div class="row">
		<div class="col-md-3">
			<?php if (isset($game->is_favorited)) { ?>
				<button id="favorite-btn" class="btn btn-favorite <?= $game->is_favorited ? 'hidden' : '' ?>" data-toggle="tooltip" data-placement="top" title="お気に入り登録する" >☆</button>
				<button id="unfavorite-btn" class="btn btn-favorite <?= !$game->is_favorited ? 'hidden' : '' ?>" data-toggle="tooltip" data-placement="top" title="お気に入り解除する">★</button>
			<?php } else { ?>
				<button class="btn btn-favorite disabled disabled-tmp" data-toggle="tooltip" data-placement="top" title="お気に入り登録にはログインが必要です">☆</button>
			<?php } ?>
			<div class="btn-group">
				<a class="btn btn-default <?= $gamemode == GAME_MODE_RANK ? '' : 'disabled' ?>" href="<?= $game->get_link() ?>"><?= tag_icon('play') ?>ゲーム</a>
				<a class="btn btn-default <?= $gamemode != GAME_MODE_RANK ? '' : 'disabled' ?>" href="<?= $game->get_ranklink() ?>"><?= tag_icon('sort-by-attributes') ?>統計　</a>
			</div>
		</div>
		<div class="col-md-8">
			<h1 class="page-title"><?= $title ?></h1>
		</div>
	</div>
	<div class="description">
		<p><?= $game->get_wraped_description() ?></p>
		<?php
		echo '<div class="tweet-btn-box">';
		$text = $game->get_full_title(TRUE);
		sharebtn_twitter($text, $game->get_link(), 'tweet');
		echo '</div>';
		?>
	</div>
	<div class="row">
		<div class="col-md-2">
			<div class="category"><?= $game->get_category_tag() ?></div>
		</div>
		<div class="col-md-10">
			<div class="tag-box">
				<?php
				foreach ($game->tag_list as $tag) {
					echo '<div class="tag">' . wrap_taglink_only($tag, FALSE) . '</div>';
				}
				?>
			</div>
		</div>
	</div>
</div>