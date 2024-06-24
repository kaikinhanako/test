<?php
/* @var $game Gameobj */
/* @var $is_owner bool */
/* @var $gamemode string */
/* @var $games_tag Gameobj[] */
/* @var $feed_items Itemobj[] */
/* @var $logs Logobj[] */

/**
 * 
 * @param string $str
 * @return string
 */
function to_ans_check($str) {
	return preg_replace(
		array('#[・ ()（）「」/]#u', '#[ー-]#u', '#([+]|たす|タス|プラス|ぷらす)#', '#\.#', '/#/', '#[?？]#u', '#[！!]#u', '#>#', '#<#', '#([&＆]|アンド|あんど)#u'), array('', '__h', '__p', '__d', '__s', '__q', '__e', '__l', '__r', '__a'), strtolower(mb_convert_kana(mb_convert_kana($str, 'asKVc', 'utf8'), 'c', 'utf8')));
}

function to_valuetext($text, $gamemode) {
	switch ($gamemode) {
		case GAME_MODE_EASY:
			return strtosilhouette($text);
		case GAME_MODE_SO_EASY:
			return strtosilhouette($text, TRUE);
		case GAME_MODE_TYPING:
			return $text;
		default:
			break;
	}
	return '';
}

function strtosilhouette($str, $head_view = FALSE) {
	$strs = mbStringToArray($str);
	$silhouette = '';
	$lib = 'ぁぃぅぇぉっゃゅょゎァィゥェォッャュョヮ.。、';
	foreach ($strs as $i => $c) {
		if ($i == 0 && $head_view && count($strs) != 1) {
			$silhouette .= $c;
			continue;
		}
		if (mb_strpos($lib, $c) !== FALSE) {
			$silhouette .= 'o';
			continue;
		}
		if (is_half_char($c)) {
			$silhouette .= 'O';
			continue;
		}
		$silhouette .= '◯';
	}
	return $silhouette;
}

function is_half_char($str) {
	return strlen($str) === mb_strlen($str);
}

function mbStringToArray($sStr, $sEnc = 'UTF-8') {
	$aRes = array();
	while ($iLen = mb_strlen($sStr, $sEnc)) {
		array_push($aRes, mb_substr($sStr, 0, 1, $sEnc));
		$sStr = mb_substr($sStr, 1, $iLen, $sEnc);
	}
	return $aRes;
}
?>
<?php if ($game->words_num > 32) { ?>
	<div class="row">
		<div class="col-xs-12 visible-xs">
			<div class="alerts-div">
				<div class="alert alert-dismissable alert-warning">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<p>ワード数30を超える言えるかなはPCでのプレイをおすすめしています</p>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<div class="content">
	<?php
	$this->load->view('gameinfo', array("game" => $game, 'page' => 'game', 'gamemode' => $gamemode));
	?>
	<div class="row recommend-top">
		<?php
		$this->load->view('listparts', array('items' => $games_tag, 'title' => 'おすすめ言えるかな', 'col' => 6, 'num' => 5));
//		$this->load->view('listparts', array('items' => $feed_items, 'title' => '記事紹介', 'col' => 6, 'num' => 5));
		$this->load->view('loggraphparts', array('logs' => $logs, 'col' => 6, 'is_login' => !!$user, 'word_num' => $game->words_num));
		?>
	</div>
	<div class="game-container">
		<div class="control-box">
			<div class="row game-mode-box">
				<div class="col-xs-12">
					<span href="#" class="ruby-btn">ゲームモード</span>
				</div>
				<div class="col-xs-12">
					<div class="btn-group">
						<a href="<?= $game->get_link() ?>" data-func="end" class="btn btn-default<?= $gamemode == GAME_MODE_NORMAL ? ' active disabled' : '' ?>">ノーマル</a>
						<a href="<?= $game->get_link('?easy') ?>" data-func="end" class="btn btn-default<?= $gamemode == GAME_MODE_EASY ? ' active disabled' : '' ?>">やさしい</a>
						<a href="<?= $game->get_link('?soeasy') ?>" data-func="end" class="btn btn-default<?= $gamemode == GAME_MODE_SO_EASY ? ' active disabled' : '' ?>">超やさしい</a>
						<a href="<?= $game->get_link('?typing') ?>" data-func="end" class="btn btn-default<?= $gamemode == GAME_MODE_TYPING ? ' active disabled' : '' ?>">タイピング</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="input-group timer-set" disabled="">
						<span class="input-group-btn">
							<button id="timer-toggle-btn" class="btn btn-xs btn-default" type="button" data-toggle="button">
								<i class="glyphicon glyphicon-time"></i>セットする
							</button>
						</span>
						<input id="timer-input" type="number" class="form-control" value="3" disabled="">
						<span class="input-group-addon">分</span>
					</div><!-- /input-group -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="timer plate plate-plain">
						<div id="time-box">00:00:00.00</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="counter plate plate-plain">
						<span id="process_count">0</span>
						/
						<span id="process-all"><?= $game->get_words_num() ?></span>
					</div>
				</div>
				<div class="col-md-7">
					<div class="control-forms">
						<div class="action-box">
							<span style="display: none" class="judge judge-ok">○</span>
							<span style="display: none" class="judge judge-ng">×</span>
							<span style="display: none" class="judge judge-already">済</span>
						</div>
						<input id="answer-form" class="form-control" type="text" placeholder="解答欄" />
						<input class="btn btn-primary" id="submit-answer" type="button" value="答える" />
						<input class="btn btn-primary" id="submit-start" type="button" value="スタート" />
						<input class="btn btn-danger" id="submit-end" type="button" value="降参する" />
						<input class="btn btn-success" id="submit-tweet" type="button" value="結果ツイート" />
					</div>
				</div>
			</div>
		</div>
		<div class="words-box">
			<table class="table table-words table-<?= $gamemode ?>">
				<?php
				$i = 0;
				$p = ($game->get_words_num() <= 32) ? 4 : 8;
				foreach ($game->word_list as $word) {
					if ($i % $p == 0) {
						if ($i != 0) {
							echo '</tr>';
						}
						echo '<tr>';
					}
					$value = to_valuetext($word->text, $gamemode);
					echo '<td nid="' . $word->id . '" ans="' . $word->text . '" ansc="' . to_ans_check($word->text) . '" ansf="' . $value . '">' . $value . '</td>';
					$i++;
				}
				while ($i % $p != 0) {
					$i++;
					echo '<td class="emp"></td>';
				}
				echo '</tr>';
				?>
			</table>
		</div>
	</div>
	<input type="hidden" id="game-id" value="<?= $game->id ?>" />
	<input type="hidden" id="game-name" value="<?= $game->name ?>" />
	<input type="hidden" id="word-unit" value="<?= $game->word_unit ?>" />
	<input type="hidden" id="game-mode" value="<?= $gamemode ?>" />
</div>