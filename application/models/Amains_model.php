<?php
	
	//对位操作model类
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Amains_model extends CI_Model
	{

		protected $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		//读取登录返回信息
		protected function read_login($id)
		{
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				unset($result["passwd"]);
				unset($result["last_ip"]);
				unset($result["last_time"]);
				unset($result["login_ip"]);
				unset($result["login_time"]);
				unset($result["focus"]);
				unset($result["focus_text"]);
				unset($result["money_desc"]);
				unset($result["score_count"]);
				unset($result["score_all"]);
				unset($result["user_agent"]);
				unset($result["desc"]);
				unset($result["contents"]);
				return $result;
			}	
			else
			{
				error_token_show();	
			}
		}
		
	}