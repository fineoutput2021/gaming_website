<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("login_model");
        $this->load->model("admin/base_model");
        $this->load->library('user_agent');
    }

    public function admin_login()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            redirect("dcadmin/home", "refresh");
        } else {
            $this->load->view('admin/login/index');
        }
    }

    public function admin_login_process()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->helper('security');
        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'email', 'required|valid_email|xss_clean|trim');
            $this->form_validation->set_rules('password', 'password Number', 'required|xss_clean|trim');
            // $this->form_validation->set_rules('college_type', 'University Type', 'required');
            if ($this->form_validation->run()== true) {
                $email=$this->input->post('email');
                $passw=$this->input->post('password');
                $pass=md5($passw);
                $this->db->select('*');
                $this->db->from('tbl_team');
                $this->db->where('email', $email);
                // $this->db->where('password',$pass);
                $da_teacher= $this->db->get();
                $da=$da_teacher->row();
                if (!empty($da)) {
                    if ($da->is_active==1) {
                        $nnn1=$da->name;
                        $nnn2=$da->password;



                        if ($nnn2==$pass) {
                            $nnn3=$da->image;
                            $nnn4=$da->power;
                            $nnn5=$da->services;
                            $nnn6=$da->id;




                            if ($nnn4==1) {
                                $pos="Super Admin";
                            }
                            if ($nnn4==2) {
                                $pos="Admin";
                            }
                            if ($nnn4==3) {
                                $pos="Manager";
                            }


                            $this->session->set_userdata('admin_data', 1);
                            $this->session->set_userdata('adminname', $nnn1);
                            $this->session->set_userdata('image', $nnn3);
                            $this->session->set_userdata('power', $nnn4);
                            $this->session->set_userdata('services', $nnn5);
                            $this->session->set_userdata('position', $pos);
                            $this->session->set_userdata('admin_id', $nnn6);

                            redirect(ADMIN_URL."/home", "refresh");
                        } else {
                            $this->session->set_flashdata('emessage', 'wrong password');
                            // redirect("auth/login","refresh");
                            redirect($_SERVER['HTTP_REFERER']);
                        }
                    } else {
                        $this->session->set_flashdata('emessage', 'Your account is inactive. Contact admin.');
                        // redirect("auth/login","refresh");
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {

                                                    //echo $pass;
                    $this->session->set_flashdata('emessage', 'Wrong Details Entered');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata('emessage', validation_errors());
                // redirect("auth/login","refresh");
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function admin_login_reset()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->helper('security');
        if ($this->input->post()) {
            // print_r($this->input->post());
            // exit;
            $this->form_validation->set_rules('email_rst', 'email', 'required|valid email|xss_clean');

            // $this->form_validation->set_rules('college_type', 'University Type', 'required');
            if ($this->form_validation->run()== true) {
                $email=$this->input->post('email_rst');

                echo "Update Server Mail ids in panel";
                exit;

                $config = array(
                      'protocol' => 'smtp',
                      'smtp_host' => 'ssl://smtp.googlemail.com',
                      'smtp_port' => 465,
                      'smtp_user' => 'xxx@gmail.com', // change it to yours
                      'smtp_pass' => 'xxx', // change it to yours
                      'mailtype' => 'html',
                      'charset' => 'iso-8859-1',
                      'wordwrap' => true
                    );

                $message = '';
                $this->load->library('email', $config);
                $this->email->set_newline("\r\n");
                $this->email->from('xxx@gmail.com'); // change it to yours
                          $this->email->to('xxx@gmail.com');// change it to yours
                          $this->email->subject('Resume from JobsBuddy for your Job posting');
                $this->email->message($message);
                if ($this->email->send()) {
                    echo 'Email sent.';
                } else {
                    show_error($this->email->print_debugger());
                }




                redirect("admin/home/view_team", "refresh");
            } else {
                echo validation_error();
                exit;
            }
        } else {
            echo "Please insert some data, No data available";
            exit;
        }
    }



    public function logout()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $this->session->unset_userdata('admin_data');
            $this->session->unset_userdata('username');
            $this->session->unset_userdata('position');
            $this->session->unset_userdata('power');
            $this->session->unset_userdata('services');
            $this->session->unset_userdata('image');


            redirect("login/admin_login", "refresh");




        // $this->load->view('login/admin/index');
        } else {
            $this->load->view('admin/login/index');
        }
    }
}
