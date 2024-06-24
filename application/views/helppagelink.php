<h3 class="sub-title">相互リンク</h3>
<p>当サイトは相互リンクを募集しております</p>
<table class="table">
	<thead>
		<tr>
			<th>カテゴリ</th>
			<th>RSS URL</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$category_lib = unserialize(GAME_CATEGORY_MAP);
		$en_lib = unserialize(GAME_CATEGORY_EN_MAP);
		$en_lib[GAME_CATEGORY_ALL] = 'new';
		foreach ($category_lib as $code => $str) {
			$rss_link = base_url(PATH_RSS . $en_lib[$code]);
			echo '<tr> <td>'.$str.'</td> <td><a href="' .$rss_link . '">' . tag_icon_fa('rss') . '</a></td> </tr>';
		}
		?>
		
	</tbody>
</table>