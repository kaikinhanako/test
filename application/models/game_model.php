<?php

class Game_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 既に登録されている名前の確認
	 */
	public function check_gamename_can_regist($name) {
		$this->db->where(DB_CN_GAMES_NAME, $name);
		$query = $this->db->get(DB_TN_GAMES);
		$result = $query->result();
		return empty($result[0]);
	}

	/**
	 * 
	 * @param int $user_id
	 * @return array
	 */
	public function get_ids_favorited($user_id) {
		$rows = $this->_select_favorite_user($user_id);
		$ids = array();
		foreach ($rows as $row) {
			$ids[] = $row->{DB_CN_FAVORITES_GAME_ID};
		}
		return $ids;
	}

	/*
	 *  game access methods
	 * ------------------ */

	/**
	 * 新規ゲームの登録
	 * @param Gameobj $game
	 * @return int
	 */
	public function regist_game(Gameobj $game) {
		$game_id = $this->_insert_game($game);
		$this->_insert_words($game_id, $game->word_list);
		if (!empty($game->tag_list)) {
			$this->_insert_tags($game_id, $game->tag_list);
		}
		return $game_id;
	}

	/**
	 * 
	 * @param int $game_id
	 * @return null|\Gameobj
	 */
	public function get_game($game_id, $user_id = NULL) {
		if (!$game_res = $this->_select_game($game_id)) {
			return NULL;
		}
		$game = new Gameobj($game_res);
		$game->set_word_list(Game_model::to_wordobjs($this->_select_words($game_id)));
		$game->tag_list = $this->get_tags($game_id);
		if ($user_id) {
			$game->is_favorited = $this->is_favorite($user_id, $game_id);
		}
		return $game;
	}

	/**
	 * タグ複数からゲーム取得
	 * @param Tagobj[] $tags
	 * @param int $limit
	 * @param int $offset
	 * @param string $order_by
	 * @param string $order_asc
	 * @return Gameobj[]
	 */
	public function get_games_from_tags($tags, $limit, $offset = 0, $order_by = DB_CN_GAMES_PLAY_COUNT, $order_asc = 'DESC') {
		$tag_texts = array();
		foreach ($tags as $t) {
			$tag_texts[] = $t->text;
		}
		$ids = $this->_select_ids_from_tagtexts($tag_texts);
		if (empty($ids)) {
			return array();
		}
		return $this->to_gameobjs($this->_select_games_from_ids($ids, $limit, $offset, $order_by, $order_asc));
	}

	/**
	 * タグひとつからゲーム取得
	 * @param Tagobj $tag
	 * @param int $limit
	 * @param int $offset
	 * @param string $order_by
	 * @param string $order_asc
	 * @return Gameobj[]
	 */
	public function get_games_from_tag(Tagobj $tag, $limit, $offset = 0, $order_by = DB_CN_GAMES_PLAY_COUNT, $order_asc = 'DESC') {
		return $this->get_games_from_tags(array($tag), $limit, $offset, $order_by, $order_asc);
	}

	/**
	 * 
	 * @param int $user_id
	 * @param int $limit
	 * @param int $offset
	 * @return Gameobj[]
	 */
	function get_games_favorited($user_id, $limit = NUM_GAME_PAR_SEARCHPAGE, $offset = 0) {
		$ids = $this->get_ids_favorited($user_id);
		if (empty($ids)) {
			return array();
		}
		return $this->to_gameobjs($this->_select_games_from_ids($ids, $limit, $offset, DB_CN_GAMES_CREATED_AT, 'DESC'));
	}

	/**
	 * 
	 * @param int $category
	 * @param int $limit
	 * @return Gameobj[]
	 */
	public function get_games_hot($category = NULL, $limit = NUM_GAME_PAR_TOPPAGE, $is_set_words = FALSE, $is_set_tags = FALSE) {
		return $this->get_games_top_sort($category, $limit, SORT_HOT, $is_set_words, $is_set_tags);
	}

	/**
	 * 
	 * @param int $category
	 * @param int $limit
	 * @return Gameobj[]
	 */
	public function get_games_new($category = NULL, $limit = NUM_GAME_PAR_TOPPAGE, $is_set_words = FALSE, $is_set_tags = FALSE) {
		return $this->get_games_top_sort($category, $limit, SORT_NEW, $is_set_words, $is_set_tags);
	}

	/**
	 * 
	 * @param int $category
	 * @param int $limit
	 * @return Gameobj[]
	 */
	public function get_games_recent($category = NULL, $limit = NUM_GAME_PAR_TOPPAGE_RECENT, $is_set_words = TRUE, $is_set_tags = FALSE) {
		return $this->get_games_top_sort($category, $limit, SORT_RECENT, $is_set_words, $is_set_tags);
	}

	/**
	 * 
	 * @param int $category
	 * @param int $limit
	 * @param string $sort
	 * @param int $is_set_words
	 * @return Gameobj[]
	 */
	public function get_games_top_sort($category, $limit, $sort, $is_set_words = FALSE, $is_set_tags = FALSE) {
		$rows = $this->get_games(NULL, $category, NULL, $sort, $limit, 0, TRUE);
		return $this->to_gameobjs($rows, $is_set_words, $is_set_tags);
	}

	/**
	 * ユーザが作成したゲームの取得
	 * @param int $user_id
	 * @param int $limit
	 * @param int $offset
	 * @param string $q
	 * @param int $category
	 * @return Gameobj[]
	 */
	public function get_games_owner($user_id, $limit = NULL, $offset = 0, $q = NULL, $category = NULL) {
		$rows = $this->get_games($q, $category, $user_id, SORT_NEW, $limit, $offset, TRUE);
		return $this->to_gameobjs($rows, FALSE, TRUE);
	}

	/**
	 * 
	 * @param string $q
	 * 検索文字列
	 * @param int $category
	 * カテゴリコード
	 * @param int $user_id
	 * ユーザID
	 * @param string $sort
	 * ソートタイプ
	 * @param int $limit
	 * @param int $offset
	 * @param int $is_noobj
	 * Gameobjをラップしてから返すか
	 * @return Gameobj[]
	 */
	public function get_games($q = NULL, $category = NULL, $user_id = NULL, $sort = SORT_HOT, $limit = NUM_GAME_PAR_SEARCHPAGE, $offset = 0, $is_noobj = FALSE) {
		$order_asc = 'DESC';
		switch ($sort) {
			case SORT_HOT:
				$order_by = DB_CN_GAMES_PLAY_COUNT;
				break;
			case SORT_NEW:
				$order_by = DB_CN_GAMES_CREATED_AT;
				break;
			case SORT_RECENT:
				$order_by = DB_CN_GAMES_UPDATED_AT;
				break;
			default :
				return NULL;
		}
		$rows = $this->_select_games($q, $category, $user_id, $order_by, $order_asc, $limit, $offset);
		return $is_noobj ? $rows : $this->to_gameobjs($rows);
	}

	/**
	 * ツイートするゲームを一つ選ぶ
	 * @return \Gameobj
	 */
	function get_game_tweet() {
		$games = $this->get_games_recent(NULL, 1);
		return $games[0];
	}

	/**
	 * ゲームの消去
	 * @param int $game_id
	 */
	public function remove_game($game_id) {
		$this->_delete_game($game_id);
	}

	/**
	 * ゲームの更新
	 * @param Gameobj $game
	 */
	public function update_game(Gameobj $game) {
		$this->_update_game($game);
		$this->_delete_words($game->id);
		$this->_insert_words($game->id, $game->word_list);
		$this->_delete_tags($game->id);
		$this->_insert_tags($game->id, $game->tag_list);
	}

	/**
	 * ゲームのプレイカウントの更新
	 * @param int $game_id
	 */
	function increment_play_count($game_id) {
		// TODO: create
		$this->db->where(DB_CN_GAMES_ID, $game_id);
		$this->db->set(DB_CN_GAMES_PLAY_COUNT, DB_CN_GAMES_PLAY_COUNT . ' + 1', FALSE);
		$this->db->update(DB_TN_GAMES);
	}

	/**
	 * ポイント更新処理
	 * @param int $game_id
	 * @param array $active_points
	 * @param array $negative_points
	 */
	public function log_points($game_id, $active_points, $negative_points) {
		$game = $this->get_game($game_id);
		$data = Game_model::to_sql_points($game, $active_points, $negative_points);
		$this->_update_points($game_id, $data);
	}

	public function regist_log(Logobj $log) {
		$this->_insert_log($log);
		// 制限以上のログは消去
		$c = count($this->_select_log($log->user_id, $log->game_id)) - NUM_LOG_RESULT;
		if ($c > 0) {
			$this->_pop_log($log->user_id, $log->game_id, $c);
		}
		$this->_update_best_log($log);
	}

	private function _update_best_log(Logobj $log) {
		$log_best = $this->_select_best_log($log->user_id, $log->game_id);
		// ベスト記録が無い または 新記録がベストより大きい
		if (!$log_best ||
			$log->compare_to(new Logobj($log_best)) == 1) {
			$this->_delete_best_log($log->user_id, $log->game_id);
			$this->_insert_log($log, TRUE);
		}
	}

	public function get_logs($user_id, $game_id) {
		$logs = Game_model::to_logobjs($this->_select_log($user_id, $game_id));
		return $logs;
	}

	public function get_best_log($user_id, $game_id) {
		return new Logobj($this->_select_best_log($user_id, $game_id));
	}

	/**
	 * 
	 * @param array $rows
	 * @param bool $is_set_words
	 * @param bool $is_set_tags
	 * @return \Gameobj
	 */
	public function to_gameobjs($rows, $is_set_words = FALSE, $is_set_tags = FALSE) {
		$games = array();
		foreach ($rows as $row) {
			$game = new Gameobj($row);
			if ($is_set_words) {
				$game->set_word_list($this->get_words($game->id));
			}
			if ($is_set_tags) {
				$game->tag_list = $this->get_tags($game->id);
			}
			$games[] = $game;
		}
		return $games;
	}

	/**
	 * ゲームIDからワードオブジェクトを取得
	 * @param int $game_id
	 * @return Gameobj[]
	 */
	function get_words($game_id) {
		return Game_model::to_wordobjs($this->_select_words($game_id));
	}

	/**
	 * ゲームIDからタグを取得
	 * @param int $game_id
	 * @return \Tagobj
	 */
	public function get_tags($game_id) {
		$rows = $this->_select_tags($game_id);
		$tags = array();
		foreach ($rows as $row) {
			$tags[] = new Tagobj($row);
		}
		return $tags;
	}

	/**
	 * ホットなタグを取得
	 * @param int $category
	 * @param int $limit
	 * @return \Tagobj
	 */
	public function get_hot_tags($category = NULL, $limit = NUM_TAG_PAR_TOPPAGE) {
		$sql = 'SELECT distinct ' . DB_CN_TAGS_TEXT . ', count(' . DB_CN_TAGS_TEXT . ') as ' . DB_CN_AS_COUNT;
		$sql .= ' FROM ie_' . DB_TN_TAGS;
		$where = isset($category) ? ' WHERE ' . DB_CN_GAMES_CATEGORY . ' = ' . $category : '';
		$sql .= ' WHERE (' . DB_CN_TAGS_GAME_ID . ') in (select ' . DB_CN_GAMES_ID . ' from ie_' . DB_TN_GAMES . ' ' . $where . ' order by ' . DB_CN_GAMES_UPDATED_AT . ')';
		$sql .= ' group by ' . DB_CN_TAGS_TEXT;
		$sql .= ' limit ' . $limit;
		$query = $this->db->query($sql);
		$result = $query->result();
		$tags = array();
		foreach ($result as $row) {
			$tags[] = new Tagobj($row);
		}
		return $tags;
	}

	/**
	 * タグ名から登録数を取得
	 * @param string $tag_text
	 * @return int
	 */
	public function get_tag_count($tag_text) {
		$this->db->where(DB_CN_TAGS_TEXT, $tag_text);
		return $this->db->count_all_results(DB_TN_TAGS);
	}

	/**
	 * お気に入り状態を入れ替える
	 * @param int $user_id
	 * @param int $game_id
	 * @param bool $is_regist
	 * @return 
	 */
	public function toggle_favorite($user_id, $game_id, $is_regist = NULL) {
		if (!isset($is_regist)) {
			$this->toggle_favorite($user_id, $game_id, !$this->is_favorite($user_id, $game_id));
			return;
		}
		if ($is_regist) {
			$this->_insert_favorite($user_id, $game_id);
		} else {
			$this->_delete_favorite($user_id, $game_id);
		}
	}

	/*
	 * DB methods
	  --------------------- */

	/**
	 * 
	 * @param Gameobj $game
	 * @return integer
	 */
	private function _insert_game(Gameobj $game) {
		$this->db->set(DB_CN_GAMES_USER_ID, $game->user_id);
		$this->db->set(DB_CN_GAMES_NAME, $game->name);
		$this->db->set(DB_CN_GAMES_DESCRIPTION, $game->description);
		$this->db->set(DB_CN_GAMES_WORDS_UNIT, $game->word_unit);
		$this->db->set(DB_CN_GAMES_WORDS_NUM, $game->words_num);
		$this->db->set(DB_CN_GAMES_CATEGORY, $game->category);
		$this->db->set(DB_CN_GAMES_CREATED_AT, date(MYSQL_TIMESTAMP));
		$this->db->set(DB_CN_GAMES_UPDATED_AT, date(MYSQL_TIMESTAMP, strtotime('-1 day')));
		$this->db->insert(DB_TN_GAMES);
		return $this->db->insert_id();
	}

	/**
	 * 
	 * @param int $game_id
	 * @return stdobj|null
	 */
	private function _select_game($game_id) {
		$this->db->where(DB_CN_GAMES_ID, $game_id);
		$query = $this->db->get(DB_TN_GAMES);
		$result = $query->result();
		return @$result[0] ? : NULL;
	}

	private function _delete_game($game_id) {
		$this->db->where(DB_CN_GAMES_ID, $game_id);
		$this->db->delete(DB_TN_GAMES);
	}

	private function _update_game(Gameobj $game) {
		$this->db->where(DB_CN_GAMES_ID, $game->id);
		$this->db->set(DB_CN_GAMES_DESCRIPTION, $game->description);
		$this->db->set(DB_CN_GAMES_WORDS_UNIT, $game->word_unit);
		$this->db->set(DB_CN_GAMES_WORDS_NUM, $game->words_num);
		$this->db->set(DB_CN_GAMES_CATEGORY, $game->category);
		$this->db->update(DB_TN_GAMES);
	}

	/**
	 * 
	 * @param int $game_id
	 * @param Wordobj[] $word_list
	 */
	function _insert_words($game_id, array $word_list) {
		$data = array();
		foreach ($word_list as $word) {
			$data[] = array(
				DB_CN_WORDS_GAME_ID => $game_id,
				DB_CN_WORDS_TEXT => $word->text,
				DB_CN_WORDS_POINT_NEGATIVE => $word->point_negative,
				DB_CN_WORDS_POINT_POSITIVE => $word->point_positive,
			);
		}
		$this->db->insert_batch(DB_TN_WORDS, $data);
	}

	/**
	 * 
	 * @param int $game_id
	 * @param array $data
	 */
	private function _update_points($game_id, $data) {
		$this->db->where(DB_CN_WORDS_GAME_ID, $game_id);
		$this->db->update_batch(DB_TN_WORDS, $data, DB_CN_WORDS_ID);
	}

	/**
	 * 
	 * @param int $game_id
	 * @return array
	 */
	private function _select_words($game_id) {
		$this->db->where(DB_CN_WORDS_GAME_ID, $game_id);
		$query = $this->db->get(DB_TN_WORDS);
		$result = $query->result();
		return $result;
	}

	/**
	 * DBからゲームの取得
	 * @param string|null $q
	 * 検索ワードで絞り込む場合は指定
	 * @param int|null $category
	 * カテゴリを絞り込む場合は指定
	 * @param string $order_by
	 * ソートするカラムの指定
	 * @param string $order_asc
	 * 'ASC' or 'DESC' 昇順 or 降順
	 * @param inteter $limit
	 * リミット指定
	 * @param teterype $offset
	 * スタート個数指定
	 * @return Gameobj[]
	 */
	private function _select_games($q, $category, $user_id, $order_by, $order_asc, $limit, $offset) {
		$this->db->from(DB_TN_GAMES);
		if (isset($q)) {
			$this->db->like(DB_CN_GAMES_NAME, $q);
			$this->db->or_like(DB_CN_GAMES_DESCRIPTION, $q);
		}
		if (isset($category) && $category != GAME_CATEGORY_ALL) {
			$this->db->where(DB_CN_GAMES_CATEGORY, $category);
		}
		if (isset($user_id)) {
			$this->db->where(DB_CN_USERS_ID, $user_id);
		}
		$this->db->order_by($order_by, $order_asc);
		if (isset($limit) && $limit != -1) {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}

	private function _select_games_from_ids($ids, $limit, $offset, $order_by, $order_asc) {
		$this->db->where_in(DB_CN_GAMES_ID, $ids);
		$this->db->order_by($order_by, $order_asc);
		$this->db->limit($limit, $offset);
		$query = $this->db->get(DB_TN_GAMES);
		return $query->result();
	}

	private function _delete_words($game_id) {
		$this->db->where(DB_CN_GAMES_ID, $game_id);
		$this->db->delete(DB_TN_WORDS);
	}

	private function _insert_log(Logobj $log, $is_best = FALSE) {
		$this->db->set(DB_CN_LOG_USER_ID, $log->user_id);
		$this->db->set(DB_CN_LOG_GAME_ID, $log->game_id);
		if ($is_best) {
			$this->db->set(DB_CN_LOG_IS_BEST, TRUE);
		}
		$this->db->set(DB_CN_LOG_POINT, $log->point);
		$this->db->set(DB_CN_LOG_TIME, $log->time);
		$this->db->insert(DB_TN_LOG);
		return $this->db->insert_id();
	}

	private function _select_log($user_id, $game_id, $is_best = FALSE) {
		$this->db->where(DB_CN_LOG_USER_ID, $user_id);
		$this->db->where(DB_CN_LOG_GAME_ID, $game_id);
		$this->db->where(DB_CN_LOG_IS_BEST, $is_best);
		$query = $this->db->get(DB_TN_LOG);
		return $query->result();
	}

	private function _pop_log($user_id, $game_id, $num = 1, $is_best = FALSE) {
		$this->db->where(DB_CN_LOG_USER_ID, $user_id);
		$this->db->where(DB_CN_LOG_GAME_ID, $game_id);
		$this->db->where(DB_CN_LOG_IS_BEST, $is_best);
		$this->db->order_by(DB_CN_LOG_LOGGED_AT, 'ASC');
		$this->db->limit($num);
		$this->db->delete(DB_TN_LOG);
	}

	private function _delete_best_log($user_id, $game_id) {
		$this->_pop_log($user_id, $game_id, 1, TRUE);
	}

	private function _select_best_log($user_id, $game_id) {
		$logs = $this->_select_log($user_id, $game_id, TRUE);
		return isset($logs[0]) ? $logs[0] : NULL;
	}

	/**
	 * 
	 * @param int $game_id
	 * @param Tagobj[] $tags
	 */
	private function _insert_tags($game_id, array $tags) {
		$data = array();
		foreach ($tags as $tag) {
			$data[] = array(
				DB_CN_TAGS_GAME_ID => $game_id,
				DB_CN_TAGS_TEXT => $tag->text
			);
		}
		$this->db->insert_batch(DB_TN_TAGS, $data);
	}

	private function _select_ids_from_tagtexts(array $tag_text_list) {
		$this->db->where_in(DB_CN_TAGS_TEXT, $tag_text_list);
		$query = $this->db->get(DB_TN_TAGS);
		$ids = array();
		foreach ($query->result() as $row) {
			$ids[] = $row->{DB_CN_TAGS_GAME_ID};
		}
		return $ids;
	}

	private function _select_tags($game_id) {
		$this->db->select('*, count(' . DB_CN_TAGS_TEXT . ') as `' . DB_CN_AS_COUNT . '`');
		$this->db->where(DB_CN_TAGS_GAME_ID, $game_id);
		$this->db->group_by(DB_CN_TAGS_TEXT);
		$rows = $this->db->get(DB_TN_TAGS)->result();
		return $rows;
	}

	private function _delete_tags($game_id) {
		$this->db->where(DB_CN_TAGS_GAME_ID, $game_id);
		$this->db->delete(DB_TN_TAGS);
	}

	private function _delete_favorite($user_id, $game_id) {
		$this->db->where(DB_CN_FAVORITES_USER_ID, $user_id);
		$this->db->where(DB_CN_FAVORITES_GAME_ID, $game_id);
		$this->db->delete(DB_TN_FAVORITES);
	}

	private function _insert_favorite($user_id, $game_id) {
		$this->db->set(DB_CN_FAVORITES_USER_ID, $user_id);
		$this->db->set(DB_CN_FAVORITES_GAME_ID, $game_id);
		$this->db->insert(DB_TN_FAVORITES);
	}

	public function is_favorite($user_id, $game_id) {
		$this->db->where(DB_CN_FAVORITES_USER_ID, $user_id);
		$this->db->where(DB_CN_FAVORITES_GAME_ID, $game_id);
		$query = $this->db->get(DB_TN_FAVORITES);
		$result = $query->result();
		return !empty($result[0]);
	}

	private function _select_favorite_user($user_id) {
		$this->db->where(DB_CN_FAVORITES_USER_ID, $user_id);
		$query = $this->db->get(DB_TN_FAVORITES);
		return $query->result();
	}

	public function close() {
		$this->db->close();
	}

	/**
	 * 
	 * @param array $rows
	 * @return \Wordobj
	 */
	public static function to_wordobjs($rows) {
		$words = array();
		foreach ($rows as $row) {
			$words[$row->{DB_CN_WORDS_ID}] = new Wordobj($row);
		}
		return $words;
	}

	public static function to_logobjs($rows) {
		$logs = array();
		foreach ($rows as $row) {
			$logs[] = new Logobj($row);
		}
		return $logs;
	}

	/**
	 * 
	 * @param Gameobj $game
	 * @param array $active_points_b
	 * @param array $negative_points_b
	 * @return array
	 */
	public static function to_sql_points(Gameobj $game, $active_points_b, $negative_points_b) {
		$data = array();
		$active_points = array_filter($active_points_b, "strlen");
		$negative_points = array_filter($negative_points_b, "strlen");
		$max = count($active_points) < 100 ? count($active_points) : 100;
		foreach ($active_points as $i => $p) {
			$data[] = array(
				DB_CN_WORDS_ID => $p,
				DB_CN_WORDS_POINT_POSITIVE => $game->word_list[$p]->point_positive + ($max - $i),
			);
		}
		foreach ($negative_points as $i => $p) {
			$data[] = array(
				DB_CN_WORDS_ID => $p,
				DB_CN_WORDS_POINT_NEGATIVE => $game->word_list[$p]->point_negative + 1,
			);
		}
		return $data;
	}

}
