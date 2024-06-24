<?php
/* @var $category int */
?>
<div class="nav-wrap">
	<ul class="nav nav-tabs nav-category">
		<?php
		$tab_codes = array();
		$tab_codes[] = GAME_CATEGORY_ALL;
		for ($i = 1; $i < GAME_CATEGORY_NUM; $i++) {
			$tab_codes[] = $i;
		}
		$tab_codes[] = GAME_CATEGORY_OTHER;
		foreach ($tab_codes as $code) {
			echo '<li class="' . ($code == $category ? 'active' : '' ) . '"><a href="' . Gameobj::to_category_link($code) . '" >' . Gameobj::to_category_str($code) . '</a></li>';
		}
		?>
	</ul>
</div>