<?php

class User_model extends CI_Model {

	private $twitter_connection;

	/** @var Userobj */
	private $user;
	private $is_guest;

	function __construct() {
		parent::__construct();
		$this->check_login();
	}

	/**
	 * 
	 * @return Userobj
	 */
	public function get_main_user() {
		return $this->user;
	}

	public function is_login() {
		return !empty($this->user);
	}

	/**
	 * @return bool login successed or failed
	 */
	function check_login() {

		$twitter_config = $this->config->item('TWITTER_CONSUMER');

		$access_token = @$this->session->userdata('access_token');
		if (empty($access_token['oauth_token'])) {
			return FALSE;
		}

		$connection = new TwitterOAuth($twitter_config['key'], $twitter_config['secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
		$id_twitter = $access_token['user_id'];
		$screen_name = $access_token['screen_name'];
		$id_user = @$this->session->userdata('id_user');
		if (empty($id_user)) {
			$id_user = $this->get_user_id($id_twitter);
			$this->session->set_userdata('id_user', $id_user);
		}
		$img_url = @$this->session->userdata('img_url');
		if (empty($img_url)) {
			$result = $connection->get('account/verify_credentials');
			$img_url = @$result->profile_image_url;
			$this->session->set_userdata('img_url', $img_url);
		}

		$this->user = new Userobj($connection, $id_user, $id_twitter, $screen_name, $img_url);
		return TRUE;
	}

	/**
	 * TwitteridからユーザIDの取得
	 * 登録されていなければ登録する
	 * @param type $id_user
	 * @param type $is_guest
	 * @return Userobj|boolean
	 */
	public function get_user_id($id_twitter) {
		$user = $this->check_register($id_twitter);
		if ($user) {
			$user_id = $user->{DB_CN_USERS_ID};
		} else {
			$user_id = $this->register($id_twitter);
		}
		return $user_id;
	}

	/**
	 * 
	 * @param int $id twitterid
	 */
	function check_register($id_twitter) {
		$this->db->where(DB_CN_USERS_TWITTER_USER_ID, $id_twitter);
		$query = $this->db->get(DB_TN_USERS);
		$result = $query->result();
		return @$result[0] ? : FALSE;
	}

	function register($id_twitter) {
		$this->db->set(DB_CN_USERS_TWITTER_USER_ID, $id_twitter);
		$this->db->insert(DB_TN_USERS);

		return $this->db->insert_id();
	}

}
