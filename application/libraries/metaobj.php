<?php

class Metaobj
{

	private $title;
	private $title_meta;
	public $description;
    public $head_detail;
    public $logo_url;
	/* @var $keywords array */
	public $keywords;
	public $url;
	private $type;
	public $no_meta;
	public $no_og;

	public function __construct()
	{
		$this->url = base_url(uri_string());
		$this->title = SITE_NAME;
		$this->title_meta = SITE_NAME;
		$this->keywords = META_KEYWORDS ? explode(',', META_KEYWORDS) : array();
		$this->type = 'article';
		$this->no_meta = FALSE;
		$this->no_og = FALSE;
        $this->head_detail = NULL;
	}

	public function unset_type()
	{
		$this->type = FALSE;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function get_title($is_meta = FALSE)
	{
		return $is_meta ? $this->title_meta : $this->title;
	}

	public function set_title($str)
	{
		$this->title = $str;
		$this->title_meta = $str . ' - ' . SITE_NAME;
// $this->title = $str;
// $this->title_meta = $str . ' - '.SITE_NAME;
	}

	public function set_keyword($str)
	{
		array_unshift($this->keywords, $str);
	}

	public function get_keywords_text()
	{
		return implode(',', $this->keywords);
	}

// call methods to setup several page case
	public function setup_top()
	{
		$this->description = SITE_DESCRIPTION;
		$this->type = 'website';
	}

	public function setup_user(Userobj $user) {
		$this->set_title("マイページ");
		$this->description = '@' . $user->screen_name . "のマイページ - 言えるかな";
		$this->no_meta = TRUE;
	}

	public function setup_help()
	{
		$this->set_title("ヘルプ");
		$this->description = "このサイトについてやその他サポート - 言えるかな";
	}

	public function setup_category($category)
	{
		$this->set_title(Gameobj::to_category_str($category) . "カテゴリ");
		$this->description = Gameobj::to_category_str($category) . "カテゴリのトップページ 人気の言えるかなを紹介";
	}

	public function setup_game(Gameobj $game)
	{
		// TODO: for game obj
		$this->set_title($game->get_full_title());
		$this->description = $game->get_full_title(TRUE) . 'に挑戦しよう。' .  $game->description .  '[' . $game->get_tags_text() . '] - 言えるかな';
	}

	public function setup_make()
	{
		$this->set_title("言えるかな？の作成");
		$this->description = "新しい言えるかなゲームの作成ページ - 言えるかな";
	}

	public function setup_update()
	{
		$this->set_title("言えるかな？の変更");
		$this->description = "言えるかな？再設定 - 言えるかな";
		$this->no_meta = TRUE;
	}

}
