<?php

class Itemobj {

	public $title;
	public $link;
	public $description;
	public $author;
	public $category;
	public $timestamp;
	public $guid;

	public function __construct() {
	}

	public function set_xmlitem(SimpleXMLElement $item) {
		$dc = $item->children('http://purl.org/dc/elements/1.1/');
		$this->title = $item->title->__toString();
		$this->link = $item->link->__toString();
		$this->description = $item->description->__toString();
		$this->timestamp = strtotime($dc->date);
	}

	public function set_game(Gameobj $game) {
		$this->title = $game->get_full_title(TRUE);
		$this->link = $game->get_link();
		$this->description = $game->get_full_title(TRUE) . 'に挑戦しよう。' .  $game->description;
		$tag_text = $game->get_tags_text();
		if ($tag_text) {
			$this->description .= '[' . $tag_text . ']';
		}
		$this->author = AUTHOR_MAIL . ' (' . AUTHOR_NAME . ')';
		$this->category = str_replace('・', ' ', $game->get_category_str());
		$this->timestamp = $game->timestamp;
		$this->guid = $game->get_guid();
	}

	/**
	 * 
	 * @param Gameobj[] $games
	 */
	public static function to_items(array $games) {
		$items = array();
		foreach ($games as $game) {
			$item = new Itemobj();
			$item->set_game($game);
			$items[] = $item;
		}
		return $items;
	}

	public function get_pub_date() {
		return date(DATE_RFC2822, $this->timestamp);
	}
	
}