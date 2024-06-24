<?php
/* @var $games Gameobj[] */
/* @var $page_index integer */
/* @var $is_tag bool */
$is_tag = !!@$is_tag;
$is_exist_next = count($games) == NUM_GAME_PAR_SEARCHPAGE + 1;
$is_nogame = count($games) == 0;
$is_exist_prev = $page_index != 0;

function tag_pager($is_exist_prev, $is_exist_next, $page_index, $q, $is_tag, $m = NULL) {
	if ($is_tag) {
		$prev_url = base_url(PATH_TAG . $q . '/' . ($page_index - 1));
		$next_url = base_url(PATH_TAG . $q . '/' . ($page_index + 1));
        echo 'is_tag';
	} else {
		if ($m) {
			$prev_url = '?m=' . $m;
		} else {
			$prev_url = ($q ? '?q=' . urlencode($q) : "");
		}
		if ($page_index != 1) {
			$prev_url .= '&n=' . ($page_index - 1);
		}
		if ($m) {
			$next_url = '?m=' . $m;
		}else  {
			$next_url = ($q ? '?q=' . urlencode($q) : "");
		}
		$next_url .= '&n=' . ($page_index + 1);
	}
	?>
	<ul class="pager">
		<li class="previous<?= $is_exist_prev ? '' : ' disabled' ?>"><a href="<?= $is_exist_prev ? $prev_url : "#" ?>">←</a></li>
		<li class="next<?= $is_exist_next ? '' : ' disabled' ?>"><a href="<?= $is_exist_next ? $next_url : "#" ?>" class="">→</a></li>
	</ul>
	<?php
}
?>
<div class="container">
	<div class="row">
		<div class="col-md-offset-2 col-md-8">
			<?php
			$this->load->view('searchform');
			tag_pager($is_exist_prev, $is_exist_next, $page_index, $q, $is_tag, $m);
			if (!$is_nogame) {
				foreach ($games as $i => $game) {
					if ($i == NUM_GAME_PAR_SEARCHPAGE) {
						break;
					}
					$i++;
					?>
					<div class="plate plate-game">
						<p class="name"><span class="index"><?= $i + $page_index * NUM_GAME_PAR_SEARCHPAGE ?></span><a href="<?= $game->get_link() ?>"><?= $game->get_full_title() ?></a></p>
						<p class="description"><?= $game->get_wraped_description() ?></p>
					</div>
					<?php
				}
				tag_pager($is_exist_prev, $is_exist_next, $page_index, $q, $is_tag, $m);
			} else {
				?>
				言えるかなは見つかりませんでした
				<?php
			}
			?>
		</div>
	</div>
</div>