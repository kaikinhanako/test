<?php

class Job extends CI_Controller {

	/** @var Game_model */
	public $game;

	public function __construct() {
		parent::__construct();
		$this->load->model('Game_model', 'game', TRUE);
	}

	public function index() {
		$keys_config = $this->config->item('CHECK_KEYS');
		$key = $this->input->get(@$keys_config['get_param']);
		if (empty($key) || $key != $keys_config['tweet_post_key']) {
			show_404();
		}
		$game = $this->game->get_game_tweet();
		$this->_post_tweet($game);
	}

	private function _post_tweet(Gameobj $game) {
		$twitter_config = $this->config->item('TWITTER_BOT');
		$connection = new TwitterOAuth($twitter_config['consumer_key'], $twitter_config['consumer_secret'], $twitter_config['token_key'], $twitter_config['token_secret']);
		$url = 'statuses/update';
		$text = $game->get_full_title() . ' ' . $game->get_link();
		$parameters = array(
			'status' => $text,
		);
		$connection->post($url, $parameters);
	}

	public function ping() {
		$keys_config = $this->config->item('CHECK_KEYS');
		$key = $this->input->get(@$keys_config['get_param']);
		if (empty($key) || $key != $keys_config['tweet_post_key']) {
			show_404();
		}
		$lib = unserialize(GAME_CATEGORY_MAP);
		foreach ($lib as $category => $category_str) {
			$title = str_replace(FORMAT_RSS_TITLE_CATEGORY_KEYWORD, $category_str, FORMAT_RSS_TITLE_CATEGORY);
			if ($category == GAME_CATEGORY_ALL) {
				$title = '言えるかな？'; 

			}
			$url = Gameobj::to_category_link($category);
			$this->_sendPings($title, $url);
		}
	}

	private function _sendPings($title, $url) {

		$ping_list = array(
			array("nyan.eggtree.net", "/rpc/ping"),
//			array("api.my.yahoo.co.jp", "/RPC2"),
//			array("blog.goo.ne.jp", "/XMLRPC"),
//			array("blog.with2.net", "/ping.php/"),
			array("blogsearch.google.co.jp", "/ping/RPC2"),
			array("blogsearch.google.com", "/ping/RPC2"),
//			array("hamham.info", "/blog/xmlrpc/"),
//			array("rpc.reader.livedoor.com", "/ping"),
//			array("ping.bloggers.jp", "/rpc/"),
//			array("ping.blogranking.net", "/"),
//			array("ping.blogoon.net", "/"),
//			array("ping.cocolog-nifty.com", "/xmlrpc"),
//			array("ping.exblog.jp", "/xmlrpc"),
//			array("ping.namaan.net", "/rpc/"),
			array("ping.fc2.com", "/"),
//			array("jugem.jp", "/?mode=NEWENTRY"),
//			array("ping.feedburner.com", "/"),
			array("ping.freeblogranking.com", "/xmlrpc/"),
//			array("ping.rootblog.com", "/rpc.php"),
//			array("www.blogmura.com", "/"),
			array("ping.rss.drecom.jp", "/"),
//			array("ping.sitecms.net", "/"),
			array("pingoo.jp", "/ping/"),
//			array("ranking.kuruten.jp", "/ping"),
//			array("rpc.blogrolling.com", "/pinger/"),
//			array("rpc.pingomatic.com", "/"),
			array("rpc.weblogs.com", "/RPC2"),
//			array("serenebach.net", "/rep.cgi"),
//			array("taichistereo.net", "/xmlrpc/"),
//			array("www.i-learn.jp", "/ping/"),
			array("xping.pubsub.com", "/ping/"),
		);
		foreach ($ping_list as $ping) {
			updatePing($ping[0], $ping[1], $title, $url) . PHP_EOL;
		}
	}

}
