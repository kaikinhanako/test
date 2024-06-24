<?php
/* @var $logs Logobj[] */
/* @var $col int */
/* @var $word_num int */
/* @var $is_login bool */
?>

<?php
if (isset($col)) {
	echo '<div class="col-md-' . $col . '">';
}
?>
<div class = "plate plate-tab">
	<span class = "sub-title">
		履歴
	</span>
</div>
<div class="plate plate-wide">
	<?php if ($is_login) { ?>
		<?php
		$logs = array_reverse($logs);
		if (isset($logs[0])) {
			echo '<table>';
			echo '<tr class="log ' . ($logs[0]->point == $word_num ? 'time' : 'point') . '">';
			echo '<td class="title">前回の記録</td>';
			echo '<td><span class="maxpiont">' . $logs[0]->point . '</span>';
			echo '/' . '<span class="point">' . $word_num . '</span></td>';
			echo '<td class="time">' . time_to_str_ms($logs[0]->time) . '</td>';
			echo '</tr>';

			echo '<tr class="log ' . ($logs[0]->point == $word_num ? 'time' : 'point') . '">';
			echo '<td class="title">ベストレコード</td>';
			echo '<td><span class="maxpiont">' . $logs['best']->point . '</span>';
			echo '/' . '<span class="point">' . $word_num . '</span></td>';
			echo '<td class="time">' . time_to_str_ms($logs['best']->time) . '</td>';
			echo '</tr>';
			echo '</table>';
		} else {
			echo '<p>前回の成績 : なし</p>';
		}
		?>
		<!--
		<svg height="110px" width="100%">	
		<?php
		$max = 0;
		foreach ($logs as $log) {
			$max = max($log->point, $max);
		}
		foreach ($logs as $i => $log) {
			$ph = $log->point / $max;
			$mh = 90;
			$x = $i * 30;
			$h = $mh * $ph;
			$y = $mh - $h;
			$w = 20;
			echo '<rect class="rect-test" x="' . $x . 'px" y="' . $y . '%" height="' . $h . '%" width="' . $w . 'px" fill="orange" />';
			echo '<text x="' . ($x + 12) . '" y="' . ($mh + 20) . '" font-size="10px">' . $log->point . '</text>';
		}
		?>
		</svg>
		-->
	<?php } else { ?>
		ログインしていません
		<?php
	}
	?> </div>
<?php
if (isset($col)) {
	echo '</div>';
}