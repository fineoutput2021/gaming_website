<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once(APPPATH . 'core/CI_finecontrol.php');
class Features extends CI_finecontrol
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("login_model");
        $this->load->model("admin/base_model");
        $this->load->library('user_agent');
    }
    //================================ VIEW FEATURES ============================================
    public function view_features()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $this->db->select('*');
            $this->db->from('tbl_features');
            $data['features_data']= $this->db->get();

            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/features/view_features');
            $this->load->view('admin/common/footer_view');
        } else {
            redirect("login/admin_login", "refresh");
        }
    }
    //================================ VIEW UPDATE FEATURES ============================================
    public function update_feature($idd)
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $id=base64_decode($idd);
            $data['id']=$idd;

            $this->db->select('*');
            $this->db->from('tbl_features');
            $this->db->where('id', $id);
            $data['features_data']= $this->db->get()->row();

            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/features/update_feature');
            $this->load->view('admin/common/footer_view');
        } else {
            redirect("login/admin_login", "refresh");
        }
    }
    //================================ UPDATE FEATURES DATA ============================================
    public function update_feature_data($idd)
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->load->helper('security');
            if ($this->input->post()) {
                $this->form_validation->set_rules('round', 'round', 'required|trim|xss_clean');
                $this->form_validation->set_rules('step', 'step', 'required|trim|xss_clean');
                $this->form_validation->set_rules('offer', 'offer', 'required|trim|xss_clean');
                $this->form_validation->set_rules('title', 'title', 'required|trim|xss_clean');
                $this->form_validation->set_rules('inflow', 'inflow', 'trim|xss_clean');
                $this->form_validation->set_rules('outflow', 'outflow', 'trim|xss_clean');
                $this->form_validation->set_rules('msg1', 'msg1', 'trim|xss_clean');
                $this->form_validation->set_rules('msg2', 'msg2', 'trim|xss_clean');
                $this->form_validation->set_rules('msg3', 'msg3', 'trim|xss_clean');
                if ($this->form_validation->run()== true) {
                    $round=$this->input->post('round');
                    $step=$this->input->post('step');
                    $offer=$this->input->post('offer');
                    $title=$this->input->post('title');
                    $inflow=$this->input->post('inflow');
                    $outflow=$this->input->post('outflow');
                    $msg1=$this->input->post('msg1');
                    $msg2=$this->input->post('msg2');
                    $msg3=$this->input->post('msg3');

                    $ip = $this->input->ip_address();
                    date_default_timezone_set("Asia/Calcutta");
                    $cur_date=date("Y-m-d H:i:s");
                    $addedby=$this->session->userdata('admin_id');

                    $idw=base64_decode($idd);

                    $data_insert = array('round'=>$round,
                    'step'=>$step,
                    'offer'=>$offer,
                    'title'=>$title,
                    'inflow'=>$inflow,
                    'outflow'=>$outflow,
                    'msg1'=>$msg1,
                    'msg2'=>$msg2,
                    'msg3'=>$msg3,
                    'ip'=>$ip,
                    'last_updated_date'=>$cur_date,
                    'updated_by'=>$addedby,

                    );
                    $this->db->where('id', $idw);
                    $last_id=$this->db->update('tbl_features', $data_insert);
                    if ($last_id!=0) {
                        $this->session->set_flashdata('smessage', 'Data updated successfully');
                        redirect("dcadmin/Features/view_features", "refresh");
                    } else {
                        $this->session->set_flashdata('emessage', 'Sorry error occured');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata('emessage', validation_errors());
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata('emessage', 'Please insert some data, No data available');
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            redirect("login/admin_login", "refresh");
        }
    }
    //================================ VIEW SETTING ============================================
    public function view_settings()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $this->db->select('*');
            $this->db->from('tbl_setting');
            $data['setting_data']= $this->db->get();

            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/setting/view_settings');
            $this->load->view('admin/common/footer_view');
        } else {
            redirect("login/admin_login", "refresh");
        }
    }
    //================================ VIEW UPDATE SETTING ============================================
    public function update_setting($idd)
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $id=base64_decode($idd);
            $data['id']=$idd;

            $this->db->select('*');
            $this->db->from('tbl_setting');
            $this->db->where('id', $id);
            $data['setting_data']= $this->db->get()->row();

            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/setting/update_setting');
            $this->load->view('admin/common/footer_view');
        } else {
            redirect("login/admin_login", "refresh");
        }
    }
    //================================ UPDATE SETTING DATA ============================================
    public function update_setting_data($idd)
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->load->helper('security');
            if ($this->input->post()) {
                $this->form_validation->set_rules('salary', 'salary', 'required|trim|xss_clean');
                $this->form_validation->set_rules('personal_exp', 'personal_exp', 'required|trim|xss_clean');
                $this->form_validation->set_rules('loan_exp', 'loan_exp', 'required|trim|xss_clean');
                if ($this->form_validation->run()== true) {
                    $salary=$this->input->post('salary');
                    $personal_exp=$this->input->post('personal_exp');
                    $loan_exp=$this->input->post('loan_exp');

                    $ip = $this->input->ip_address();
                    date_default_timezone_set("Asia/Calcutta");
                    $cur_date=date("Y-m-d H:i:s");

                    $addedby=$this->session->userdata('admin_id');

                    $idw=base64_decode($idd);
                    $ip = $this->input->ip_address();
                    date_default_timezone_set("Asia/Calcutta");
                    $cur_date=date("Y-m-d H:i:s");
                    $addedby=$this->session->userdata('admin_id');
                    $data_insert = array('salary'=>$salary,
                    'personal_exp'=>$personal_exp,
                    'loan_exp'=>$loan_exp,
                    'ip'=>$ip,
                    'last_updated_date'=>$cur_date,
                    'updated_by'=>$addedby,

                    );
                    $this->db->where('id', $idw);
                    $last_id=$this->db->update('tbl_setting', $data_insert);
                    if ($last_id!=0) {
                        $this->session->set_flashdata('smessage', 'Data updated successfully');
                        redirect("dcadmin/Features/view_settings", "refresh");
                    } else {
                        $this->session->set_flashdata('emessage', 'Sorry error occured');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata('emessage', validation_errors());
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata('emessage', 'Please insert some data, No data available');
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            redirect("login/admin_login", "refresh");
        }
    }
}
