<?php

	//教练端配置控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Alls extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();	
		}
		
		//检测token信息
		public function check_token($token,$msg=null){

			$msg==""?$msg="*":$msg=$msg;
			$query=$this->db->query("select ".$msg." from `dg_teacher` where `token_app`='".trim($token)."' and `state`='1' limit 1");
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				error_token_show();		
			}

		}
		
	}
