<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| My Constants
|--------------------------------------------------------------------------
|
*/

/*
 * BaseSiteInfo
 */
define('SITE_NAME', '言えるかな？');
define('SITE_DESCRIPTION', '言えるかな？とはお題に沿った単語のリストの中でいくつ答えられるかを試すゲームです');

define('LAST_MOD', date(DateTime::ISO8601));

define('META_KEYWORDS', '言えるかな,いえるかな,ierukana');

define('AUTHOR_TWITTER_SCREEN_NAME', 'ieru_kana');
define('AUTHOR_NAME', 'ierukana');
define('AUTHOR_MAIL', 'ierukana.help@gmail.com');

define('NUM_GAME_PAR_SEARCHPAGE', 50);
define('NUM_GAME_PAR_TOPPAGE', 5);
define('NUM_GAME_PAR_TOPPAGE_RECENT', 10);
define('NUM_TAG_PAR_TOPPAGE', 10);
define('NUM_GAME_FEED', 5);

// TODO: change to 21
define('NUM_LOG_RESULT', 20);

define('FORMAT_RSS_TITLE_CATEGORY_KEYWORD', '%CATEGORY%');
define('FORMAT_RSS_TITLE_CATEGORY', '言えるかな？ - [' . FORMAT_RSS_TITLE_CATEGORY_KEYWORD . ']');

/* path */
define('PATH_IMG', 'images/');
define('PATH_LIB', 'lib/');
define('PATH_JS', 'js/');
define('PATH_STYLE', 'style/');
define('PATH_GAME', 'g/');
define('PATH_CATEGORY', 'c/');
define('PATH_RANK', PATH_GAME . 'r/');
define('PATH_USER', 'u/');
define('PATH_SEARCH', 's/');
define('PATH_HOT', 's/');
define('PATH_NEW', 's/new');
define('PATH_TAG', 'tag/');
define('PATH_AUTH', 'auth/');
define('PATH_MAKE', 'make/');
define('PATH_MAKE_POST', 'make/post');
define('PATH_UPDATE', 'update/');
define('PATH_UPDATE_POST', 'update/post/');
define('PATH_DELETE', 'del/');
define('PATH_FAVORITE', 'fav/');
define('PATH_HELP', 'help/');
define('PATH_RSS', 'rss/');
define('PATH_RSS_CATEGORY', 'rss/category/');

define('PATH_IMG_SC', PATH_IMG . 'sc.png');

define('PATH_AUTH_LOGIN', 'login');
define('PATH_AUTH_END', PATH_AUTH . 'end');
define('PATH_AUTH_LOGOUT', 'logout');

define('PATH_STYLE_CSS_MAIN', PATH_STYLE . 'main.css');
define('PATH_GOOGLE', 'google');
define('PATH_GOOGLE_ANALYTICS', PATH_GOOGLE . '/analyticstracking.php');
define('PATH_GOOGLE_ANALYTICS_JS', PATH_GOOGLE . '/analyticstracking.js');
define('PATH_BOOTSTRAP_CSS', PATH_LIB . 'bootstrap/css/bootstrap.min.css');
define('PATH_BOOTSTRAP_CSS_FA', PATH_LIB . 'bootstrap/css/font-awesome.min.css');
define('PATH_BOOTSTRAP_JS', PATH_LIB . 'bootstrap/js/bootstrap.min.js');

define('PATH_JQUERY_TEXT_CONVERT', PATH_LIB . 'jquery-supertextconverter-plugin.min.js');
define('URL_JQUERY_OFFLINE', PATH_LIB . 'jquery-2.1.1.min.js');

/* online lib url */
define('URL_TWITTER_WIDGETS', 'https://platform.twitter.com/widgets.js');
define('URL_YAHOO_RESET_CSS', 'https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.css');
define('URL_JQUERY', 'https://code.jquery.com/jquery.js');

/* DB constnats */
define('DB_TN_USERS', 'users');
define('DB_CN_USERS_ID', 'user_id');
define('DB_CN_USERS_TWITTER_USER_ID', 'twitter_user_id');

