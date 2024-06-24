<?php

class Index extends CI_Controller {

	/** @var User_model */
	public $user;

	/** @var Game_model */
	public $game;

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Game_model', 'game', TRUE);
	}

	/**
	 * 
	 * @param Gameobj[] $games
	 */
	public function index() {
		$user = $this->user->get_main_user();
		$games_hot = $this->game->get_games_hot();
		$games_new = $this->game->get_games_new();
		$games_recent = $this->game->get_games_recent();
		$tags = $this->game->get_hot_tags();

		$messages = array();
		if (($posted = $this->session->userdata('alert'))) {
			$this->session->unset_userdata('alert');
			$messages[] = $posted;
		}

		$meta = new Metaobj();
		$meta->setup_top();
		$this->load->view('head', array('meta' => $meta, 'rss_link' => base_url(PATH_RSS_CATEGORY . 'new')));
		$this->load->view('bodywrapper_head');
		$this->load->view('navbar', array('user' => $user));
		$this->load->view('container_head');
		$this->load->view('alert', array('messages' => $messages));
		$this->load->view('toppage', array('games_hot' => $games_hot, 'games_new' => $games_new, 'games_recent' => $games_recent, 'tags' => $tags));
		$this->load->view('container_foot');
		$this->load->view('bodywrapper_foot');
		$this->load->view('footer');
		$this->load->view('foot');
	}

	private function _dump_games(array $games) {
		echo '>> dump games [' . count($games) . ']' . PHP_EOL;
		foreach ($games as $g) {
			echo sprintf("%4d: %s, \n", $g->id, $g->name);
		}
	}

}
