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
        $this->load->library('pagination');
    }
    //============================ VIEW RESULT ==============================================
    public function view_results($t="")
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $count = $this->db->get_where('tbl_game_cases', array('action is NOT NULL'=> null, false))->num_rows();

            // echo count;
            $config['base_url'] = base_url().'dcadmin/Play/view_results/';
            $per_page = 1000;
            $config['total_rows'] = $count;
            $config['per_page'] = $per_page;
            $config['num_links'] = 5;

            $config['full_tag_open'] = '<ul class="pagination">';

            $config['full_tag_close'] = '</ul>';

            $config['use_page_numbers'] = true;

            $config['next_link'] = 'First';
            $config['first_tag_open'] = '<li class="first page">';
            $config['first_tag_close'] = '</li>';

            $config['last_link'] = 'Last';
            $config['last_tag_open'] = '<li class="last page">';
            $config['last_tag_close'] = '</li>';

            $config['next_link'] = 'Next';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';

            $config['prev_link'] = ' Previous';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';

            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';

            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            if (!empty($t)) {
                $page = $t;
                $i = $per_page * ($page - 1) + 1;
                $start = ($page - 1) * $config['per_page'];
            } else {
                $page = 0;
                $start = 0;
                $i=1;
            }
            $data['game_data'] = $this->db->limit($config["per_page"], $start)->get_where('tbl_game_cases', array('action is NOT NULL'=> null, false));
            $data['links'] = $this->pagination->create_links();
            $data['i'] = $i;
            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/play/view_results');
            $this->load->view('admin/common/footer_view');
        } else {
            redirect("login/admin_login", "refresh");
        }
    }
    //============================ PLAY GAME ==============================================
    public function play()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $this->db->truncate('tbl_game_cases');// --------- table truncate
            //---- get setting info --
            $setting_info = $this->db->get_where('tbl_setting')->result();
            $CH = $setting_info[0]->salary-($setting_info[0]->personal_exp+$setting_info[0]->loan_exp);
            //--------------- round 1---------------------------
            $this->r1step2($setting_info);// buy tcs stock
            $this->r1step3();//---- buy youtube channel
            $this->r1step4();// buy real state
            $this->r1step5();// fixed medical expense
            $this->r1step6();// Loan Repayment

          //--------------- round 2---------------------------
            $this->r2step2($CH);// buy factory setup
            $this->r2step3();// buy commercial setup
            $this->r2step4();// buy stock asian paints
            $this->r2step5();// gift
            $this->r2step6();// sell youtube channel
            $this->r2step7();// sell sell tcs stock
            $this->r2step8();//  Loan Repayment

           //--------------- round 3---------------------------
            $this->r3step2($CH);// buy lab
            $this->r3step3();//  buy reliance stock
            $this->r3step4();//  buy land
            $this->r3step5();// fixed child expense
            $this->r3step6();// sell stock asian paints
            $this->r3step7();// sell commercial setup
            $this->r3step8();//  Loan Repayment

             //--------------- round 4 ---------------------------
              $this->r4step2($CH);// chance donation received
              $this->r4step3();// sell land
              $this->r4step4();// sell lab
              $this->r4step5();//  Loan Repayment

            //-------- result ---------
            $this->session->set_flashdata('smessage', 'Success! Plaese Check Results');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect("login/admin_login", "refresh");
        }
    }
    //====================================================== START ROUND 1 ==========================================================
    //======================= round 1 step 2 (buy tcs stock) ======================================
    public function r1step2($setting_info)
    {
        $salary = $setting_info[0]->salary;
        $personal_exp = $setting_info[0]->personal_exp;
        $loan_exp = $setting_info[0]->loan_exp;
        $CH = $salary-($personal_exp+$loan_exp);
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 1,'step'=> 2))->result();
        $bp =$step_info[0]->outflow;
        if ($CH >= $bp) {
            $history=array(1);
            $history2=array(2);
            //------ yes entry -----
            $buy=array(1);
            $data_insert = array('round_id'=>1,
            'step_id'=>2,
            'action'=>1,
            'salary'=>$salary,
            'cash_in_hand' =>$CH - $bp,
            'buy' =>json_encode($buy),
            'personal_exp' =>$personal_exp,
            'loan_exp' =>$loan_exp,
            'summary' =>'Yes',
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //------ no entry -----
            $data_insert = array('round_id'=>1,
            'step_id'=>2,
            'action'=>2,
            'salary'=>$salary,
            'cash_in_hand' =>$CH,
            'personal_exp' =>$personal_exp,
            'loan_exp' =>$loan_exp,
            'summary' =>'No',
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        //------ for Out of money
        else {
            $data_insert = array('round_id'=>1,
        'step_id'=>2,
        'action'=>0,
        'salary'=>$salary,
        'cash_in_hand' =>$CH,
        'personal_exp' =>$personal_exp,
        'summary' =>'NA',
        'loan_exp' =>$loan_exp,
        'status'=>'survived'
        );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 1 step 3 (buy youtube channel) ======================================
    public function r1step3()
    {
        $step_2_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 2,'status'=>'survived'))->result();
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 1,'step'=> 3))->result();
        $in =$step_info[0]->inflow;
        $out =$step_info[0]->outflow;
        foreach ($step_2_data as $step2) {
            //--------- yes entry ---------
            $buy = json_decode($step2->buy);
            if (!empty($buy)) {
                array_push($buy, 2);
            } else {
                $buy=array(2);
            }
            $loan_exp = $step2->loan_exp+$out;
            $data_insert = array('case_id'=>$step2->id,
            'round_id'=>1,
            'step_id'=>3,
            'action'=>1,
            'salary'=>$step2->salary,
            'passive_income'=>$in,
            'cash_in_hand' =>$step2->cash_in_hand + ($in-$out),
            'personal_exp' =>$step2->personal_exp,
            'loan_exp' =>$loan_exp,
            'buy' =>json_encode($buy),
            'summary' =>$step2->summary.",Yes",
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //--------- no entry ---------
            $buy = json_decode($step2->buy);
            $data_insert = array('case_id'=>$step2->id,
        'round_id'=>1,
        'step_id'=>3,
        'action'=>2,
        'salary'=>$step2->salary,
        'cash_in_hand' =>$step2->cash_in_hand,
        'personal_exp' =>$step2->personal_exp,
        'loan_exp' =>$step2->loan_exp,
        'buy' =>json_encode($buy),
        'summary' =>$step2->summary.",No",
        'status'=>'survived'
        );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 1 step 4 (buy real state) ======================================
    public function r1step4()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 3,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 1,'step'=> 4))->result();
        $in =$step_info[0]->inflow;
        $out =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            //--------- yes entry ---------
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                array_push($buy, 3);
            } else {
                $buy=array(3);
            }
            $loan_exp = $step->loan_exp+$out;
            $data_insert = array('case_id'=>$step->id,
        'round_id'=>1,
        'step_id'=>4,
        'action'=>1,
        'salary'=>$step->salary,
        'passive_income'=> $step->passive_income+$in,
        'cash_in_hand' =>$step->cash_in_hand + ($in-$out),
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$loan_exp,
        'buy' =>json_encode($buy),
        'summary' =>$step->summary.",Yes",
        'status'=>'survived'
        );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //--------- no entry ---------
            $buy = json_decode($step->buy);
            $data_insert = array('case_id'=>$step->id,
        'round_id'=>1,
        'step_id'=>4,
        'action'=>2,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand,
        'passive_income'=>$step->passive_income,
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'summary' =>$step->summary.",No",
        'status'=>'survived'
        );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 1 step 5 (fixed medical expense)======================================
    public function r1step5()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 4,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 1,'step'=> 5))->result();
        $exp =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            if ($step->cash_in_hand > $exp) {
                $buy = json_decode($step->buy);
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $data_insert = array('case_id'=>$step->id,
            'status'=>'survived',
            'round_id'=>1,
            'step_id'=>5,
            'action'=>1,
            'salary'=>$new_salary,
            'cash_in_hand' =>$step->cash_in_hand - ($exp),
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'passive_income'=>$step->passive_income,
            'buy' =>json_encode($buy),
            'summary' =>$step->summary.",Yes",
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                $buy = json_decode($step->buy);
                //--------- out entry ---------
                $new_salary = $step->salary;
                $data_insert = array('case_id'=>$step->id,
            'status'=>'out',
            'round_id'=>1,
            'step_id'=>5,
            'action'=>0,
            'salary'=>$new_salary,
            'cash_in_hand' =>$step->cash_in_hand - ($exp),
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'passive_income'=>$step->passive_income,
            'buy' =>json_encode($buy),
            'summary' =>$step->summary.",Yes",
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 1 step 6 (Loan Repayment) ======================================
    public function r1step6()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 5,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 1,'step'=> 6))->result();
        $exp =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $buy = json_decode($step->buy);
            if ($step->cash_in_hand >= $exp) {
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $loan_exp = $step->loan_exp-($exp * LOAN_PERCENTAGE /100);
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>1,
                'step_id'=>6,
                'action'=>1,
                'salary'=>$new_salary,
                'cash_in_hand' =>$step->cash_in_hand - ($exp),
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'summary' =>$step->summary.",Yes",
                'status'=>'survived'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                //--------- no entry ---------
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>1,
                'step_id'=>6,
                'action'=>2,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'passive_income'=>$step->passive_income,
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'buy' =>json_encode($buy),
                'summary' =>$step->summary.",No",
                'status'=>'survived'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                //--------- for Out of money ---------
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>1,
                'step_id'=>6,
                'action'=>0,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'summary' =>$step->summary.",NA",
                'status'=>'survived'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //====================================================== END ROUND 1 ==========================================================

    //====================================================== START ROUND 2 ==========================================================
    //======================= round 2 step 2 (buy factory setup) ======================================
    public function r2step2($ch)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 6,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 2,'step'=> 2))->result();
        $in =$step_info[0]->inflow;
        $out =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                array_push($buy, 4);
            } else {
                $buy=array(4);
            }
            //--------- yes entry ---------
            $new_cash_in_hand = $step->cash_in_hand + $ch + $step->passive_income;
            $loan_exp = $step->loan_exp+$out;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>2,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$loan_exp,
            'passive_income'=>$step->passive_income+$in,
            'buy' =>json_encode($buy),
            'summary' =>$step->summary.",Yes",
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //--------- no entry ---------
            $buy = json_decode($step->buy);
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>2,
            'action'=>2,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand,
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'passive_income'=>$step->passive_income,
            'buy' =>json_encode($buy),
            'summary' =>$step->summary.",No",
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 2 step 3 (buy commercial setup) ======================================
    public function r2step3()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 2,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 2,'step'=> 3))->result();
        $in =$step_info[0]->inflow;
        $out =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                array_push($buy, 5);
            } else {
                $buy=array(5);
            }
            //--------- yes entry ---------
            $new_cash_in_hand = $step->cash_in_hand;
            $loan_exp = $step->loan_exp+$out;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>3,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$loan_exp,
            'passive_income'=>$step->passive_income+$in,
            'buy' =>json_encode($buy),
            'summary' =>$step->summary.",Yes",
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //--------- no entry ---------
            $buy = json_decode($step->buy);
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>3,
            'action'=>2,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand,
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'passive_income'=>$step->passive_income,
            'buy' =>json_encode($buy),
            'summary' =>$step->summary.",No",
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 2 step 4 (buy stock asian paints) ======================================
    public function r2step4()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 3,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 2,'step'=> 3))->result();
        $bp =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $new_cash_in_hand = $step->cash_in_hand;
            if ($new_cash_in_hand > $bp) {
                if (!empty($buy)) {
                    array_push($buy, 6);
                } else {
                    $buy=array(6);
                }
                //--------- yes entry ---------
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>2,
                'step_id'=>4,
                'action'=>1,
                'salary'=>$step->salary,
                'cash_in_hand' =>$new_cash_in_hand - ($bp),
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'buy' =>json_encode($buy),
                'summary' =>$step->summary.",Yes",
                'passive_income'=>$step->passive_income,
                'status'=>'survived',
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                //--------- no entry ---------
                $buy = json_decode($step->buy);
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>2,
                'step_id'=>4,
                'action'=>2,
                'salary'=>$step->salary,
                'cash_in_hand' =>$new_cash_in_hand,
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'summary' =>$step->summary.",No",
                  'status'=>'survived',
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                //--------- for Out of money ---------
                $buy = json_decode($step->buy);
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>2,
                'step_id'=>4,
                'action'=>0,
                'salary'=>$step->salary,
                'cash_in_hand' =>$new_cash_in_hand - ($bp),
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'summary' =>$step->summary.",NA",
                  'status'=>'out',
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 2 step 5 (Gift) ======================================
    public function r2step5()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 4,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 2,'step'=> 5))->result();
        $gift =$step_info[0]->inflow;
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $buy = json_decode($step->buy);
            //--------- yes entry ---------
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>5,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($gift),
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'buy' =>json_encode($buy),
            'passive_income'=>$step->passive_income,
            'summary' =>$step->summary.",Yes",
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 2 step 6 (sell youtube channel) ======================================
    public function r2step6()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 5,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 2,'step'=> 5))->result();
        $amount =$step_info[0]->inflow;
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                if (in_array(2, $buy)) {
                    $sell = array(1);
                    //--------- yes entry ---------
                    //get yt buy details
                    $yt_buy = $this->db->get_where('tbl_features', array('round'=> 1,'step'=> 3))->result();
                    $loan_exp = $step->loan_exp-$yt_buy[0]->outflow;
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>2,
                    'step_id'=>6,
                    'action'=>1,
                    'salary'=>$step->salary,
                    'cash_in_hand' =>$step->cash_in_hand + ($amount),
                    'personal_exp' =>$step->personal_exp,
                    'loan_exp' =>$step->loan_exp,
                    'buy' =>json_encode($buy),
                    'passive_income'=>$step->passive_income-$yt_buy[0]->inflow,
                    'sell'=>json_encode($sell),
                    'summary' =>$step->summary.",Yes",
                    'status'=>'survived'
                    );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>2,
                    'step_id'=>6,
                    'action'=>2,
                    'salary'=>$step->salary,
                    'cash_in_hand' =>$step->cash_in_hand,
                    'personal_exp' =>$step->personal_exp,
                    'loan_exp' =>$step->loan_exp,
                    'buy' =>json_encode($buy),
                    'summary' =>$step->summary.",No",
                    'passive_income'=>$step->passive_income,
                    'status'=>'survived'
                    );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>2,
                    'step_id'=>6,
                    'salary'=>$step->salary,
                    'cash_in_hand' =>$step->cash_in_hand,
                    'personal_exp' =>$step->personal_exp,
                    'loan_exp' =>$step->loan_exp,
                    'buy' =>json_encode($buy),
                    'summary' =>$step->summary,
                    'passive_income'=>$step->passive_income,
                    'status'=>'survived'
                    );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            } else {
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>2,
                'step_id'=>6,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'buy' =>json_encode($buy),
                'summary' =>$step->summary,
                'passive_income'=>$step->passive_income,
                'status'=>'survived'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 2 step 7 (sell tcs stock) ======================================
    public function r2step7()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 6,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 2,'step'=> 7))->result();
        $amount =$step_info[0]->inflow;
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if (!empty($buy)) {
                if (in_array(1, $buy)) {
                    if (!empty($sell)) {
                        array_push($sell, 2);
                    } else {
                        $sell=array(2);
                    }
                    //--------- yes entry ---------
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>2,
                    'step_id'=>7,
                    'action'=>1,
                    'salary'=>$step->salary,
                    'cash_in_hand' =>$step->cash_in_hand + ($amount),
                    'personal_exp' =>$step->personal_exp,
                    'loan_exp' =>$step->loan_exp,
                    'buy' =>json_encode($buy),
                    'passive_income'=>$step->passive_income,
                    'sell'=>json_encode($sell),
                    'summary' =>$step->summary.",Yes",
                    'status'=>'survived'
                    );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $sell = json_decode($step->sell);
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>2,
                    'step_id'=>7,
                    'action'=>2,
                    'salary'=>$step->salary,
                    'cash_in_hand' =>$step->cash_in_hand,
                    'personal_exp' =>$step->personal_exp,
                    'loan_exp' =>$step->loan_exp,
                    'buy' =>json_encode($buy),
                    'passive_income'=>$step->passive_income,
                    'sell'=>json_encode($sell),
                    'summary' =>$step->summary.",No",
                    'status'=>'survived'
                    );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>2,
                    'step_id'=>7,
                    'salary'=>$step->salary,
                    'cash_in_hand' =>$step->cash_in_hand,
                    'personal_exp' =>$step->personal_exp,
                    'loan_exp' =>$step->loan_exp,
                    'buy' =>json_encode($buy),
                    'passive_income'=>$step->passive_income,
                    'sell'=>json_encode($sell),
                    'summary' =>$step->summary,
                    'status'=>'survived'
                    );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            } else {
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>2,
                'step_id'=>7,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'buy' =>json_encode($buy),
                'passive_income'=>$step->passive_income,
                'sell'=>json_encode($sell),
                'summary' =>$step->summary,
                'status'=>'survived'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 2 step 8 (Loan Repayment) ======================================
    public function r2step8()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 7,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 2,'step'=> 8))->result();
        $exp =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if ($step->cash_in_hand > $exp) {
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $loan_exp = $step->loan_exp-($exp * LOAN_PERCENTAGE /100);
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>2,
                'step_id'=>8,
                'action'=>1,
                'salary'=>$new_salary,
                'cash_in_hand' =>$step->cash_in_hand - ($exp),
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'sell'=>json_encode($sell),
                'summary' =>$step->summary.",Yes",
                'status'=>'survived'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                //--------- no entry ---------
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>2,
                'step_id'=>8,
                'action'=>2,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'passive_income'=>$step->passive_income,
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'buy' =>json_encode($buy),
                'sell'=>json_encode($sell),
                'summary' =>$step->summary.",No",
                'status'=>'survived'
      );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                //--------- for Out of money ---------
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>2,
                'step_id'=>8,
                'action'=>0,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand - ($exp),
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'sell'=>json_encode($sell),
                'summary' =>$step->summary.",NA",
                'status'=>'out'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //====================================================== END ROUND 2 ==========================================================


    //====================================================== START ROUND 3 ==========================================================
    //======================= round 3 step 2 (buy lab) ======================================
    public function r3step2($CH)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 8,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 3,'step'=> 2))->result();
        $in =$step_info[0]->inflow;
        $out =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $new_cash_in_hand = $step->cash_in_hand +$CH + $step->passive_income;
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if ($new_cash_in_hand>0) {
                if (!empty($buy)) {
                    array_push($buy, 7);
                } else {
                    $buy=array(7);
                }
                //--------- yes entry ---------
                $loan_exp = $step->loan_exp+$out;
                $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>2,
        'action'=>1,
        'salary'=>$step->salary,
        'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$loan_exp,
        'passive_income'=>$step->passive_income+$in,
        'buy' =>json_encode($buy),
        'sell'=>json_encode($sell),
        'summary' =>$step->summary.",Yes",
        'status'=>'survived'
        );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                //--------- no entry ---------
                $buy = json_decode($step->buy);
                $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>2,
        'action'=>2,
        'salary'=>$step->salary,
        'cash_in_hand' =>$new_cash_in_hand,
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'passive_income'=>$step->passive_income,
        'buy' =>json_encode($buy),
        'sell'=>json_encode($sell),
        'summary' =>$step->summary.",No",
        'status'=>'survived'
        );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                //--------- no entry ---------
                $buy = json_decode($step->buy);
                $data_insert = array('case_id'=>$step->id,
    'round_id'=>3,
    'step_id'=>2,
    'action'=>0,
    'salary'=>$step->salary,
    'cash_in_hand' =>$new_cash_in_hand,
    'personal_exp' =>$step->personal_exp,
    'loan_exp' =>$step->loan_exp,
    'passive_income'=>$step->passive_income,
    'buy' =>json_encode($buy),
    'sell'=>json_encode($sell),
    'summary' =>$step->summary.",NA",
    'status'=>'out'
    );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 3 step 3 (buy reliance stock) ======================================
    public function r3step3()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 2,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 3,'step'=> 3))->result();
        $bp =$step_info[0]->outflow;

        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            $salary = $step->salary;
            $CH = $step->cash_in_hand;
            if ($CH >= $bp) {
                if (!empty($buy)) {
                    array_push($buy, 8);
                } else {
                    $buy=array(8);
                }
                //------ yes entry -----
                $data_insert = array('case_id'=>$step->id,
          'round_id'=>3,
          'step_id'=>3,
          'action'=>1,
          'salary'=>$salary,
          'cash_in_hand' =>$CH - $bp,
          'buy' =>json_encode($buy),
          'summary' =>$step->summary.",Yes",
          'personal_exp' =>$step->personal_exp,
          'loan_exp' =>$step->loan_exp,
          'passive_income'=>$step->passive_income,
          'sell'=>json_encode($sell),
          'status'=>'survived'
          );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                //------ no entry -----
                $buy = json_decode($step->buy);
                $data_insert = array('case_id'=>$step->id,
          'round_id'=>3,
          'step_id'=>3,
          'action'=>2,
          'salary'=>$salary,
          'cash_in_hand' =>$CH,
          'buy' =>json_encode($buy),
          'summary' =>$step->summary.",No",
          'personal_exp' =>$step->personal_exp,
          'loan_exp' =>$step->loan_exp,
          'passive_income'=>$step->passive_income,
          'sell'=>json_encode($sell),
          'status'=>'survived'
          );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
            //------ for Out of money
            else {
                $data_insert = array('case_id'=>$step->id,
              'round_id'=>3,
            'step_id'=>3,
            'action'=>0,
            'salary'=>$salary,
            'cash_in_hand' =>$CH,
            'buy' =>json_encode($buy),
            'summary' =>$step->summary.",NA",
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'sell'=>json_encode($sell),
            'passive_income'=>$step->passive_income,
            'status'=>'survived'

            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }

        return;
    }
    //======================= round 3 step 4 (buy land) ======================================
    public function r3step4()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 3,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 3,'step'=> 4))->result();
        $in =$step_info[0]->inflow;
        $out =$step_info[0]->outflow;

        foreach ($step_data->result() as $step) {
            //-----step  history ----

            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                array_push($buy, 9);
            } else {
                $buy=array(9);
            }
            //--------- yes entry ---------
            $new_cash_in_hand = $step->cash_in_hand;
            $loan_exp = $step->loan_exp+$out;
            $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>4,
        'action'=>1,
        'salary'=>$step->salary,
        'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
        'summary' =>$step->summary.",Yes",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$loan_exp,
        'passive_income'=>$step->passive_income+$in,
        'buy' =>json_encode($buy),
        'sell' =>$step->sell,
        'status'=>'survived'
        );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //--------- no entry ---------
            $buy = json_decode($step->buy);
            $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>4,
        'action'=>2,
        'salary'=>$step->salary,
        'cash_in_hand' =>$new_cash_in_hand,
        'summary' =>$step->summary.",No",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'passive_income'=>$step->passive_income,
        'buy' =>json_encode($buy),
        'sell' =>$step->sell,
        'status'=>'survived'
        );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 3 step 5 (fixed child expense)======================================
    public function r3step5()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 4,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 3,'step'=> 5))->result();
        $exp =$step_info[0]->outflow;

        foreach ($step_data->result() as $step) {
            if ($step->cash_in_hand > $exp) {
                $buy = json_decode($step->buy);
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $data_insert = array('case_id'=>$step->id,
            'status'=>'survived',
            'round_id'=>3,
            'step_id'=>5,
            'action'=>1,
            'salary'=>$new_salary,
            'cash_in_hand' =>$step->cash_in_hand - ($exp),
            'summary' =>$step->summary.",Yes",
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'passive_income'=>$step->passive_income,
            'buy' =>json_encode($buy),
            'sell' =>$step->sell,
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                $buy = json_decode($step->buy);
                //--------- out entry ---------
                $new_salary = $step->salary;
                $new_exp = $step->expenditure+$exp;
                $data_insert = array('case_id'=>$step->id,
            'status'=>'out',
            'round_id'=>3,
            'step_id'=>5,
            'action'=>0,
            'salary'=>$new_salary,
            'cash_in_hand' =>$step->cash_in_hand - ($exp),
            'summary' =>$step->summary.",Yes",
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'passive_income'=>$step->passive_income,
            'buy' =>json_encode($buy),
            'sell' =>$step->sell,
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 3 step 6 (sell asian paint) ======================================
    public function r3step6()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 5,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 3,'step'=> 6))->result();
        $amount =$step_info[0]->inflow;

        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if (!empty($buy)) {
                if (in_array(6, $buy)) {
                    if (!empty($sell)) {
                        array_push($sell, 3);
                    } else {
                        $sell=array(3);
                    }
                    //--------- yes entry ---------
                    $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>6,
        'action'=>1,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand + ($amount),
        'summary' =>$step->summary.",Yes",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income,
        'sell'=>json_encode($sell),
        'status'=>'survived'
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $sell = json_decode($step->sell);
                    $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>6,
        'action'=>2,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand,
        'summary' =>$step->summary.",No",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income,
        'status'=>'survived',
        'sell'=>json_encode($sell),
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>6,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand,
        'summary' =>$step->summary,
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income,
        'status'=>'survived',
        'sell'=>json_encode($sell),
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            } else {
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>6,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'summary' =>$step->summary,
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'buy' =>json_encode($buy),
            'passive_income'=>$step->passive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 3 step 7 (sell commercial setup) ======================================
    public function r3step7()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 6,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 3,'step'=> 7))->result();
        $amount =$step_info[0]->inflow;
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if (!empty($buy)) {
                if (in_array(5, $buy)) {
                    if (!empty($sell)) {
                        array_push($sell, 4);
                    } else {
                        $sell=array(4);
                    }
                    //--------- yes entry ---------
                    //get yt buy details
                    $ct_buy = $this->db->get_where('tbl_features', array('round'=> 2,'step'=> 3))->result();
                    $loan_exp = $step->loan_exp-$ct_buy[0]->outflow;
                    $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>7,
        'action'=>1,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand + ($amount),
        'summary' =>$step->summary.",Yes",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income-$ct_buy[0]->inflow,
        'sell'=>json_encode($sell),
        'status'=>'survived'
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $sell = json_decode($step->sell);
                    $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>7,
        'action'=>2,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand,
      'summary' =>$step->summary.",No",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income,
        'status'=>'survived',
        'sell'=>json_encode($sell),
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $data_insert = array('case_id'=>$step->id,
        'round_id'=>3,
        'step_id'=>7,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand,
        'summary' =>$step->summary,
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income,
        'status'=>'survived',
        'sell'=>json_encode($sell),
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            } else {
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>7,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'passive_income'=>$step->passive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            'summary' =>$step->summary,
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 3 step 8 (Loan Repayment) ======================================
    public function r3step8()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 7,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 3,'step'=> 8))->result();
        $exp =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if ($step->cash_in_hand > $exp) {
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $loan_exp = $step->loan_exp-($exp * LOAN_PERCENTAGE /100);
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>3,
                'step_id'=>8,
                'action'=>1,
                'salary'=>$new_salary,
                'cash_in_hand' =>$step->cash_in_hand - ($exp),
                'summary' =>$step->summary.",Yes",
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'sell'=>json_encode($sell),
                'status'=>'survived'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                //--------- no entry ---------
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>3,
                'step_id'=>8,
                'action'=>2,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'passive_income'=>$step->passive_income,
                'summary' =>$step->summary.",No",
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$loan_exp,
                'buy' =>json_encode($buy),
                'sell'=>json_encode($sell),
                'status'=>'survived'
      );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                //--------- for Out of money ---------
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>3,
                'step_id'=>8,
                'action'=>0,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand - ($exp),
                'summary' =>$step->summary.",Yes",
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'sell'=>json_encode($sell),
                'status'=>'out'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //====================================================== END ROUND 3 ==========================================================

    //====================================================== START ROUND 4 ==========================================================
    //======================= round 4 step 2 (chance donation received) ======================================
    public function r4step2($CH)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 8,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 4,'step'=> 2))->result();
        $amount =$step_info[0]->inflow;
        foreach ($step_data->result() as $step) {
            $new_cash_in_hand = $step->cash_in_hand +$CH + $step->passive_income;
            //--------- yes entry ---------
            $data_insert = array('case_id'=>$step->id,
        'round_id'=>4,
        'step_id'=>2,
        'action'=>1,
        'salary'=>$step->salary,
        'cash_in_hand' =>$new_cash_in_hand + ($amount),
        'summary' =>$step->summary.",Yes",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'passive_income'=>$step->passive_income,
        'buy' =>$step->buy,
        'sell'=>$step->sell,
        'status'=>'survived'
        );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 4 step 3 (sell land) ======================================
    public function r4step3()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>4,'step_id'=> 2,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 4,'step'=> 3))->result();
        $amount =$step_info[0]->inflow;
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if (!empty($buy)) {
                if (in_array(9, $buy)) {
                    if (!empty($sell)) {
                        array_push($sell, 5);
                    } else {
                        $sell=array(5);
                    }
                    //--------- yes entry ---------
                    //get land buy details
                    $land_buy = $this->db->get_where('tbl_features', array('round'=> 3,'step'=> 4))->result();
                    $loan_exp = $step->loan_exp-$land_buy[0]->outflow;
                    $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>3,
        'action'=>1,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand + ($amount),
        'summary' =>$step->summary.",Yes",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income-$land_buy[0]->inflow,
        'sell'=>json_encode($sell),
        'status'=>'survived'
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $sell = json_decode($step->sell);
                    $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>3,
        'action'=>2,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand,
        'summary' =>$step->summary.",No",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income,
        'status'=>'survived',
        'sell'=>json_encode($sell),
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>3,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand,
          'summary' =>$step->summary,
          'personal_exp' =>$step->personal_exp,
          'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income,
        'status'=>'survived',
        'sell'=>json_encode($sell),
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            } else {
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>4,
            'step_id'=>3,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
              'summary' =>$step->summary,
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'buy' =>json_encode($buy),
            'passive_income'=>$step->passive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 4 step 4 (sell lab) ======================================
    public function r4step4()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>4,'step_id'=> 3,'status'=>'survived'));
        //---- get step info --
        $step_info = $this->db->get_where('tbl_features', array('round'=> 4,'step'=> 4))->result();
        $amount =$step_info[0]->inflow;
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if (!empty($buy)) {
                if (in_array(7, $buy)) {
                    if (!empty($sell)) {
                        array_push($sell, 6);
                    } else {
                        $sell=array(6);
                    }
                    //--------- yes entry ---------
                    //get land buy details
                    $lab_buy = $this->db->get_where('tbl_features', array('round'=> 3,'step'=> 2))->result();
                    $loan_exp = $step->loan_exp-$lab_buy[0]->outflow;
                    $data_insert = array('case_id'=>$step->id,
        'round_id'=>4,
        'step_id'=>4,
        'action'=>1,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand + ($amount),
        'summary' =>$step->summary.",Yes",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income -$lab_buy[0]->inflow,
        'sell'=>json_encode($sell),
        'status'=>'survived'
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $sell = json_decode($step->sell);
                    $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>4,
        'action'=>2,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand,
        'summary' =>$step->summary.",No",
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income,
        'status'=>'survived',
        'sell'=>json_encode($sell),
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>4,
        'salary'=>$step->salary,
        'cash_in_hand' =>$step->cash_in_hand,
          'summary' =>$step->summary,
        'personal_exp' =>$step->personal_exp,
        'loan_exp' =>$step->loan_exp,
        'buy' =>json_encode($buy),
        'passive_income'=>$step->passive_income,
        'status'=>'survived',
        'sell'=>json_encode($sell),
        );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            } else {
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>4,
            'step_id'=>4,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
              'summary' =>$step->summary,
            'personal_exp' =>$step->personal_exp,
            'loan_exp' =>$step->loan_exp,
            'buy' =>json_encode($buy),
            'passive_income'=>$step->passive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 4 step 5 (Loan Repayment) ======================================
    public function r4step5()
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>4,'step_id'=> 4,'status'=>'survived'));
        $step_info = $this->db->get_where('tbl_features', array('round'=> 4,'step'=> 5))->result();
        $exp =$step_info[0]->outflow;
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if ($step->cash_in_hand > $exp) {
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $new_cash = $step->cash_in_hand - ($exp);
                $loan_exp = $step->loan_exp-($exp * LOAN_PERCENTAGE /100);
                $total_exp = $loan_exp+$step->personal_exp;
                if ($total_exp < $step->passive_income) {
                    $status1= 'winner';
                } else {
                    $status1= 'losser';
                }
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>5,
                'action'=>1,
                'salary'=>$new_salary,
                'cash_in_hand' =>$new_cash,
                'summary' =>$step->summary.",Yes",
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'sell'=>json_encode($sell),
                'status'=>$status1
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                //--------- no entry ---------
                $total_exp2 = $step->loan_exp+$step->personal_exp;
                if ($total_exp2 < $step->passive_income) {
                    $status2= 'winner';
                } else {
                    $status2= 'losser';
                }
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>5,
                'action'=>2,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'passive_income'=>$step->passive_income,
                'summary' =>$step->summary.",No",
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'buy' =>json_encode($buy),
                'sell'=>json_encode($sell),
                'status'=>$status2
      );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                //--------- for Out of money ---------
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>5,
                'action'=>0,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand - ($exp),
                'summary' =>$step->summary.",Yes",
                'personal_exp' =>$step->personal_exp,
                'loan_exp' =>$step->loan_exp,
                'passive_income'=>$step->passive_income,
                'buy' =>json_encode($buy),
                'sell'=>json_encode($sell),
                'status'=>'out'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //============================================== END ROUND 4 ===============================================
    //===================================== SEARCH RESULT =====================
    public function search()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $string= $this->input->get('string');
            $data['game_data'] = $this->db->get_where('tbl_game_cases', array('action is NOT NULL'=> null, false,'summary'=>$string));
            $data['string'] = $string;

            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/play/search_result');
            $this->load->view('admin/common/footer_view');

        } else {
            redirect("login/admin_login", "refresh");
        }
    }
}
