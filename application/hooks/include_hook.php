<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Include_hook
{
    function index()
    {
        include_once(FCPATH . PATH_LIB . 'twitteroauth/twitteroauth.php');
    }
}