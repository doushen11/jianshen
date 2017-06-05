<?php

	//客户端配置控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Mains extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();	
		}

		//检测token信息
		public function check_token($token,$msg=null){
			if(substr_count($token,"_")!=2)
			{
				error_token_show();
			}
			else
			{
				$arrs=explode("_",$token);
				$msg==""?$msg="*":$msg=$msg;
				$query=$this->db->query("select * from `dg_user` where `token_app`='".trim($arrs[0])."' and `passwd`='".trim($arrs[1])."' and `salts`='".trim($arrs[2])."' limit 1");
				if($query->num_rows()>0){
					$result=$query->row_array();
					if($result["state"]==2)
					{
						json_array2("30000","您已经被管理员拉黑，无法继续进行操作","");
					}
					return $result;
				}else{
					error_token_show();		
				}
			}
		}
		
		//常规token检测
		public function get_users($token,$msg=null)
		{
			if(substr_count($token,"_")!=2)
			{
				return false;	
			}
			$arrs=explode("_",$token);
			$msg==""?$msg="*":$msg=$msg;
			$query=$this->db->query("select ".$msg." from `dg_user` where `token_app`='".trim($arrs[0])."' and `passwd`='".trim($arrs[1])."' and `salts`='".trim($arrs[2])."' limit 1");
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;	
			}				
		}
		
	}
