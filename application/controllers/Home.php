<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/login_model");
        $this->load->model("admin/base_model");
    }
    public function index()
    {
        $this->load->view('index');
    }

    public function error404()
    {
        $this->load->view('errors/error404');
    }
    public function round1()
    {
        $this->db->truncate('tbl_game_cases');// --------- table truncate
        //--------------- round 1---------------------------
        $this->r1step2(8000);// buy tcs stock
        $this->r1step3(10000, 5000);//---- buy youtube channel
        $this->r1step4(8000, 15000);// buy real state
        $this->r1step5(10000);// fixed medical expense
        $this->r1step6(10000);// Loan Repayment

      //--------------- round 2---------------------------
        $this->r2step2(15000, 25000);// buy factory setup
        $this->r2step3(15000, 10000);// buy commercial setup
        $this->r2step4(5000);// buy stock asian paints
        $this->r2step5(15000);// gift
        $this->r2step6(50000);// sell youtube channel
        $this->r2step7(14000);// sell sell tcs stock
        $this->r2step8(20000);// sell Loan Repayment
      //--------------- round 3---------------------------

        $this->r3step2(20000, 10000);// buylab
        echo "yes";
    }
    //====================================================== START ROUND 1 ==========================================================
    //======================= round 1 step 2 (buy tcs stock) ======================================
    public function r1step2($bp)
    {
        $salary = 50000;
        $PE = 20000;
        $LE = 20000;
        $exp = $PE + $LE;
        $CH = 10000;
        if ($CH >= $bp) {
            //------ yes entry -----
            $buy=array(1);
            $data_insert = array('round_id'=>1,
          'step_id'=>2,
          'action'=>1,
          'salary'=>$salary,
          'cash_in_hand' =>$CH - $bp,
          'buy' =>json_encode($buy),
          'expenditure' =>$exp,
          );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //------ no entry -----
            $data_insert = array('round_id'=>1,
          'step_id'=>2,
          'action'=>2,
          'salary'=>$salary,
          'cash_in_hand' =>$CH,
          'expenditure' =>$exp,
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
            'expenditure' =>$exp,
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 1 step 3 (buy youtube channel) ======================================
    public function r1step3($in, $out)
    {
        $step_2_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 2))->result();
        foreach ($step_2_data as $step2) {
            //--------- yes entry ---------
            $buy = json_decode($step2->buy);
            if (!empty($buy)) {
                array_push($buy, 2);
            } else {
                $buy=array(2);
            }
            $new_exp = $step2->expenditure+$out;
            $data_insert = array('case_id'=>$step2->id,
            'round_id'=>1,
            'step_id'=>3,
            'action'=>1,
            'salary'=>$step2->salary,
            'pasive_income'=>$in-$out,
            'cash_in_hand' =>$step2->cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
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
            'expenditure' =>$step2->expenditure,
            'buy' =>json_encode($buy),
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
    }
    //======================= round 1 step 4 (buy real state) ======================================
    public function r1step4($in, $out)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 3));
        foreach ($step_data->result() as $step) {
            //--------- yes entry ---------
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                array_push($buy, 3);
            } else {
                $buy=array(3);
            }
            $new_exp = $step->expenditure+$out;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>1,
            'step_id'=>4,
            'action'=>1,
            'salary'=>$step->salary,
            'pasive_income'=>$step->pasive_income-$in-$out,
            'cash_in_hand' =>$step->cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
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
            'pasive_income'=>$step->pasive_income,
            'expenditure' =>$step->expenditure,
            'buy' =>json_encode($buy),
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
    }
    //======================= round 1 step 5 (fixed medical expense)======================================
    public function r1step5($exp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 4));
        foreach ($step_data->result() as $step) {
            if ($step->cash_in_hand > $exp) {
                $buy = json_decode($step->buy);
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $new_exp = $step->expenditure+$exp;
                $data_insert = array('case_id'=>$step->id,
                'status'=>'survived',
                'round_id'=>1,
                'step_id'=>5,
                'action'=>1,
                'salary'=>$new_salary,
                'cash_in_hand' =>$step->cash_in_hand - ($exp),
                'expenditure' =>$new_exp,
                'pasive_income'=>$step->pasive_income,
                'buy' =>json_encode($buy),
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                $buy = json_decode($step->buy);
                //--------- out entry ---------
                $new_salary = $step->salary;
                $new_exp = $step->expenditure+$exp;
                $data_insert = array('case_id'=>$step->id,
                'status'=>'out',
                'round_id'=>1,
                'step_id'=>5,
                'action'=>0,
                'salary'=>$new_salary,
                'cash_in_hand' =>$step->cash_in_hand - ($exp),
                'expenditure' =>$new_exp,
                'pasive_income'=>$step->pasive_income,
                'buy' =>json_encode($buy),
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
    }
    //======================= round 1 step 6 (Loan Repayment) ======================================
    public function r1step6($exp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 5));
        foreach ($step_data->result() as $step) {
            if ($step->status=='survived') {
                $buy = json_decode($step->buy);
                if ($step->cash_in_hand >= $exp) {
                    //--------- yes entry ---------
                    $new_salary = $step->salary;
                    $new_exp = $step->expenditure+$exp;
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>1,
                    'step_id'=>6,
                    'action'=>1,
                    'salary'=>$new_salary,
                    'cash_in_hand' =>$step->cash_in_hand - ($exp),
                    'expenditure' =>$new_exp,
                    'pasive_income'=>$step->pasive_income,
                    'buy' =>json_encode($buy),
                    );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- no entry ---------
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>1,
                    'step_id'=>6,
                    'action'=>2,
                    'salary'=>$step->salary,
                    'cash_in_hand' =>$step->cash_in_hand,
                    'pasive_income'=>$step->pasive_income,
                    'expenditure' =>$step->expenditure,
                    'buy' =>json_encode($buy),
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
                    'expenditure' =>$step->expenditure,
                    'pasive_income'=>$step->pasive_income,
                    'buy' =>json_encode($buy),
                    );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            }
        }
    }
    //====================================================== END ROUND 1 ==========================================================

    //====================================================== START ROUND 2 ==========================================================
    //======================= round 2 step 2 (buy factory setup) ======================================
    public function r2step2($in, $out)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 6));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                array_push($buy, 4);
            } else {
                $buy=array(4);
            }
            //--------- yes entry ---------
            $new_cash_in_hand = $step->cash_in_hand +10000 + $step->pasive_income;
            $new_exp = $step->expenditure+$out;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>2,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            'pasive_income'=>$step->pasive_income-$in-$out,
            'buy' =>json_encode($buy),
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
            'expenditure' =>$step->expenditure,
            'pasive_income'=>$step->pasive_income,
            'buy' =>json_encode($buy),
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
    }
    //======================= round 2 step 3 (buy commercial setup) ======================================
    public function r2step3($in, $out)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 2));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                array_push($buy, 5);
            } else {
                $buy=array(5);
            }
            //--------- yes entry ---------
            $new_cash_in_hand = $step->cash_in_hand;
            $new_exp = $step->expenditure+$out;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>3,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            'pasive_income'=>$step->pasive_income-$in-$out,
            'buy' =>json_encode($buy),
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
            'expenditure' =>$step->expenditure,
            'pasive_income'=>$step->pasive_income,
            'buy' =>json_encode($buy),
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
    }
    //======================= round 2 step 4 (buy stock asian paints) ======================================
    public function r2step4($bp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 3));
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
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>4,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand - ($bp),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
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
            'expenditure' =>$step->expenditure,
            'pasive_income'=>$step->pasive_income,
            'buy' =>json_encode($buy),
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
          'expenditure' =>$step->expenditure,
          'pasive_income'=>$step->pasive_income,
          'buy' =>json_encode($buy),
            'status'=>'out',
          );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
    }
    //======================= round 2 step 5 (Gift) ======================================
    public function r2step5($gift)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 4));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $status = $step->status;
            if ($status == 'survived') {
                //--------- yes entry ---------
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>5,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($gift),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
    }
    //======================= round 2 step 6 (sell youtube channel) ======================================
    public function r2step6($amount)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 5));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            if (in_array(2, $buy)) {
                $sell = array(1);
                //--------- yes entry ---------
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>6,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($amount),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income-5000,
            'sell'=>json_encode($sell)
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                //--------- No entry ---------
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>6,
            'action'=>2,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>6,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
    }
    //======================= round 2 step 7 (sell tcs stock) ======================================
    public function r2step7($amount)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 6));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if (in_array(1, $buy)) {
                if (!empty($sell)) {
                    array_push($sell, 2);
                } else {
                    $sell=array(2);
                }
                //--------- yes entry ---------
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>7,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($amount),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income-7000,
            'sell'=>json_encode($sell)
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                //--------- No entry ---------
                $sell = json_decode($step->sell);
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>7,
            'action'=>2,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'sell'=>json_encode($sell)
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>7,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'sell'=>json_encode($sell)
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
    }
    //======================= round 2 step 7 (Loan Repayment) ======================================
    public function r2step8($exp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 7));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if ($step->cash_in_hand > $exp) {
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $new_exp = $step->expenditure+$exp;
                $data_insert = array('case_id'=>$step->id,
                    'round_id'=>2,
                    'step_id'=>8,
                    'action'=>1,
                    'salary'=>$new_salary,
                    'cash_in_hand' =>$step->cash_in_hand - ($exp),
                    'expenditure' =>$new_exp,
                    'pasive_income'=>$step->pasive_income,
                    'buy' =>json_encode($buy),
                    'sell'=>json_encode($sell),
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
                    'pasive_income'=>$step->pasive_income,
                    'expenditure' =>$step->expenditure,
                    'buy' =>json_encode($buy),
                    'sell'=>json_encode($sell),
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
                    'expenditure' =>$step->expenditure,
                    'pasive_income'=>$step->pasive_income,
                    'buy' =>json_encode($buy),
                    'sell'=>json_encode($sell),
                    'status'=>'out'
                    );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
    }
    //====================================================== END ROUND 2 ==========================================================


    //====================================================== START ROUND 3 ==========================================================
    //======================= round 3 step 2 (buy lab) ======================================
    public function r3step2($in, $out)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 8));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if (!empty($buy)) {
                array_push($buy, 7);
            } else {
                $buy=array(7);
            }
            //--------- yes entry ---------
            $new_cash_in_hand = $step->cash_in_hand +10000 + $step->pasive_income;
            $new_exp = $step->expenditure+$out;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>3,
            'step_id'=>2,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            'pasive_income'=>$step->pasive_income-$in-$out,
            'buy' =>json_encode($buy),
            'sell'=>json_encode($sell),
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
            'expenditure' =>$step->expenditure,
            'pasive_income'=>$step->pasive_income,
            'buy' =>json_encode($buy),
            'sell'=>json_encode($sell),
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
    }
}
