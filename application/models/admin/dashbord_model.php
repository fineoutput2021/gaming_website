<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class dashbord_model extends CI_Model
{
	// function for geting no of order recevied	public function rece_order()	{		return $this->db->count_all('tbl_order');	}	// fucntion for no of oreder complet	public function comp_or()	{		$this->db->where('is_complet', 3);		$this->db->from('tbl_order');		$q=$this->db->count_all_results();		return $q;	}		public function testing()	{	//	$this->db->query("create table test(id int, ids varchar(20))");		$this->dbforge->create_table('table_name');		
	}
	
	// function 
	
}