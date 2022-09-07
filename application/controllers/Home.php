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
    public function play()
    {
        $this->db->truncate('tbl_game_cases');// --------- table truncate
        $salary = 80000;
        $exp = 50000;
        $CH = $salary-$exp;
        //--------------- round 1---------------------------
        $this->r1step2(12500);// buy tcs stock
        $this->r1step3(10000, 5000);//---- buy youtube channel
        $this->r1step4(8000, 15000);// buy real state
        $this->r1step5(10000);// fixed medical expense
        $this->r1step6(10000);// Loan Repayment

      //--------------- round 2---------------------------
        $this->r2step2(15000, 25000, $CH);// buy factory setup
        $this->r2step3(15000, 10000);// buy commercial setup
        $this->r2step4(5000);// buy stock asian paints
        $this->r2step5(15000);// gift
        $this->r2step6(50000);// sell youtube channel
        $this->r2step7(14000);// sell sell tcs stock
        $this->r2step8(20000);//  Loan Repayment

      //--------------- round 3---------------------------
        // $this->r3step2(20000, 10000);// buy lab
        // $this->r3step3(4000);//  buy reliance stock
        // $this->r3step4(0, 5000);//  buy land
        // $this->r3step5(5000);// fixed child expense
        // $this->r3step6(25000);// sell stock asian paints
        // $this->r3step7(150000);// sell commercial setup
        // $this->r3step8(20000);//  Loan Repayment
        //
        // //--------------- round 4 ---------------------------
        //   $this->r4step2(5000);// chance donation received
        //   $this->r4step3(150000);// sell land
        //   $this->r4step4(150000);// sell lab
        //   $this->r4step5(20000);//  Loan Repayment

        //-------- result ---------
        $no_of_cases = $this->db->get_where('tbl_game_cases', array('status'=>'survived','action is NOT NULL'=> null, false))->num_rows();

        echo "Success! Check records <br />";
        echo "Number of Cases :- ".$no_of_cases;
    }
    //====================================================== START ROUND 1 ==========================================================
    //======================= round 1 step 2 (buy tcs stock) ======================================
    public function r1step2($bp)
    {
        $salary = 80000;
        $exp = 50000;
        $CH = $salary-$exp;
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
          'status'=>'survived'
          );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //------ no entry -----
            $data_insert = array('round_id'=>1,
          'step_id'=>2,
          'action'=>2,
          'salary'=>$salary,
          'cash_in_hand' =>$CH,
          'expenditure' =>$exp,
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
            'expenditure' =>$exp,
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 1 step 3 (buy youtube channel) ======================================
    public function r1step3($in, $out)
    {
        $step_2_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 2,'status'=>'survived'))->result();
        foreach ($step_2_data as $step2) {
            //-----step  history ----
            $history=array($step2->action);

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
            'history' =>json_encode($history),
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
            'expenditure' =>$step2->expenditure,
            'buy' =>json_encode($buy),
            'history' =>json_encode($history),
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 1 step 4 (buy real state) ======================================
    public function r1step4($in, $out)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 3,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);
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
            'pasive_income'=> $step->pasive_income+($in-$out),
            'cash_in_hand' =>$step->cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'history' =>json_encode($history),
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
            'pasive_income'=>$step->pasive_income,
            'expenditure' =>$step->expenditure,
            'buy' =>json_encode($buy),
            'history' =>json_encode($history),
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 1 step 5 (fixed medical expense)======================================
    public function r1step5($exp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 4,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);
            if ($step->cash_in_hand > $exp) {
                $buy = json_decode($step->buy);
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $new_exp = $step->expenditure;
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
                'history' =>json_encode($history),

                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            } else {
                $buy = json_decode($step->buy);
                //--------- out entry ---------
                $new_salary = $step->salary;
                $new_exp = $step->expenditure;
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
                'history' =>json_encode($history),

                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 1 step 6 (Loan Repayment) ======================================
    public function r1step6($exp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 5,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);
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
                    'history' =>json_encode($history),
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
                    'pasive_income'=>$step->pasive_income,
                    'expenditure' =>$step->expenditure,
                    'buy' =>json_encode($buy),
                    'history' =>json_encode($history),
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
                    'expenditure' =>$step->expenditure,
                    'pasive_income'=>$step->pasive_income,
                    'buy' =>json_encode($buy),
                    'history' =>json_encode($history),
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
    public function r2step2($in, $out, $ch)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>1,'step_id'=> 6,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                array_push($buy, 4);
            } else {
                $buy=array(4);
            }
            //--------- yes entry ---------
            $new_cash_in_hand = $step->cash_in_hand + $ch + $step->pasive_income;
            $new_exp = $step->expenditure+$out;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>2,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            'pasive_income'=>$step->pasive_income+($in-$out),
            'buy' =>json_encode($buy),
            'history' =>json_encode($history),

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
            'expenditure' =>$step->expenditure,
            'pasive_income'=>$step->pasive_income,
            'buy' =>json_encode($buy),
            'history' =>json_encode($history),

            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 2 step 3 (buy commercial setup) ======================================
    public function r2step3($in, $out)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 2,'status'=>'survived'));
        foreach ($step_data->result() as $step) {  //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);

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
            'pasive_income'=>$step->pasive_income+($in-$out),
            'buy' =>json_encode($buy),
            'history' =>json_encode($history),

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
            'expenditure' =>$step->expenditure,
            'pasive_income'=>$step->pasive_income,
            'buy' =>json_encode($buy),
            'history' =>json_encode($history),

            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 2 step 4 (buy stock asian paints) ======================================
    public function r2step4($bp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 3,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);
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
            'history' =>json_encode($history),

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
            'history' =>json_encode($history),

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
          'history' =>json_encode($history),

            'status'=>'out',
          );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 2 step 5 (Gift) ======================================
    public function r2step5($gift)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 4,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);
            $buy = json_decode($step->buy);
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
            'history' =>json_encode($history),

            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 2 step 6 (sell youtube channel) ======================================
    public function r2step6($amount)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 5,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                if (in_array(2, $buy)) {
                    $sell = array(1);
                    //--------- yes entry ---------
                    $new_exp = $step->expenditure-5000;
                    $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>6,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($amount),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income-(10000-5000),
            'sell'=>json_encode($sell),
            'history' =>json_encode($history),

            'status'=>'survived'
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
            'history' =>json_encode($history),

            'pasive_income'=>$step->pasive_income,
            'status'=>'survived'
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
            'history' =>json_encode($history),

            'pasive_income'=>$step->pasive_income,
            'status'=>'survived'
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            } else {
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>2,
                'step_id'=>6,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'expenditure' =>$new_exp,
                'buy' =>json_encode($buy),
                'history' =>json_encode($history),

                'pasive_income'=>$step->pasive_income,
                'status'=>'survived'
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 2 step 7 (sell tcs stock) ======================================
    public function r2step7($amount)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 6,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);
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
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
            'round_id'=>2,
            'step_id'=>7,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($amount),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'sell'=>json_encode($sell),
            'history' =>json_encode($history),

            'status'=>'survived'
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
            'sell'=>json_encode($sell),
            'history' =>json_encode($history),

            'status'=>'survived'
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
                'sell'=>json_encode($sell),
                'history' =>json_encode($history),

                'status'=>'survived'
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
            'pasive_income'=>$step->pasive_income,
            'sell'=>json_encode($sell),
            'history' =>json_encode($history),

            'status'=>'survived'
            );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 2 step 7 (Loan Repayment) ======================================
    public function r2step8($exp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 7,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //-----step  history ----
            $history = json_decode($step->history);
            array_push($history, $step->action);
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
                    'history' =>json_encode($history),

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
                    'history' =>json_encode($history),

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
                    'history' =>json_encode($history),

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
    public function r3step2($in, $out)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>2,'step_id'=> 8,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            $new_cash_in_hand = $step->cash_in_hand +10000 + $step->pasive_income;
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if ($new_cash_in_hand>0) {
                if (!empty($buy)) {
                    array_push($buy, 7);
                } else {
                    $buy=array(7);
                }
                //--------- yes entry ---------
                $new_exp = $step->expenditure+$out;
                $data_insert = array('case_id'=>$step->id,
            'round_id'=>3,
            'step_id'=>2,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            'pasive_income'=>$step->pasive_income+($in-$out),
            'buy' =>json_encode($buy),
            'sell'=>json_encode($sell),
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
            'expenditure' =>$step->expenditure,
            'pasive_income'=>$step->pasive_income,
            'buy' =>json_encode($buy),
            'sell'=>json_encode($sell),
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
        'expenditure' =>$step->expenditure,
        'pasive_income'=>$step->pasive_income,
        'buy' =>json_encode($buy),
        'sell'=>json_encode($sell),
        'status'=>'out'
        );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 3 step 3 (buy reliance stock) ======================================
    public function r3step3($bp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 2,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            $salary = $step->salary;
            $CH = $step->cash_in_hand;
            $exp = $step->expenditure;
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
              'expenditure' =>$exp,
              'pasive_income'=>$step->pasive_income,
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
              'expenditure' =>$exp,
              'pasive_income'=>$step->pasive_income,
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
                'expenditure' =>$exp,
                'sell'=>json_encode($sell),
                'pasive_income'=>$step->pasive_income,
                'status'=>'survived'

                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }

        return;
    }
    //======================= round 3 step 4 (buy land) ======================================
    public function r3step4($in, $out)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 3,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            if (!empty($buy)) {
                array_push($buy, 9);
            } else {
                $buy=array(9);
            }
            //--------- yes entry ---------
            $new_cash_in_hand = $step->cash_in_hand;
            $new_exp = $step->expenditure+$out;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>3,
            'step_id'=>4,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$new_cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            'pasive_income'=>$step->pasive_income+($in-$out),
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
            'expenditure' =>$step->expenditure,
            'pasive_income'=>$step->pasive_income,
            'buy' =>json_encode($buy),
            'sell' =>$step->sell,
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 3 step 5 (fixed child expense)======================================
    public function r3step5($exp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 4,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            if ($step->cash_in_hand > $exp) {
                $buy = json_decode($step->buy);
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $new_exp = $step->expenditure+$exp;
                $data_insert = array('case_id'=>$step->id,
                'status'=>'survived',
                'round_id'=>3,
                'step_id'=>5,
                'action'=>1,
                'salary'=>$new_salary,
                'cash_in_hand' =>$step->cash_in_hand - ($exp),
                'expenditure' =>$new_exp,
                'pasive_income'=>$step->pasive_income,
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
                'expenditure' =>$new_exp,
                'pasive_income'=>$step->pasive_income,
                'buy' =>json_encode($buy),
                'sell' =>$step->sell,
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 3 step 6 (sell asian paint) ======================================
    public function r3step6($amount)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 5,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
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
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
            'round_id'=>3,
            'step_id'=>6,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($amount),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'sell'=>json_encode($sell),
            'status'=>'survived'
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $sell = json_decode($step->sell);
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
            'round_id'=>3,
            'step_id'=>6,
            'action'=>2,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
            'round_id'=>3,
            'step_id'=>6,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
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
                'status'=>'survived',
                'sell'=>json_encode($sell),
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 3 step 7 (sell commercial setup) ======================================
    public function r3step7($amount)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 6,'status'=>'survived'));
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
                    $new_exp = $step->expenditure-10000;
                    $data_insert = array('case_id'=>$step->id,
            'round_id'=>3,
            'step_id'=>7,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($amount),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income-(15000-10000),
            'sell'=>json_encode($sell),
            'status'=>'survived'
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $sell = json_decode($step->sell);
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
            'round_id'=>3,
            'step_id'=>7,
            'action'=>2,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
            'round_id'=>3,
            'step_id'=>7,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
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
                'pasive_income'=>$step->pasive_income,
                'status'=>'survived',
                'sell'=>json_encode($sell),
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 3 step 8 (Loan Repayment) ======================================
    public function r3step8($exp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 7,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if ($step->cash_in_hand > $exp) {
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $new_exp = $step->expenditure+$exp;
                $data_insert = array('case_id'=>$step->id,
                    'round_id'=>3,
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
                    'round_id'=>3,
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
                    'round_id'=>3,
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
        return;
    }
    //====================================================== END ROUND 3 ==========================================================

    //====================================================== START ROUND 4 ==========================================================
    //======================= round 4 step 2 (chance donation received) ======================================
    public function r4step2($amount)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>3,'step_id'=> 8,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            //--------- yes entry ---------
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>4,
            'step_id'=>2,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($amount),
            'expenditure' =>$step->expenditure,
            'pasive_income'=>$step->pasive_income,
            'buy' =>$step->buy,
            'sell'=>$step->sell,
            'status'=>'survived'
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
        return;
    }
    //======================= round 4 step 3 (sell land) ======================================
    public function r4step3($amount)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>4,'step_id'=> 2,'status'=>'survived'));
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
                    $new_exp = $step->expenditure-5000;
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>4,
                    'step_id'=>3,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($amount),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income -(0-5000),
            'sell'=>json_encode($sell),
            'status'=>'survived'
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $sell = json_decode($step->sell);
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>4,
                    'step_id'=>3,
            'action'=>2,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>4,
                    'step_id'=>3,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            } else {
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>3,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'expenditure' =>$new_exp,
                'buy' =>json_encode($buy),
                'pasive_income'=>$step->pasive_income,
                'status'=>'survived',
                'sell'=>json_encode($sell),
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 4 step 4 (sell lab) ======================================
    public function r4step4($amount)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>4,'step_id'=> 3,'status'=>'survived'));
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
                    $new_exp = $step->expenditure-10000;
                    $data_insert = array('case_id'=>$step->id,
            'round_id'=>4,
            'step_id'=>4,
            'action'=>1,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand + ($amount),
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income -(20000-10000),
            'sell'=>json_encode($sell),
            'status'=>'survived'
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                    //--------- No entry ---------
                    $sell = json_decode($step->sell);
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>4,
                    'step_id'=>4,
            'action'=>2,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                } else {
                    $new_exp = $step->expenditure;
                    $data_insert = array('case_id'=>$step->id,
                    'round_id'=>4,
                    'step_id'=>4,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$new_exp,
            'buy' =>json_encode($buy),
            'pasive_income'=>$step->pasive_income,
            'status'=>'survived',
            'sell'=>json_encode($sell),
            );
                    $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
                }
            } else {
                $new_exp = $step->expenditure;
                $data_insert = array('case_id'=>$step->id,
                'round_id'=>4,
                'step_id'=>4,
                'salary'=>$step->salary,
                'cash_in_hand' =>$step->cash_in_hand,
                'expenditure' =>$new_exp,
                'buy' =>json_encode($buy),
                'pasive_income'=>$step->pasive_income,
                'status'=>'survived',
                'sell'=>json_encode($sell),
                );
                $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            }
        }
        return;
    }
    //======================= round 4 step 5 (Loan Repayment) ======================================
    public function r4step5($exp)
    {
        $step_data = $this->db->get_where('tbl_game_cases', array('round_id'=>4,'step_id'=> 4,'status'=>'survived'));
        foreach ($step_data->result() as $step) {
            $buy = json_decode($step->buy);
            $sell = json_decode($step->sell);
            if ($step->cash_in_hand > $exp) {
                //--------- yes entry ---------
                $new_salary = $step->salary;
                $new_exp = $step->expenditure+$exp;
                $data_insert = array('case_id'=>$step->id,
                    'round_id'=>4,
                    'step_id'=>5,
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
                'round_id'=>4,
                'step_id'=>5,
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
                'round_id'=>4,
                'step_id'=>5,
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
        return;
    }
    //====================================================== END ROUND 4 ==========================================================
}
