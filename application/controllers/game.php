<?php

class Game extends CI_Controller {

	/** @var User_model */
	public $user;

	/** @var Game_model */
	public $game;

	/** @var Rss_model */
	public $rss;

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Game_model', 'game', TRUE);
		$this->load->model('Rss_model', 'rss', TRUE);
	}

	public function index() {
		
	}

	public function test() {
		$url = 'http://ssimas.blog.fc2.com/?xml';
	}

	public function play($game_id, $rank = NULL) {
		if ($rank == "rank") {
			$this->rank($game_id);
		}
		$user = $this->user->get_main_user();
		$game = $this->game->get_game($game_id, @$user->id_user);

		// gamemode
		$get = $this->input->get();
		$gamemode = GAME_MODE_NORMAL;
		if (isset($get['easy'])) {
			$gamemode = GAME_MODE_EASY;
		} elseif (isset($get['soeasy'])) {
			$gamemode = GAME_MODE_SO_EASY;
		} elseif (isset($get['typing'])) {
			$gamemode = GAME_MODE_TYPING;
		}

		$messages = array();
		if (($posted = $this->session->userdata('alert'))) {
			$this->session->unset_userdata('alert');
			$messages[] = $posted;
		}

		if (empty($game)) {
			$this->session->set_userdata('alert', 'お探しの言えるかな？は存在しません。消去された可能性があります');
			jump(base_url());
		}

		$is_owner = isset($user) && $user->id_user == $game->user_id;
		$logs = array();
		if ($user) {
			$logs = $this->game->get_logs($user->id_user, $game->id);
			$logs['best'] = $this->game->get_best_log($user->id_user, $game->id);
		}

		$games_hot = $this->game->get_games_hot();
		$games_new = $this->game->get_games_new();
		$games_tag = array();
		if ($game->tag_list) {
			$games_tag_tmp = $this->game->get_games_from_tags($game->tag_list, 5, 0, DB_CN_GAMES_UPDATED_AT);
			shuffle($games_tag_tmp);
			$games_tag = array_slice($games_tag_tmp, 0, 5);
			if (count($games_tag) < 5) {
				$games_tag = array_merge($games_tag, $this->game->get_games_recent(NULL, 5 - count($games_tag)));
			}
		} else {
			$games_tag = $this->game->get_games_recent();
		}

//		$feed_items = $this->rss->get_feeds($game->category);

		$meta = new Metaobj();
		$meta->setup_game($game);
		$this->load->view('head', array('meta' => $meta));
		$this->load->view('bodywrapper_head');
		$this->load->view('navbar', array('user' => $user));
		$this->load->view('container_head');
		$breaadcrumbs_param = array(
			'list' => array(
				'TOP' => base_url(),
				$game->get_category_str() => $game->get_category_link(),
				$game->get_full_title() => TRUE
			),
		);
		$this->load->view('breadcrumbs', $breaadcrumbs_param);
		$this->load->view('alert', array('messages' => $messages));
		$gamepage_param = array(
			'game' => $game,
			'is_owner' => $is_owner,
			'gamemode' => $gamemode,
			'games_tag' => $games_tag,
//			'feed_items' => $feed_items,
			'logs' => $logs,
		);
		$this->load->view('gamepage', $gamepage_param);
		$this->load->view('listparts_head');
		$this->load->view('listparts', array('items' => $games_tag, 'col' => 4, 'title' => 'おすすめ'));
		$this->load->view('listparts', array('items' => $games_hot, 'col' => 4, 'title' => '人気言えるかな'));
		$this->load->view('listparts', array('items' => $games_new, 'col' => 4, 'title' => '新着言えるかな'));
		$this->load->view('listparts_foot');
		if ($is_owner) {
			$this->load->view('ownerpanel', array('game' => $game));
		}
		$this->load->view('container_foot');
		$this->load->view('bodywrapper_foot');
		$this->load->view('footer');
		$this->load->view('foot');
	}

	public function rank($game_id) {
		$user = $this->user->get_main_user();
		$game = $this->game->get_game($game_id, @$user->id_user);

		$messages = array();
		if (($posted = $this->session->userdata('alert'))) {
			$this->session->unset_userdata('alert');
			$messages[] = $posted;
		}

		if (empty($game)) {
			$this->session->set_userdata('alert', 'お探しの言えるかな？は存在しません。消去された可能性があります');
			jump(base_url());
		}
		$is_owner = isset($user) && $user->id_user == $game->user_id;

		$meta = new Metaobj();
		$meta->setup_game($game);
		$this->load->view('head', array('meta' => $meta));
		$this->load->view('bodywrapper_head');
		$this->load->view('navbar', array('user' => $user));
		$this->load->view('container_head');
		$this->load->view('breadcrumbs', array('list' => array('TOP' => base_url(), 'ゲーム' => base_url(PATH_SEARCH), $game->get_full_title() => $game->get_link(), '単語ランキング' => TRUE)));
		$this->load->view('alert', array('messages' => $messages));
		$this->load->view('rankpage', array('game' => $game, 'is_owner' => $is_owner));

		$this->load->view('container_foot');
		$this->load->view('bodywrapper_foot');
		$this->load->view('footer');
		$this->load->view('foot');
	}

	private function _no_game($user) {
		$meta = new Metaobj();
		$meta->no_meta = TRUE;
		$meta->set_title("言えるかな？がみつかりません");
		$meta->description = "この言えるかなは存在しません";
		$this->load->view('head', array('meta' => $meta));
		$this->load->view('bodywrapper_head');
		$this->load->view('navbar', array('user' => $user));
		$this->load->view('title', array('title' => $meta->get_title()));
		$this->load->view('nogamepage');
		$this->load->view('bodywrapper_foot');
		$this->load->view('footer');
		$this->load->view('foot');
	}

	public function result($game_id = NULL) {
		$post = $this->input->post();
		$active_points = array();
		$negative_points = array();
		if (($ids = $post['start_ids'])) {
			$active_points = explode(',', $ids);
		}
		if (($ids = $post['ng_ids'])) {
			$negative_points = explode(',', $ids);
		}
		$time = $post['time'];
		$is_typing = $post['is_typing'];
		if ($this->agent->is_referral()) {
			$game_id_check = Game::get_game_id($this->agent->referrer());
		}
		if (is_null($game_id) || is_null($game_id_check) || $game_id != $game_id_check) {
			echo 'e:0';
			return;
		}
		if (!$is_typing) {
			$this->game->increment_play_count($game_id);
			$this->game->log_points($game_id, $active_points, $negative_points);
		}
		$user = $this->user->get_main_user();
		if (($user)) {
			$log = new Logobj();
			$log->game_id = $game_id;
			$log->user_id = $user->id_user;
			$log->time = $time;
			$log->point = count($active_points);
			$this->game->regist_log($log);
		}

		$this->game->close();
		echo "result logged!";
	}

	public function delete($game_id) {
		$user = $this->user->get_main_user();
		$game = $this->game->get_game($game_id);
		if (empty($user) || empty($game) || $user->id_user != $game->user_id) {
			// TODO: error処理
			jump(base_url());
		}
		$this->game->remove_game($game_id);
		$this->session->set_userdata('alert', '「' . $game->get_full_title() . '」を削除しました');
		jump(base_url());
	}

	public function favorite($game_id) {
		$user = $this->user->get_main_user();
		$game = $this->game->get_game($game_id);
		$is_regist = $this->input->post('is_regist');
		if (empty($user) || empty($game) || $user->id_user != $game->user_id || !isset($is_regist)) {
			// TODO: error処理
			die("e:0");
		}
		$this->game->toggle_favorite($user->id_user, $game_id, $is_regist);
		die("s:1");
	}

	public static function get_game_id($url) {
		preg_match('#.*/(?<id>[0-9]+)\??.*#', $url, $m);
		return $m['id'];
	}

}
