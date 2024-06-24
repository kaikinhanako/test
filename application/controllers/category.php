<?php

class Category extends CI_Controller {

	/** @var User_model */
	public $user;

	/** @var Game_model */
	public $game;

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Game_model', 'game', TRUE);
	}

	public function index() {
		jump(base_url());
	}

	public function view($category) {
		$user = $this->user->get_main_user($category);
		$games_hot = $this->game->get_games_hot($category);
		$games_new = $this->game->get_games_new($category);
		$games_recent = $this->game->get_games_recent($category);

		$tags = $this->game->get_hot_tags($category);

		$messages = array();
		if (($posted = $this->session->userdata('alert'))) {
			$this->session->unset_userdata('alert');
			$messages[] = $posted;
		}

		$lib = unserialize(GAME_CATEGORY_EN_MAP);
		$meta = new Metaobj();
		$meta->setup_category($category);
		$this->load->view('head', array('meta' => $meta, 'rss_link' => Gameobj::to_category_link($category)));
		$this->load->view('bodywrapper_head');
		$this->load->view('navbar', array('user' => $user));
		$this->load->view('container_head');
		$breaadcrumbs_param = array(
			'list' => array(
				'TOP' => base_url(),
				'カテゴリ' => base_url(),
				Gameobj::to_category_str($category) => TRUE
			),
			'rss_link' => base_url(PATH_RSS_CATEGORY . $lib[$category]),
		);
		$this->load->view('breadcrumbs', $breaadcrumbs_param);
		$this->load->view('title', array('title' => $meta->get_title()));
		$this->load->view('alert', array('messages' => $messages));
		$this->load->view('categorypage', array('games_hot' => $games_hot, 'games_new' => $games_new, 'games_recent' => $games_recent, 'tags' => $tags, 'category' => $category));
		$this->load->view('container_foot');
		$this->load->view('bodywrapper_foot');
		$this->load->view('footer');
		$this->load->view('foot');
	}

}
