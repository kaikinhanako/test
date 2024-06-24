<?php
/* @var $game Gameobj */
?>
<div class="row">
	<div class="col-xs-12 visible-xs">
		<div class="alerts-div">
			<div class="alert alert-dismissable alert-warning">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<p>単語数の多い言えるかな？の場合<br />PC環境での作成をおすすめです</p>
			</div>
		</div>
	</div>
</div>
<form id="make-form" class="form-horizontal" method="POST" action="<?= base_url($game ? PATH_UPDATE_POST . $game->id : PATH_MAKE_POST) ?>">
	<fieldset>
		<!--<legend>Legend</legend>-->
		<div class="form-group">
			<label for="input_game_name" class="col-md-2 control-label">タイトル</label>
			<div class="col-md-10">
				<input class="" <?= $game ? 'disabled=""' : '' ?> id="input_game_name" name="game_name" value="<?= $game ? $game->name : '' ?>" placeholder="炎ポケモン" type="text" maxlength="20">
				<span id="num">&nbsp;&nbsp;<?= $game ? $game->words_num : '0' ?></span>
				<input class="" id="input_words_unit" name="words_unit" value="<?= $game ? $game->word_unit : '個' ?>" type="text" maxlength="5">
				言えるかな？
				<span id="check-name"></span>
				<span class="help-block">最大20文字</span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_description" class="col-md-2 control-label">追加説明文</label>
			<div class="col-md-10">
				<input class="form-control" id="input_description" name="game_description" value="<?= $game ? $game->description : '' ?>" placeholder="ex. 初代151匹の中で炎タイプをもつポケモンを答えてください" type="text" maxlength="50">
				<span class="help-block">最大50文字</span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_category" class="col-md-2 control-label">カテゴリー</label>
			<div class="col-md-4">
				<select multiple="" class="form-control" id="input_category" name="game_category">
					<?php
					$category = @$game->category ?: 0;
					for ($i = 0; $i < GAME_CATEGORY_NUM; $i++) {
						echo '<option value="' . $i . '"' . ($category == $i ? 'selected' : '') . '>' . Gameobj::to_category_str($i) . '</option>';
					}
					?>
				</select>
				<span class="help-block">最大50文字</span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_tags" class="col-md-2 control-label">タグ付け</label>
			<div class="col-md-10">
				<input class="form-control" id="input_tags" name="game_tags" value="<?= $game ? implode(',', $game->tag_list) : '' ?>" placeholder="ポケモン,キャラ" type="text" maxlength="20">
				<div id="tag-check">---</div>
				<span class="help-block">,(カンマ)区切りで羅列してください.タグは無くても可</span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_add" class="col-md-2 control-label">ワードリスト</label>
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-7 input-add-box">
						<textarea name="" id="input_add" cols="60" rows="3">
						</textarea>
						<div class="help-div">
							<span class="help-block">上のフォームに単語入力して一括追加できます</span>
							<span class="help-block">単語は20文字以内ですが全角文字の場合<b>10文字以内</b>推奨です</span>
							<span class="help-block">最大1024単語まで,区切り文字の記号は使用できません</span>
							<span class="help-block">下のリストを直接変更することも出来ます</span>
						</div>
						<div class="btn-group pull-right">
							<input class="btn btn-danger" id="submit-clear" name="" maxlength="20" type="button" value="全消去" />
							<input class="btn btn-primary" id="submit-add" name="" maxlength="20" type="button" value="追加" />
						</div>
					</div>
					<div class="col-md-2">
						<div class="checkbox">
							区切り文字指定
							<label>
								<input id="checkbox-split-comma" type="checkbox" checked="">カンマ(,)
							</label>
							<label>
								<input id="checkbox-split-return" type="checkbox" checked="">改行
							</label>
							<label>
								<input id="checkbox-split-space" type="checkbox" checked="">スペース
							</label>
							<label>
								<input id="checkbox-split-tab" type="checkbox" checked="">タブスペース
							</label>
						</div>
					</div>
					<!--<div class="col-md-8">
						<p class="">
							単語は20文字以内ですが全角文字の場合<b>10文字以内</b>推奨です.最大1024単語まで<br />
							記号(改行やスペースなど)は使えません.<br />
							右のフォームでカンマ,またはスペース区切りで<b>複数同時</b>に追加できます.<br />
							下のボックスに直接編集することも出来ます.<br />
						</p>
					</div>-->
				</div>
			</div>
			<div class="col-md-12" id="word-list-box">
				<?php
				$w = 8;
				$h = 1024 / 8;
				$f = TRUE;
				for ($i = 0; $i < $h; $i++) {
					/* @var $word Wordobj */
					if ($i == 16) {
						echo '<button type="button" class="btn btn-block btn-default margin-v" data-toggle="collapse" data-target="#more-word-box">表示(129 ~)</button>';
						echo '<div id="more-word-box" class="collapse">';
					}
					echo '<div class="row row-word">';
					for ($j = 0; $j < $w; $j++) {
						$k = $i * $w + $j;
						$word = NULL;
						if ($game && $f) {
							list($key, $word) = each($game->word_list);
							$f = isset($word);
						}
						?>
						<div class="col-md-1">
							<input class="wordbox" id="input_word<?= $k ?>" name="word-<?= "" + $k ?>" maxlength="20" value="<?= $word ? $word->text : '' ?>" placeholder="---" type="text">
							<button class="delete-btn btn btn-default" style="display: none">×</button>
						</div>
						<?php
					}
					echo '</div>';
				}
				echo '</div>';
				?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-10 col-md-2">
				<!--<button id="check-btn" type="button" class="btn btn-default">チェック</button>-->
				<?php if ($game) { ?>
					<input type="hidden" id="words-text-box" name="words_list_text" />
					<button id="update-btn" type="button" class="btn btn-block btn-primary">変更</button>
				<?php } else { ?>
					<button id="submit-btn" type="button" class="btn btn-block btn-primary">作成</button>
<?php } ?>
			</div>
		</div>
	</fieldset>
</form>