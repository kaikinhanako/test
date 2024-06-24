<?php

class Tagobj {

	public $text;
	public $count;

	public function __construct($obj = NULL) {
		if (is_null($obj)) {
			return;
		}
		$this->text = $obj->{DB_CN_TAGS_TEXT};
		$this->count = $obj->{DB_CN_AS_COUNT};
	}

	public function __toString() {
		return $this->text;
	}

}