define('DB_TN_GAMES', 'games');
define('DB_CN_GAMES_ID', 'game_id');
define('DB_CN_GAMES_USER_ID', 'user_id');
define('DB_CN_GAMES_NAME', 'game_name');
define('DB_CN_GAMES_DESCRIPTION', 'game_description');
define('DB_CN_GAMES_WORDS_NUM', 'words_num');
define('DB_CN_GAMES_WORDS_UNIT', 'words_unit');
define('DB_CN_GAMES_PLAY_COUNT', 'play_count');
define('DB_CN_GAMES_CATEGORY', 'game_category');
define('DB_CN_GAMES_CREATED_AT', 'created_at');
define('DB_CN_GAMES_UPDATED_AT', 'updated_at');

define('DB_TN_WORDS', 'words');
define('DB_CN_WORDS_ID', 'word_id');
define('DB_CN_WORDS_GAME_ID', 'game_id');
define('DB_CN_WORDS_TEXT', 'word_text');
define('DB_CN_WORDS_POINT_POSITIVE', 'point_positive');
define('DB_CN_WORDS_POINT_NEGATIVE', 'point_negative');

define('DB_TN_TAGS', 'tags');
define('DB_CN_TAGS_GAME_ID', 'game_id');
define('DB_CN_TAGS_TEXT', 'tag_text');

define('DB_TN_FAVORITES', 'favorites');
define('DB_CN_FAVORITES_USER_ID', 'user_id');
define('DB_CN_FAVORITES_GAME_ID', 'game_id');

define('DB_TN_LOG', 'log');
define('DB_CN_LOG_USER_ID', 'user_id');
define('DB_CN_LOG_GAME_ID', 'game_id');
define('DB_CN_LOG_POINT', 'point');
define('DB_CN_LOG_TIME', 'time');
define('DB_CN_LOG_LOGGED_AT', 'logged_at');
define('DB_CN_LOG_IS_BEST', 'is_best');

define('DB_CN_AS_COUNT', 'ascount');

define('MYSQL_TIMESTAMP', 'Y-m-d H:i:s');

define('SORT_HOT', 'hot');
define('SORT_NEW', 'new');
define('SORT_RECENT', 'recent');

define('GAME_MODE_NORMAL', 'normal');
define('GAME_MODE_EASY', 'easy');
define('GAME_MODE_SO_EASY', 'soeasy');
define('GAME_MODE_TYPING', 'typing');
define('GAME_MODE_RANK', 'ranking');

define('GAME_CATEGORY_OTHER', 0);
define('GAME_CATEGORY_ENTER', 1);
define('GAME_CATEGORY_SCIENCE', 2);
define('GAME_CATEGORY_ARTS', 3);
define('GAME_CATEGORY_ANIME', 4);
define('GAME_CATEGORY_SPORTS', 5);
define('GAME_CATEGORY_ALL', -1);
define('GAME_CATEGORY_NUM', 6);

$category_map = array(
	GAME_CATEGORY_ALL => '総合',
	GAME_CATEGORY_ENTER => 'エンタメ・音楽',
	GAME_CATEGORY_SCIENCE => '理系分野',
	GAME_CATEGORY_ARTS => '文系分野',
	GAME_CATEGORY_ANIME => 'アニメ・漫画・ゲーム',
	GAME_CATEGORY_SPORTS => '一般・スポーツ',
	GAME_CATEGORY_OTHER  => 'その他',
);
define('GAME_CATEGORY_MAP', serialize($category_map));

$category_en_map = array(
	GAME_CATEGORY_ALL => '',
	GAME_CATEGORY_ENTER => 'enter',
	GAME_CATEGORY_SCIENCE => 'science',
	GAME_CATEGORY_ARTS => 'arts',
	GAME_CATEGORY_ANIME => 'anime',
	GAME_CATEGORY_SPORTS => 'sports',
	GAME_CATEGORY_OTHER  => 'other',
);
define('GAME_CATEGORY_EN_MAP', serialize($category_en_map));

/* End of file constants.php */
/* Location: ./application/config/constants.php */