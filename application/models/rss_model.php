<?php

class Rss_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function get_feeds($category = GAME_CATEGORY_ALL) {
		$urls = $this->get_feed_urls($category);
		$all_items = array();
		foreach ($urls as $url) {
			$items = $this->read_items($url);
			$all_items = array_merge($all_items, $items);
		}
		return array_rand_values($all_items, 5);
	}

	public function read_items($xml_url) {
		$rss = simplexml_load_file($xml_url);
		$items = array();
		if (isset($rss->channel)) {
			$rss = $rss->channel;
		}
		foreach ($rss->item as $item) {
			$i = new Itemobj();
			$i->set_xmlitem($item);
			$items[] = $i;
		}
		return $items;
	}

	public function get_feed_urls($category) {
		$lib = array(
			GAME_CATEGORY_ENTER => array(
				'http://nyan.eggtree.net/category/sell/rss.xml',
			),
			GAME_CATEGORY_SCIENCE => array(
				'http://nyan.eggtree.net/category/tg/rss.xml',
			),
			GAME_CATEGORY_ARTS => array(
			),
			GAME_CATEGORY_ANIME => array(
			),
			GAME_CATEGORY_SPORTS => array(
			),
			GAME_CATEGORY_OTHER => array(
				'http://nyan.eggtree.net/category/etc/rss.xml',
			),
		);
		// TODO: by the category
		$all_lib = array_flatten($lib);
		return $all_lib;
	}

}
