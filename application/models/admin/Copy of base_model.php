<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Author : Rishi Shrivastava
 * Description : This model is used for login Process
 */

class base_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	//Global Update Function
	public function update_table($table_name,$data_array,$where_array,$type_of="and")		{		if($type_of == "and")			{			foreach($where_array as $key=>$val)				{				$this->db->where($key,$val);				}			}		else if($type_of == "or")			{			$i=0;			foreach($where_array as $key=>$val)				{				if($i >= 1)					{					$this->db->or_where($key,$val);					}					else					{					$this->db->where($key,$val);					}					$i++;				}			}		if($this->db->update($table_name,$data_array))			{			return true;			}		else			{			return false;			}		}
		
	//Global Insert Function
	public function insert_table($table_name,$data_array,$return_type)
		{
		if($return_type == 1)
			{
			if($this->db->insert($table_name,$data_array))
				{
				return $this->db->insert_id();
				}
			else	
				{
				return 0;
				}
			}
		else
			{
			if($this->db->insert($table_name,$data_array))
				{
				return true;
				}
			else	
				{
				return false;
				}
			}
		}
		
	//Global Delete Function
	public function delete_table($table_name,$where_array,$type_of="and")
	{
		if($type_of == "and")
			{
			foreach($where_array as $key=>$val)
				{
				$this->db->where($key,$val);
				}
			}
		else if($type_of == "or")
			{
			$i=0;
			foreach($where_array as $key=>$val)
				{
				
				
				if($i >= 1)
					{
					$this->db->or_where($key,$val);
					}
					else
					{
					$this->db->where($key,$val);
					}
					
					$i++;
				}
			}
		
		if($this->db->delete($table_name))
			{
			return true;
			}
		else
			{
			return false;
			}
	}
	
	//Global Select Function
	public function select_table($table_name,$fetch_col,$where_array=array(),$orderby_array=array(),$groupby_array=array(),$type_of="and",$num=0)
	{
	$fetch_string = implode(",",$fetch_col);
	$this->db->select($fetch_string);
			if($type_of == "and")
			{
			foreach($where_array as $key=>$val)
				{
				$this->db->where($key,$val);
				}
			}
		else if($type_of == "or")
			{
			
			}
		if(!empty($orderby_array))
			{
			foreach($orderby_array as $okey=>$oval)
				{
				$this->db->order_by($okey,$oval);
				}
			}
		if(!empty($groupby_array))
			{
			foreach($groupby_array as $gkey=>$gval)
				{
				$this->db->group_by($gkey,$gval);
				}
			}	
			
		$this->db->from($table_name);
		$query=$this->db->get();
		if($num == 1)
			{
			return $query->num_rows();
			}
		else
			{
			return $query->result();
			}
	}
	
	/*
	
	$tabl1 --->table1
	$als1 --->Alias of first table1
	$table_array=array([0]=>array('name' => 'tbl2',
								   'als' => 't2',
								   'on'=>'t2.abc=t1.cdf'),
						[1]=>array('name' => 'tbl2',
								   'als' => 't2',
								   'on'=>'t2.abc=t1.cdf')		   
								   );
								   
	$fetch_col ---> as you send before but here you make the array with alias
    $where_array--->as you send before but here you make the array with alias	
	$orderby_array-->as you send before but here you make the array with alias	
	$groupby_array-->as you send before but here you make the array with alias	
	*/

	public function select_with_join($tabl1,$als1,$table_array,$fetch_col,$where_array,$orderby_array=array(),$groupby_array=array(),$type_of="and")
	{

	$fetch_string = implode(",",$fetch_col);
	$this->db->select($fetch_string);
	$this->db->from($tabl1." ".$als1);
	foreach($table_array as $tbl)
		{
		$this->db->join($tbl['name']." ".$tbl['als'],$tbl['on']);
		}
	if($type_of == "and")
			{
			foreach($where_array as $key=>$val)
				{
				$this->db->where($key,$val);
				}
			}
		else if($type_of == "or")
			{
			
			}
		
		if(!empty($orderby_array))
			{
			foreach($orderby_array as $okey=>$oval)
				{
				$this->db->order_by($okey,$oval);
				}
			}

			if(!empty($groupby_array))
			{
			foreach($groupby_array as $gkey=>$gval)
				{
				$this->db->group_by($gkey,$gval);
				}
			}			
	
		$query=$this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	
	}
	
	public function direct_query($qry)
	{
	$query=$this->db->query($qry);
	//echo $this->db->last_query();
	return $query->result();
	}
	
		

}