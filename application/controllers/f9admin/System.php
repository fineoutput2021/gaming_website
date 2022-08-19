<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once(APPPATH . 'core/CI_finecontrol.php');
class System extends CI_finecontrol
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("login_model");
        $this->load->model("admin/base_model");
        $this->load->library('user_agent');
    }


    // profile data

    public function profile()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $data['user_name']=$this->load->get_var('user_name');

            $usr=$this->session->userdata('admin_id');



            $this->db->select('*');
            $this->db->from('tbl_team');
            $this->db->where('id', $usr);
            $data['profile_data']= $this->db->get();


            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/profile/view_profile');
            $this->load->view('admin/common/footer_view');
        } else {
            $this->load->view('admin/login/index');
        }
    }

    // Change Password

    public function change_pass()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $data['user_name']=$this->load->get_var('user_name');
            // $this->load->get_var('user_id');
            $usr_id=$this->session->userdata('admin_id');
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->load->helper('security');
            if ($this->input->post()) {
                // print_r($this->input->post());
                // exit;
                $this->form_validation->set_rules('old', 'Old Password', 'required|xss_clean');
                $this->form_validation->set_rules('new', 'New Password', 'required|xss_clean');
                // $this->form_validation->set_rules('college_type', 'University Type', 'required');
                if ($this->form_validation->run()== true) {
                    $old=$this->input->post('old');
                    $new=$this->input->post('new');
                    $new2=md5($new);
                    $this->db->select('*');
                    $this->db->from('tbl_team');
                    $this->db->where('id', $usr_id);
                    $dsa= $this->db->get();
                    $da=$dsa->row();

                    $p1=$da->password;
                    // echo $p1."------".$old;die();
                    if (md5($old)==$p1) {
                        $data_update = array(
                                      'password'=>$new2,
                                      );

                        $this->db->where('id', $usr_id);
                        $zapak=$this->db->update('tbl_team', $data_update);

                        if ($zapak!=0) {
                            // redirect("admin/home/view_team","refresh");
                            echo "<div class='alert alert-success'>
	  <strong>Success!</strong> Password Changed Successfully
	</div>";
                        } else {
                            echo "Error";
                            // exit;
                        }
                    } else {
                        echo "<div class='alert alert-danger'>
	  <strong>Error!</strong> Wrong Password
	</div>";
                    }
                } else {
                    echo validation_errors();
                    // exit;
                }
            } else {
                echo "No data Entered";
                // exit;
            }






            // $this->load->view('admin/common/header_view',$data);
                       // $this->load->view('admin/dash');
                       // $this->load->view('admin/common/footer_view');
        } else {
            $this->load->view('admin/login/index');
        }
    }




    public function check_pass()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $data['user_name']=$this->load->get_var('user_name');

            echo "hiiii";
        // echo SITE_NAME;
                   // echo $this->session->userdata('image');
                   // echo $this->session->userdata('position');
                   // exit;


                   // $this->load->view('admin/common/header_view',$data);
                   // $this->load->view('admin/dash');
                   // $this->load->view('admin/common/footer_view');
        } else {
            $this->load->view('admin/login/index');
        }
    }


    public function view_team()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $this->db->select('*');
            $this->db->from('tbl_team');
            // $this->db->where('student_shift',$cvf);
            $data['team_data']= $this->db->get();




            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/team/view_team');
            $this->load->view('admin/common/footer_view');
        } else {
            $this->load->view('admin/login/index');
        }
    }


    public function add_team()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $this->db->select('*');
            $this->db->from('tbl_admin_sidebar');
            // $this->db->where('student_shift',$cvf);
            $data['side']= $this->db->get();




            $this->load->view('admin/common/header_view', $data);
            $this->load->view('admin/team/add_team');
            $this->load->view('admin/common/footer_view');
        } else {
            $this->load->view('admin/login/index');
        }
    }



    public function add_team_data()
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $this->load->helper(array('form', 'url'));

            $this->load->library('form_validation');

            $this->load->helper('security');
            $this->load->library('upload');
            if ($this->input->post()) {
                // print_r($this->input->post());
                // exit;
                $this->form_validation->set_rules('name', 'name', 'required|customAlpha|xss_clean');
                $this->form_validation->set_rules('email', 'email', 'required|valid_email|xss_clean|trim|is_unique[tbl_team.email]');
                $this->form_validation->set_rules('password', 'password Number', 'required|xss_clean');
                $this->form_validation->set_rules('phone', 'Phone', 'xss_clean|min_length[10]|max_length[10]');
                $this->form_validation->set_rules('address', 'address', 'xss_clean|trim');

                if ($this->form_validation->run()== true) {
                    $name=$this->input->post('name');
                    $phone=$this->input->post('phone');
                    $address=$this->input->post('address');
                    $email=$this->input->post('email');
                    $position=$this->input->post('power');
                    $service=$this->input->post('service');
                    $services=$this->input->post('services');
                    $password=$this->input->post('password');
                    $img1='fileToUpload1';

                    if ($service==999) {
                        $ser='["999"]';
                    } else {
                        $ser=json_encode($services);
                    }


                    // exit;
                    $file_check=($_FILES['fileToUpload1']['error']);
                    if ($file_check!=4) {
                        $image_upload_folder = FCPATH . "assets/uploads/team/";
                        if (!file_exists($image_upload_folder)) {
                            mkdir($image_upload_folder, DIR_WRITE_MODE, true);
                        }
                        $new_file_name="team".date("Ymdhms");
                        $this->upload_config = array(
                                'upload_path'   => $image_upload_folder,
                                'file_name' => $new_file_name,
                                'allowed_types' =>'jpg|jpeg|png',
                                'max_size'      => 25000
                        );
                        $this->upload->initialize($this->upload_config);
                        if (!$this->upload->do_upload($img1)) {
                            $upload_error = $this->upload->display_errors();
                            // echo json_encode($upload_error);

                            $this->session->set_flashdata('emessage', $upload_error);
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $file_info = $this->upload->data();

                            $videoNAmePath = "assets/uploads/team/".$new_file_name.$file_info['file_ext'];
                            $file_info['new_name']=$videoNAmePath;
                            // $this->step6_model->updateappIconImage($imageNAmePath,$appInfoId);
                            $nnnn=$file_info['file_name'];
                            // echo json_encode($file_info);
                        }
                    }
                    $ip = $this->input->ip_address();
                    date_default_timezone_set("Asia/Calcutta");
                    $cur_date=date("Y-m-d H:i:s");

                    $addedby=$this->session->userdata('admin_id');

                    // $addedby=1;
                    $pass=md5($password);

                    if (!empty($nnnn)) {
                        $nnn=$nnnn;
                    } else {
                        $nnn="";
                    }


                    $data_insert = array('name'=>$name,
                                                    'phone'=>$phone,
                                                    'address'=>$address,
                                                    'email'=>$email,
                                                    'password'=>$pass,
                                                    'power'=>$position,
                                                    'services'=>$ser,
                                                    'image'=>$nnn,
                                                    'ip' =>$ip,
                                                    'added_by' =>$addedby,
                                                    'is_active' =>1,
                                                    'date'=>$cur_date

                                                    );





                    $last_id=$this->base_model->insert_table("tbl_team", $data_insert, 1) ;


                    if ($last_id!=0) {
                        redirect("admin/system/view_team", "refresh");
                    } else {
                        $this->session->set_flashdata('emessage', 'Error Occured in data insert, Please try again');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                } // VALIDATION PART ENDS
                else {
                    $this->session->set_flashdata('emessage', validation_errors());
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } // POST DATA ENDS
            else {
                $this->session->set_flashdata('emessage', 'No data found, Please insert some data');
                redirect($_SERVER['HTTP_REFERER']);
            }
        } // LOGIN CHECK ENDS HERE

        else {
            redirect("login/admin_login", "refresh");
        }
    }


    public function delete_team($idd)
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $data['user_name']=$this->load->get_var('user_name');

            // echo SITE_NAME;
            // echo $this->session->userdata('image');
            // echo $this->session->userdata('position');
            // exit;
            $id=base64_decode($idd);

            $dww=$this->session->userdata('admin_id');

            if ($id==$dww) {
                $this->session->set_flashdata('emessage', "Sorry You can't delete yourself");
                redirect($_SERVER['HTTP_REFERER']);
            }

            if ($this->load->get_var('position')=="Super Admin") {
                $zapak=$this->db->delete('tbl_team', array('id' => $id));
                if ($zapak!=0) {
                    $this->session->set_flashdata('smessage', 'Successfully deleted');
                    redirect("admin/system/view_team", "refresh");
                } else {
                    $this->session->set_flashdata('emessage', 'Error Occured');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata('emessage', "Sorry You Don't Have Permission To Delete Anything.");
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            redirect("login/admin_login", "refresh");
        }
    }

    public function updateteamStatus($idd, $t)
    {
        if (!empty($this->session->userdata('admin_data'))) {
            $data['user_name']=$this->load->get_var('user_name');

            // echo SITE_NAME;
            // echo $this->session->userdata('image');
            // echo $this->session->userdata('position');
            // exit;
            $id=base64_decode($idd);

            $dww=$this->session->userdata('admin_id');

            if ($id==$dww) {
                $this->session->set_flashdata('emessage', "Sorry You can't change status of yourself");
                redirect($_SERVER['HTTP_REFERER']);
            }


            if ($this->load->get_var('position')=="Super Admin") {
                if ($t=="active") {
                    $data_update = array(
                 'is_active'=>1

         );

                    $this->db->where('id', $id);
                    $zapak=$this->db->update('tbl_team', $data_update);

                    if ($zapak!=0) {
                        $this->session->set_flashdata('smessage', 'Status successfully Updated');
                        redirect("admin/system/view_team", "refresh");
                    } else {
                        $this->session->set_flashdata('emessage', 'Error Occured');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
                if ($t=="inactive") {
                    $data_update = array(
          'is_active'=>0

          );

                    $this->db->where('id', $id);
                    $zapak=$this->db->update('tbl_team', $data_update);

                    if ($zapak!=0) {
                        $this->session->set_flashdata('smessage', 'Status successfully Updated');

                        redirect("admin/system/view_team", "refresh");
                    } else {
                        $this->session->set_flashdata('emessage', 'Error Occured');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
            } else {
                $this->session->set_flashdata('emessage', 'Sorry you dont have Permission to change admin, Only Super admin can change status');
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            redirect("login/admin_login", "refresh");
        }
    }



    // 		function blog(){
    //
    //
    // 		if(!empty($this->session->userdata('admin_data'))){
    //
    //
    // 											$this->db->select('*');
    // 											$this->db->from('tbl_blog');
    // 											// $this->db->where('student_shift',$cvf);
    // 											$data['blog_data']= $this->db->get();
    //
    //
    //
    //
    // 		$this->load->view('admin/common/header_view',$data);
    // 			$this->load->view('admin/blog/index');
    // 			$this->load->view('admin/common/footer_view');
    //
    // 	}
    // 	else{
    //
    // 			$this->load->view('admin/login/index');
    // 	}
    //
    // 	}
    //
    //
    //
    // 	function blog_image($idd){
    //
    //
    // 		if(!empty($this->session->userdata('admin_data'))){
    //
    //
    //
    // 			$id=base64_decode($idd);
    //
    // 											$this->db->select('*');
    // 											$this->db->from('tbl_blog');
    // 											$this->db->where('blog_id',$id);
    // 											$dsa= $this->db->get();
    // 											$da=$dsa->row();
    //
    //
    // 											$data['image']=json_decode($da->image);
    //
    //
    //
    //
    // 		$this->load->view('admin/common/header_view',$data);
    // 			$this->load->view('admin/blog/image');
    // 			$this->load->view('admin/common/footer_view');
    //
    // 	}
    // 	else{
    //
    // 			$this->load->view('admin/login/index');
    // 	}
    //
    // 	}
    //
    // 	function blog_des($idd){
    //
    //
    // 		if(!empty($this->session->userdata('admin_data'))){
    //
    //
    //
    // 			$id=base64_decode($idd);
    //
    // 											$this->db->select('*');
    // 											$this->db->from('tbl_blog');
    // 											$this->db->where('blog_id',$id);
    // 											$dsa= $this->db->get();
    // 											$da=$dsa->row();
    //
    //
    // 											$data['des']=$da->long_des;
    //
    //
    //
    //
    // 		$this->load->view('admin/common/header_view',$data);
    // 			$this->load->view('admin/blog/description');
    // 			$this->load->view('admin/common/footer_view');
    //
    // 	}
    // 	else{
    //
    // 			$this->load->view('admin/login/index');
    // 	}
    //
    // 	}
    //
    //
    //
    // 	function add_blog(){
    //
    //
    // 		if(!empty($this->session->userdata('admin_data'))){
    //
    //
    //
    //
    // 		$this->load->view('admin/common/header_view');
    // 			$this->load->view('admin/blog/add_blog');
    // 			$this->load->view('admin/common/footer_view');
    //
    // 	}
    // 	else{
    //
    // 			$this->load->view('admin/login/index');
    // 	}
    //
    // 	}
    //
    //
    // 	public function add_blog_data()
    //
    // 		{
    //
    // 			if(!empty($this->session->userdata('admin_data'))){
    //
    //
    //
    //
    //
    // 				$this->load->helper(array('form', 'url'));
    //
    // 				$this->load->library('form_validation');
    //
    // 				$this->load->helper('security');
    //
    //
    //
    // 			if($this->input->post())
    //
    // 				{
    //
    //
    // 					$title=$this->input->post('title');
    // 				$short=$this->input->post('short');
    // 				$long=$this->input->post('long');
    // 					$keyword=$this->input->post('keyword');
    // 						$highlight=$this->input->post('highlight');
    //
    // 					$meta=$this->input->post('meta');
    //
    // 							$img=$this->input->post('image');
    //
    //
    // 				$immg=json_encode($img);
    //
    // 				// print_r($img);
    //
    // 				// exit;
    //
    //
    //
    // 						$ip = $this->input->ip_address();
    //
    // 						$cur_date=date("Y-m-d H:i:s");
    //
    // 						// $addedby=$this->session->userdata('user_id');
    //
    // 						$addedby=1;
    //
    //
    // 						// $str = 'ruby on rails'; // your entered tag
    // 						$myTag = trim($title); // remove extra spaces from beginning and end
    // 						$hyphenTag = str_replace( ' ', '-', $myTag ); // place '-' between words
    // 						// echo $hyphenTag; // print result
    //
    // 						// foreach($img as $i){
    //
    // 							$data_insert = array('title'=>$title,
    // 												'url'=>$hyphenTag,
    // 												'short_des'=>$short,
    // 												'long_des'=>$long,
    // 												'highlight'=>$highlight,
    // 												'keywords'=>$keyword,
    // 												'meta'=>$meta,
    // 												'image'=>$immg,
    // 												'ip' =>$ip,
    // 												'added_by' =>$addedby,
    // 												'is_active' =>1,
    // 												'date'=>$cur_date
    //
    // 												);
    //
    //
    //
    //
    //
    // 				$last_id=$this->base_model->insert_table("tbl_blog",$data_insert,1) ;
    //
    // 				// print_r($i);
    //
    //
    //
    // 						// }
    //
    // 						// exit;
    //
    //
    //
    // 				if($last_id!=0){
    //
    //
    //
    // 				redirect("admin/home/blog","refresh");
    //
    // 								}
    //
    // 								else
    //
    // 								{
    //
    // 									echo "Error";
    //
    // 									exit;
    //
    //
    //
    // 								}
    //
    //
    //
    // 									}
    //
    // 				else
    //
    // 				{
    //
    // 														echo "please enter some data";
    // 														exit;
    //
    //
    //
    // 				}
    //
    //
    //
    // 			}
    //
    //
    //
    //  else{
    //
    // 			$this->load->view('admin/login/index');
    //
    // 	}
    //
    // }
    //
    //
    // 	public function image_uploadmutiple($name="")
    //
    // {
    //
    //
    //
    //  $this->load->library('upload');
    //
    //  $type_path = 'assets/uploads/thumbnail';
    //
    //
    //
    // // $video_upload_folder = FCPATH .UPLOAD_NOTES_FILES ;
    //
    // $video_upload_folder = FCPATH . "assets/uploads/blog/";
    //
    // if (!file_exists($video_upload_folder))
    //
    // 	{
    //
    // 		mkdir($video_upload_folder, DIR_WRITE_MODE, true);
    //
    // 	}
    //
    //
    //
    //
    //
    // 			$new_file_name="blog".date("Ymdhmis");
    //
    //
    //
    // 			$this->upload_config = array(
    //
    // 					'upload_path'   => $video_upload_folder,
    //
    // 					'file_name' => $new_file_name,
    //
    // 					'allowed_types' => 'jpg|jpeg|png|gif|bmp|PDF|XLS|XLSX|pdf|doc|docx|xlsx|xls|csv',
    //
    // 					'max_size'      => 1000000
    //
    // 			);
    //
    // 			$this->upload->initialize($this->upload_config);
    //
    // 			if (!$this->upload->do_upload('file'))
    //
    // 			{
    //
    // 				//print_r($_POST);
    //
    // 				//print_r($_FILES);
    //
    // 				$upload_error = $this->upload->display_errors();
    //
    // 				//print_r($upload_error);
    //
    // 				echo "error";
    //
    // 			}
    //
    // 			else
    //
    // 			{
    //
    // 				$file_info = $this->upload->data();
    //
    // 				$videoNAmePath = "assets/uploads/blog/".$new_file_name.$file_info['file_ext'];
    //
    // 			//	$videoNAmePath = UPLOAD_NOTIFICATION_FILES."/".$new_file_name.$file_info['file_ext'];
    //
    // 			//	$videoConPath = UPLOAD_NOTIFICATION_FILES."/".$new_file_name."_new".$file_info['file_ext'];
    //
    // 				$file_info['new_name']=$videoNAmePath;
    //
    // 				echo json_encode($file_info);
    //
    // 			}
    //
    //
    //
    //
    //
    // }
}
