<?php
//$this->load->database();
//$queryPayConfig=$this->db->query("SELECT * FROM tbl_payment");
//$rowPayConfig = $queryPayConfig->row();
/*
 		$CI =& get_instance();
        $rowPayConfig = $CI->db->get_where('tbl_payment', array('id' => 1))->row();

				$config['stripe_key_test_public']         = 'pk_test_Wn3fWnN4HWR76CjuNocV9TiM';
				$config['stripe_key_test_secret']         = 'sk_test_UYUb4LiPgjnnS1sJhWaIrsl9';
				$config['stripe_key_live_public']         = 'pk_live_Aid2LQjhTbkiY4x3hT24OhhA';
				$config['stripe_key_live_secret']         = 'sk_live_yU3M0lCdgRAVPeeVi5g8SyZW';
				
				$config['stripe_test_mode']               = TRUE;
				$config['stripe_verify_ssl']              = FALSE;
				if($rowPayConfig->stripe_mode=='Live'){
				    $config['stripe_key_live_public']         = $rowPayConfig->stripe_key_public;
					$config['stripe_key_live_secret']         = $rowPayConfig->stripe_key_secret;
					$config['stripe_test_mode']               = FALSE;
					$config['stripe_verify_ssl']              = TRUE;
				
				}else{
				    $config['stripe_key_test_public']         = $rowPayConfig->stripe_key_public;
					$config['stripe_key_test_secret']         = $rowPayConfig->stripe_key_secret;
					$config['stripe_test_mode']               = TRUE;
					$config['stripe_verify_ssl']              = FALSE;
				
				}
				*/
				$CI =& get_instance();
				$CI->load->library('session');
			if($CI->session->userdata('confs'))
			{
				$user_data=$CI->session->userdata('confs');
				if($user_data['mode']=='Live')
				{
						
					$config['stripe_key_live_public']         = $user_data['key_public'];
					$config['stripe_key_live_secret']         = $user_data['key_secret'];
					$config['stripe_test_mode']               = FALSE;
					$config['stripe_verify_ssl']              = TRUE;
					
				}else
				{
				    $config['stripe_key_test_public']         = $user_data['key_public'];
					$config['stripe_key_test_secret']         = $user_data['key_secret'];
					$config['stripe_test_mode']               = TRUE;
					$config['stripe_verify_ssl']              = FALSE;
				
				}
			}else{
				
				$config['stripe_key_test_public']         = "pk_test_4BkOdg0kk2V5LpVYmWdL5pRf";
					$config['stripe_key_test_secret']         = "sk_test_1PSGuKr7a5MpPCPOBcmolUuF";
					$config['stripe_test_mode']               = TRUE;
					$config['stripe_verify_ssl']              = FALSE;
			}
				
			
?>