<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class CI_finecontrol extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!empty($this->session->userdata('admin_data'))) {
            $name = $this->session->userdata('adminname');
            $image=$this->session->userdata('image');
            $postion=$this->session->userdata('position');
            $user_d=$this->session->userdata('admin_id');

            $this->db->select('*');
            $this->db->from('tbl_team');
            $this->db->where('id', $user_d);
            $dsa= $this->db->get();
            $da=$dsa->row();
            $ser=json_decode($da->services);

            foreach ($ser as $ss) {
                if ($ss==999) {
                    $a_ser=1;
                    $this->db->select('*');
                    $this->db->from('tbl_admin_sidebar');
                    // $this->db->where('id',$ss);
                    $this->db->order_by('sequence', 'asc');
                    $dsa= $this->db->get();
                    foreach ($dsa->result() as $dda) {
                        $ss=$dda->id;
                        $n1=$dda->name;
                        $u=$dda->url;
                        $dam[] = array('name' =>$n1,'id' =>$ss,'url' =>$u);
                    }
                } else {
                    $a_ser=2;
                    // echo $ss;
                    $this->db->select('*');
                    $this->db->from('tbl_admin_sidebar');
                    $this->db->where('id', $ss);
                    $this->db->order_by('sequence', 'asc');
                    $dda= $this->db->get()->row();
                    // $da=$dsa->row();
                    $ss=$dda->id;
                    $n1=$dda->name;
                    $u=$dda->url;
                    $dam[] = array('name' =>$n1,'id' =>$ss,'url' =>$u);
                }
            }


            $global_data = array('user_name'=>$name,
                            'image'=>$image,
                            'position'=>$postion,
                            'sidebar'=>$dam
                                    );





            $this->load->vars($global_data);
        }
    }
}
