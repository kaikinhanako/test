<?php
/* @var $game Gameobj */
?>
<div id="owner-panel">
	あなたはこの言えるかな？の作成者です
	<a class="btn btn-success" href="<?= base_url(PATH_UPDATE . $game->id) ?>">変更する</a>
	<input class="btn btn-danger" data-toggle="modal" data-target="#modal-delete" type="button" id="submit-delete-game" value="この言えるかな？を削除する" />
</div>

<div class="modal fade" id="modal-delete">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<p class="modal-title">
					作者の技術では復元出来ませんが<br />
					本当に削除してよろしいですか？
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
				<a type="button" href="<?= base_url(PATH_DELETE . $game->id) ?>" class="btn btn-danger">削除</a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->