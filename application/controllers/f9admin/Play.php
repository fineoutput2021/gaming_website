<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once(APPPATH . 'core/CI_finecontrol.php');
class Play extends CI_finecontrol
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("login_model");
        $this->load->model("admin/base_model");
        $this->load->library('user_agent');
    }
    public function view_results()
    {
        if (!empty($this->session->userdata('admin_data'))) {
        $data['game_data'] = $this->db->get_where('tbl_game_cases', array('status'=>'survived','action is NOT NULL'=> NULL, FALSE));
            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/play/view_results');
            $this->load->view('admin/common/footer_view');
        } else {
            redirect("login/admin_login", "refresh");
        }
    }
}
