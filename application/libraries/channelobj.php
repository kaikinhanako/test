<?php

class Channelobj {

	public $title;
	public $link;
	public $description;
	public $generator;
	public $web_master;
	public $category;

	/**
	 *
	 * @var Itemobj[]
	 */
	public $items;

	public function __construct($title = NULL, $link = NULL, $description = NULL) {
		if (isset($title)) {
			$this->title = $title;
		}
		if (isset($link)) {
			$this->link = $link;
		}
		if (isset($description)) {
			$this->description = $description;
		}
		$this->generator = AUTHOR_NAME;
		$this->web_master = AUTHOR_MAIL . ' (' . AUTHOR_NAME . ')';
	}

	public function get_atom_link() {
		return str_replace('rss', 'atom', $this->link);
	}
}
