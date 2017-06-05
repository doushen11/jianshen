<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Home_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//读取我的资料信息
		public function index($rs)
		{
			require FCPATH."config/img.inc.php";
			if($rs["avatar"]=="")
			{
				$rs["avatar"]=$img_inc["avatar"];	
			}
			$rs["age"]="";
			if($rs["brithday"]!="")
			{
				$arr=explode("-",$rs["brithday"]);	
				$rs["age"]=date("Y")-$arr[0];
			}
			json_array2("10000","成功",$rs);	
		}
		
		//更新资料
		public function subs($rs,$nickname,$gender,$brithday,$height,$weight,$professional)
		{
			$_array=array(
				"nickname"=>$nickname,
				"gender"=>$gender,
				"brithday"=>$brithday,
				"height"=>$height,
				"weight"=>$weight,
				"professional"=>$professional,
			);
			if($this->db->update("user",$_array,array("id"=>$rs["id"])))
			{
				json_array2("10000","成功","");	
			}
			else
			{
				error_show();	
			}
		}
		
		//更新头像
		public function avatars($rs)
		{
			$file=img_upload($_FILES["file"],$result);
			if($result=="ok")
			{
				$_array=array(
					"avatar"=>$file,
				);	
				if($this->db->update("user",$_array,array("id"=>$rs["id"])))
				{
					json_array2("10000","成功",$file);	
				}
				else
				{
					error_show();	
				}				
			}	
			else
			{
				json_array2("30000",$file,"");	
			}
		}
		
		//关于我们
		public function abouts($rs)
		{
			$query=$this->db->query("select * from `dg_abouts` where `id`='1' limit 1");
			if($query->num_rows()>0)
			{
				json_array2("10000","成功",$query->row_array());
			}	
			else
			{
				error_show();
			}				
		}
		
		//意见反馈
		public function notes($rs,$content)
		{
			$_array=array(
				"uid"=>$rs["id"],
				"contents"=>$content,
				"ip"=>ip2long(get_ip()),
				"time"=>time(),
			);
			if($this->db->insert("note",$_array))
			{
				json_array2("10000","成功","");	
			}	
			else
			{
				error_show();	
			}
		}
		
		//退出登录
		public function logout($rs)
		{
			if($this->db->query("update `dg_user` set `token_app`='' where `id`='".$rs["id"]."'"))
			{
				json_array2("10000","成功","");	
			}
			else
			{
				error_show();			
			}	
		}
	}