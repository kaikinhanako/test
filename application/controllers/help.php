<?php

class Help extends CI_Controller {

	/** @var User_model */
	public $user;

	public function __construct() {
		parent::__construct();
		$this->load->model('User_model', 'user', TRUE);
	}

	public function index() {
		$user = $this->user->get_main_user();
		$meta = new Metaobj();
		$meta->setup_help();
		$this->load->view('head', array('meta' => $meta));
		$this->load->view('bodywrapper_head');
		$this->load->view('navbar', array('user' => $user));
		$this->load->view('container_head');
		$this->load->view('breadcrumbs', array('list' => array('TOP' => base_url(), 'ヘルプ - 言えるかな？' => TRUE)));
		$this->load->view('title', array('title' => $meta->get_title()));
		$this->load->view('helppage');
		$this->load->view('container_foot');
		$this->load->view('bodywrapper_foot');
		$this->load->view('footer');
		$this->load->view('foot');
	}

}
