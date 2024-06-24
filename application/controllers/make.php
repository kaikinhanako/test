<?php

class Make extends CI_Controller {

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
		$user = $this->user->get_main_user();

		$meta = new Metaobj();
		$meta->setup_make();
		$this->load->view('head', array('meta' => $meta));
		$this->load->view('bodywrapper_head');
		$this->load->view('navbar', array('user' => $user));
		$this->load->view('container_head');
		$this->load->view('breadcrumbs', array('list' => array('TOP' => base_url(), '作成 - 言えるかな' => TRUE)));
		$this->load->view('title', array('title' => $meta->get_title()));
		$this->load->view('makepage', array('user' => $user));
		$this->load->view('container_foot');
		$this->load->view('bodywrapper_foot');
		$this->load->view('footer');
		$this->load->view('foot');
	}

	public function post() {
		$user = $this->user->get_main_user();
		$post = $this->input->post();
		$name = $post['game_name'];
		$can_use = $this->game->check_gamename_can_regist($name);
		if (!$can_use) {
			echo 'e:1';
			exit;
		}
		$game = new Gameobj();
		$game->name = $name;
		$game->description = $post['game_description'];
		if ($post['game_tags']) {
			foreach (explode(',', $post['game_tags']) as $text) {
				$tag = new Tagobj();
				$tag->text = $text;
				$game->tag_list[] = $tag;
			}
		}
		$game->user_id = $user->id_user;
		$game->word_unit = $post['words_unit'];
		$game->category = $post['game_category'];
		foreach (explode(',', $post['words_list_text']) as $word_text) {
			$word = new Wordobj();
			$word->text = $word_text;
			$game->word_list[] = $word;
		}
		$game->words_num = count($game->word_list);
		$game_id = $this->game->regist_game($game);
		$this->session->set_userdata('alert', '新しい言えるかなを作成しました！管理はこのページの最下部を見てください'); // outpput plain text
		echo 's:' . $game_id;
	}

	public function check() {
		$post = $this->input->post();
		$name = $post['name'];
		if ($this->game->check_gamename_can_regist($name)) {
			echo 's';
		} else {
			echo 'e';
		}
		exit;
	}

	public function update($game_id) {
		$user = $this->user->get_main_user();
		$game = $this->game->get_game($game_id);
		if (empty($user) || empty($game) || $user->id_user != $game->user_id) {
			// TODO: error処理
			jump(base_url());
		}

		$meta = new Metaobj();
		$meta->setup_update();
		$this->load->view('head', array('meta' => $meta, 'user' => $user));
		$this->load->view('bodywrapper_head');
		$this->load->view('navbar', array('user' => $user));
		$this->load->view('container_head');
		$this->load->view('breadcrumbs', array('list' => array('TOP' => base_url(), '変更 - 言えるかな' => TRUE)));
		$this->load->view('breadcrumbs', array('list' => array('TOP' => base_url(), 'ゲーム' => base_url(PATH_SEARCH), $game->get_full_title() => $game->get_link(), '変更' => TRUE)));
		$this->load->view('title', array('title' => $meta->get_title()));
		$this->load->view('makepage', array('user' => $user, 'game' => $game));
		$this->load->view('container_foot');
		$this->load->view('bodywrapper_foot');
		$this->load->view('footer');
		$this->load->view('foot');
	}

	public function tag_check() {
		$tags = explode(',', $this->input->post('tags_text'));
		$res = array();
		foreach ($tags as $tag_text) {
			$res[] = $this->game->get_tag_count($tag_text);
		}
		echo implode(',', $res);
	}

	public function update_post($game_id) {
		$user = $this->user->get_main_user();
		$game = $this->game->get_game($game_id);
		$post = $this->input->post();
		if (empty($user) || empty($game) || $user->id_user != $game->user_id || empty($post)) {
			// TODO: error処理
			jump(base_url());
		}
		$game->description = $post['game_description'];
		$game->word_unit = $post['words_unit'];
		$game->category = $post['game_category'];
		if ($post['game_tags']) {
			foreach (explode(',', $post['game_tags']) as $text) {
				$tag = new Tagobj();
				$tag->text = $text;
				$game->tag_list[] = $tag;
			}
		}
		$word_list_tmp = $game->word_list;
		$game->word_list = array();
		for ($i = 0; $i < 1024; $i++) {
			if (!$text = $post['word-' . $i]) {
				continue;
			}
			$word = new Wordobj();
			$word->text = $text;
			foreach($word_list_tmp as $key => $wordtmp) {
				// point 受け継ぎ
				if ($wordtmp->text == $word->text) {
					$word->point_negative = $wordtmp->point_negative;
					$word->point_positive = $wordtmp->point_positive;
					unset($word_list_tmp[$key]);
				}
			}
			$game->word_list[] = $word;
		}
		$game->words_num = count($game->word_list);
		$this->game->update_game($game);
		$this->session->set_userdata('alert', $game->get_full_title() . 'を変更しました!');
		jump(base_url(PATH_GAME . $game_id));
	}

}
