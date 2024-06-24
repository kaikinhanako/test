<?php

class Tag extends CI_Controller {

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
		$this->main();
	}

	public function main($tage, $page_index = 0) {
		$tag = new Tagobj();
		$tag->text = urldecode($tage);
		$games = $this->game->get_games_from_tag($tag, NUM_GAME_PAR_SEARCHPAGE, $page_index * NUM_GAME_PAR_SEARCHPAGE);
		$user = $this->user->get_main_user();

		$meta = new Metaobj();
		$meta->set_title('タグ検索 - ' . $tag);
		$meta->description = '言えるかなのタグ絞り込み';
		$meta->no_meta = TRUE;

		$messages = array();
		if (($posted = $this->session->userdata('alert'))) {
			$this->session->unset_userdata('alert');
			$messages[] = $posted;
		}

		$this->load->view('head', array('meta' => $meta));
		$this->load->view('bodywrapper_head');
		$this->load->view('navbar', array('user' => $user));
		$this->load->view('container_head');
		$this->load->view('breadcrumbs', array('list' => array('TOP' => base_url(), 'タグ検索 - 言えるかな' => TRUE)));
		$this->load->view('title', array('title' => $meta->get_title()));
		$this->load->view('alert', array('messages' => $messages));
		$this->load->view('listpage', array('games' => $games, 'page_index' => $page_index, 'q' => $tag, 'is_tag' => TRUE));
		$this->load->view('container_foot');
		$this->load->view('bodywrapper_foot');
		$this->load->view('footer');
		$this->load->view('foot');
	}

}
