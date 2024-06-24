<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>LoLチャンピオン言えるかな？</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="lib/i18next/i18next.min.js"></script>
	<script type="text/javascript" src="lib/jquery-i18next/jquery-i18next.min.js"></script>
	<script type="text/javascript" src="lib/i18next-xhr-backend/i18nextXHRBackend.min.js"></script>
	<script type="text/javascript" src="common/js/common.js"></script>
	<script type="text/javascript" src="js/index.js"></script>
	<script src="js/ierukana.js"></script>
	<link href="css/ierukana.css" rel="stylesheet">

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-NGGX05RQD6"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-NGGX05RQD6');
	</script>

</head>

<body style="background-color:#FEE;">
	<div class="container">

		<div id="header" class="row">
			<div class="col-md-10">
				<h2 class="text-muted">LoLチャンピオン<span class="numOfChampion"></span>人言えるかな？β</h2>
			</div>
			<div class="col-sm-3">
				<select class="form-control" id="lang">
					<option value="ja">日本語</option>
					<option value="en">English</option>
					<option value="ko">한국</option>
				</select>
			</div>
			<div class="col-sm-3">
				<a href="item.html" class="btn btn-default btn-sm" role="button">LoLアイテム言えるかな？</a>
				<a href="https://masajiro999.github.io/16pixelsquize/" class="btn btn-default btn-sm" role="button">16ピクセルLoLクイズ</a>
			</div>

			<div class="col-md-2">
				<div id="tweet-btn">
					<a href="https://twitter.com/share" class="twitter-share-button"
						data-url="https://masajiro999.github.io/ierukana/" data-text="LoLチャンピオン言えるかな" data-lang="ja"
						data-hashtags="LoLチャンピオン言えるかな">ツイート</a>
					<script>
						! function (d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
							if (!d.getElementById(id)) {
								js = d.createElement(s);
								js.id = id;
								js.src = p + '://platform.twitter.com/widgets.js';
								fjs.parentNode.insertBefore(js, fjs);
							}
						}(document, 'script', 'twitter-wjs');
					</script>
				</div>
			</div>
		</div>

		<div class="contents">

			<div id="readme" class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">遊び方</h3>
				</div>
				<div class="panel-body">
					・リーグオブレジェンドに登場するチャンピオンの名前を正式名称で答えてもらいます。<br>
					<!--・まず下の難易度を選びます。NORMALのままでもいいです。<br>-->
					・ゲーム開始ボタンを押すとカウントダウンが始まるので、0になったらチャンピオンの名前をひたすら入力します。<br>
					・チャンピオンの名前入力はエンターキーによる入力を推奨します。<br>
					・名前が頭からひねり出せなくなったら、降参ボタンを押します。<br>
				</div>
			</div>
			<div id="difficulty-show"></div>
			<div id="difficulty-select" class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">難易度選択</h3>
				</div>
				<div class="panel-body">
					<div id="radio-easy" class="radio">
						<label>
							<input id="difficulty-radio1" type="radio" name="difficulty-radio" value="easy" disabled />
							チュートリアル：チャンピオンの正式名称か英語で入力すればＯＫです。初心者サモナー向け。
						</label>
					</div>
					<div id="radio-normal" class="radio">
						<label>
							<input id="difficulty-radio2" type="radio" name="difficulty-radio" value="normal" checked />
							ノーマル：チャンピオンの正式名称を入力すると正解になります。一般的なサモナー向け。※ただし記号「・」と「＝」を除く
						</label>
					</div>
					<div id="radio-hard" class="radio">
						<label>
							<input id="difficulty-radio3" type="radio" name="difficulty-radio" value="hard" disabled />
							ランク：チャンピオン名を記号込みで入力しないと正解になりません。ランクサモナー向け。（記号「・」と「＝」を正しく入力）
						</label>
					</div>
				</div>
			</div>

			<div id="answer-area" class="row">
				<div id="timer-area">000:00:00</div>
				<div id="remain-area">残り<span id="num-of-remain">○○</span>人</div>
				<input type="text" id="answer-text" />
				<input type="button" id="answer-btn" class="btn btn-primary" value="Answer" disabled="false" />
				<input type="button" id="game-start-btn" class="btn btn-success" value="Start!" />
				<br />
				<div id="message-area">&nbsp;</div>
			</div>

			<div id="table-area">
				<table id="all-champions" class="table table-bordered">
					<thead>
						<tr id="all-header">
							<th colspan="10"><span id="version"></span> チャンピオン <span class="remain">あと○人</span></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
