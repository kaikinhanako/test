<?php

class Wordobj {

	public $id;
	public $game_id;
	public $text;
	public $point_positive;
	public $point_negative;

	public static $point_positive_maxs;
	public static $point_negative_maxs;

	public function __construct($obj = NULL) {
		$this->point_positive = 0;
		$this->point_negative = 0;
		if (is_null($obj)) {
			return;
		}
		$this->id = $obj->{DB_CN_WORDS_ID};
		$this->game_id = $obj->{DB_CN_WORDS_GAME_ID};
		$this->text = $obj->{DB_CN_WORDS_TEXT};
		$this->point_positive = $obj->{DB_CN_WORDS_POINT_POSITIVE};
		$this->point_negative = $obj->{DB_CN_WORDS_POINT_NEGATIVE};
	}

	public function get_rate_point_positive($is_x100 = FALSE) {
		if (is_null($this->point_positive) || is_null($pm = @Wordobj::$point_positive_maxs[$this->game_id])) {
			return 0;
		}
		if ($pm == 0) {
			return 0;
		}
		if ($is_x100) {
			return floor($this->point_positive * 100 / $pm);
		}
		return $this->point_positive / $pm;
	}

	public function get_rate_point_negative($is_x100 = FALSE) {
		if (is_null($this->point_negative) || is_null($pm = @Wordobj::$point_negative_maxs[$this->game_id])) {
			return 0;
		}
		if ($pm == 0) {
			return 0;
		}
		if ($is_x100) {
			return floor($this->point_negative * 100 / $pm);
		}
		return $this->point_negative / $pm;
	}

}
