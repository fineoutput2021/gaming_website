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
        $salary = 50000;
        $PE = 20000;
        $LE = 20000;
        $CH = 10000;

        for ($SII=0;$SII<=1;$SII++) {
					if($SII=1){
					$CH = $CH - 12500;
					}
            for ($SIII=0;$SIII<=1;$SIII++) {
                for ($SIV=0;$SIV<=1;$SIV++) {
									for ($SVI=0;$SVI<=1;$SVI++) {
	                }
                }
            }
        }
    }
}
