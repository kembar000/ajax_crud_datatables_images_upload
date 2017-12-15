<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user extends CI_Model {
	var $table = 'user';
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
    public function cek($email, $pass) {
        $this->db->where("email", $email);
        $this->db->where("pass", $pass);
        return $this->db->get("user");
    }
}
?>