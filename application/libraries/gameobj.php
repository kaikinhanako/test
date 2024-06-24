<?php

class Gameobj {

	public $id;
	public $user_id;
	public $name;
	public $description;
	public $word_unit;
	public $words_num;
	public $play_count;
	public $category;
	public $timestamp;
	public $updated_timestamp;
	public $is_favorited;

	/**
	 * ワードリスト
	 * @var Wordobj[]
	 */
	public $word_list;

	/**
	 * タグリスト
	 * @var Tagobj[] 
	 */
	public $tag_list;

	public function __construct($obj = NULL) {
		$this->word_list = array();
		$this->tag_list = array();
		$this->description = '';
		if (is_null($obj)) {
			return;
		}
		$this->id = $obj->{DB_CN_GAMES_ID};
		$this->user_id = $obj->{DB_CN_GAMES_USER_ID};
		$this->name = $obj->{DB_CN_GAMES_NAME};
		$this->description = $obj->{DB_CN_GAMES_DESCRIPTION};
		$this->word_unit = $obj->{DB_CN_GAMES_WORDS_UNIT};
		$this->words_num = $obj->{DB_CN_GAMES_WORDS_NUM};
		$this->play_count = $obj->{DB_CN_GAMES_PLAY_COUNT};
		$this->category = $obj->{DB_CN_GAMES_CATEGORY};
		$this->timestamp = strtotime($obj->{DB_CN_GAMES_CREATED_AT});
		$this->updated_timestamp = strtotime($obj->{DB_CN_GAMES_UPDATED_AT});
	}

	public function set_word_list(array $list) {
		$this->word_list = $list;
		$this->words_num = count($list);
		$this->rate_words_max();
	}

	public function get_words_num() {
		return $this->words_num;
	}

	public function get_wraped_description() {
		return wrap_taglink($this->description);
	}

	public function get_full_title($has_question = FALSE) {
		return $this->name . $this->get_words_num() . $this->word_unit . '言えるかな' . ($has_question ? '？' : '');
	}

	/**
	 * 
	 * @return Wordobj[]
	 */
	public function get_words_popular() {

		if (!function_exists('cmp_p')) {

			function cmp_p(Wordobj $a, Wordobj $b) {
				return $a->point_positive < $b->point_positive;
			}

		}
		$words = $this->word_list;
		usort($words, 'cmp_p');
		return $words;
	}

	public static function to_category_link($category) {
		if ($category == GAME_CATEGORY_ALL) {
			return base_url();
		}
		$lib = unserialize(GAME_CATEGORY_EN_MAP);
		return base_url(PATH_CATEGORY . $lib[$category]);
	}

	public function get_category_link() {
		return Gameobj::to_category_link($this->category);
	}

	public static function to_category_str($category) {
		$lib = unserialize(GAME_CATEGORY_MAP);
		return $lib[$category];
	}

	public function get_category_str() {
		return Gameobj::to_category_str($this->category);
	}

	public function get_category_tag() {
		return '<a href="' . $this->get_category_link() . '">' . tag_icon('bookmark') . $this->get_category_str() . '</a>';
	}

	public function get_tags_text() {
		$tag_texts = array();
		foreach($this->tag_list as $tag) {
			$tag_texts[] = $tag->text;
		}
		return implode(',', $tag_texts);
	}

	/**
	 * 
	 * @return Wordobj[]
	 */
	public function get_words_abord() {

		if (!function_exists('cmp_n')) {

			function cmp_n(Wordobj $a, Wordobj $b) {
				return $a->point_negative < $b->point_negative;
			}

		}
		$words = $this->word_list;
		usort($words, 'cmp_n');
		return $words;
	}

	public function rate_words_max() {
		$max_p = 0;
		$max_n = 0;
		foreach ($this->word_list as $word) {
			$max_p = max($word->point_positive, $max_p);
			$max_n = max($word->point_negative, $max_n);
		}
		Wordobj::$point_positive_maxs[$this->id] = $max_p;
		Wordobj::$point_negative_maxs[$this->id] = $max_n;
	}

	public function get_ranklink($tail = '') {
		return base_url(PATH_RANK . $this->id) . $tail;
	}

	public function get_link($tail = '') {
		return base_url(PATH_GAME . $this->id) . $tail;
	}

	public function get_guid() {
		return $this->id . '@' . base_url();
	}
}
