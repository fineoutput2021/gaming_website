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
        $bp=12500;
        $this->r1step2(12500);// buy tcs stock
        $this->r1step3(10000, 5000);//---- buy youtube channel
        $this->r1step4(8000, 15000);// buy real state
        $this->r1step5(10000);// fixed medical expense
        $this->r1step6(10000);// fixed medical expense
        echo "yes";
    }
    //======================= round 1 step 2 ======================================
    public function r1step2($bp)
    {
        $salary = 50000;
        $PE = 20000;
        $LE = 20000;
        $exp = $PE + $LE;
        $CH = 10000;
        if ($CH >= $bp) {
            //------ yes entry -----
            $data_insert = array('round_id'=>1,
          'step_id'=>2,
          'action'=>1,
          'salary'=>$salary,
          'cash_in_hand' =>$CH - $bp,
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
    //======================= round 1 step 3 ======================================
    public function r1step3($in, $out)
    {
        $step_2_data = $this->db->get_where('tbl_game_cases', array('step_id'=> 2));
        foreach ($step_2_data->result() as $step2) {
            //--------- yes entry ---------
            $new_salary = $step2->salary+$in;
            $new_exp = $step2->expenditure+$out;
            $data_insert = array('case_id'=>$step2->id,
            'round_id'=>1,
            'step_id'=>3,
            'action'=>1,
            'salary'=>$new_salary,
            'cash_in_hand' =>$step2->cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //--------- no entry ---------
            $data_insert = array('case_id'=>$step2->id,
            'round_id'=>1,
            'step_id'=>3,
            'action'=>2,
            'salary'=>$step2->salary,
            'cash_in_hand' =>$step2->cash_in_hand,
            'expenditure' =>$step2->expenditure,
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
    }
    //======================= round 1 step 4 ======================================
    public function r1step4($in, $out)
    {
      for ($i=2; $i <=3; $i++) {
        $step_data = $this->db->get_where('tbl_game_cases', array('step_id'=> $i));
        foreach ($step_data->result() as $step) {
            //--------- yes entry ---------
            $new_salary = $step->salary+$in;
            $new_exp = $step->expenditure+$out;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>1,
            'step_id'=>4,
            'action'=>1,
            'salary'=>$new_salary,
            'cash_in_hand' =>$step->cash_in_hand + ($in-$out),
            'expenditure' =>$new_exp,
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
            //--------- no entry ---------
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>1,
            'step_id'=>4,
            'action'=>2,
            'salary'=>$step->salary,
            'cash_in_hand' =>$step->cash_in_hand,
            'expenditure' =>$step->expenditure,
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
    }
  }
    //======================= round 1 step 5 ======================================
    public function r1step5($exp)
    {
      for ($i=2; $i <=4; $i++) {
        $step_data = $this->db->get_where('tbl_game_cases', array('step_id'=> $i));
        foreach ($step_data->result() as $step) {
          if($step->cash_in_hand >= $exp){
            //--------- yes entry ---------
            $new_salary = $step->salary;
            $new_exp = $step->expenditure+$exp;
            $data_insert = array('case_id'=>$step->id,
            'round_id'=>1,
            'step_id'=>5,
            'action'=>1,
            'salary'=>$new_salary,
            'cash_in_hand' =>$step->cash_in_hand - ($exp),
            'expenditure' =>$new_exp,
            );
            $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
        }
      else{
        //--------- yes entry ---------
        $new_salary = $step->salary;
        $new_exp = $step->expenditure+$exp;
        $data_insert = array('case_id'=>$step->id,
        'round_id'=>1,
        'step_id'=>5,
        'action'=>0,
        'salary'=>$new_salary,
        'cash_in_hand' =>$step->cash_in_hand - ($exp),
        'expenditure' =>$new_exp,
        );
        $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
      }
    }
  }
  }
  //======================= round 1 step 6 ======================================
  public function r1step6($exp)
  {
    for ($i=2; $i <=5; $i++) {
      $step_data = $this->db->get_where('tbl_game_cases', array('step_id'=> $i));
      foreach ($step_data->result() as $step) {
        if($step->cash_in_hand >= $exp){
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
          );
          $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
          //--------- no entry ---------
          $data_insert = array('case_id'=>$step->id,
          'round_id'=>1,
          'step_id'=>6,
          'action'=>2,
          'salary'=>$step->salary,
          'cash_in_hand' =>$step->cash_in_hand,
          'expenditure' =>$step->expenditure,
          );
          $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
      }
      else{
        //--------- yes entry ---------
        $new_salary = $step->salary;
        $new_exp = $step->expenditure+$exp;
        $data_insert = array('case_id'=>$step->id,
        'round_id'=>1,
        'step_id'=>6,
        'action'=>0,
        'salary'=>$new_salary,
        'cash_in_hand' =>$step->cash_in_hand - ($exp),
        'expenditure' =>$new_exp,
        );
        $last_id=$this->base_model->insert_table("tbl_game_cases", $data_insert, 1) ;
      }
  }
}
}
}
