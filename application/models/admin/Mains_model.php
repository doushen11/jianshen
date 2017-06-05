<?php
	//后台的全局控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Mains_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
			$this->load->library('encrypt');
		}
		
		public function subs($username,$passwd)
		{
			//登录程序处理	
			$sql="select `id`,`passwd`,`keys`,`login_ip`,`login_time`,`counts` from `dg_admin` where `username`='$username' limit 1";
			$query=$this->db->query($sql);
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$db_passwd=$result["passwd"];
				$sub_passwd=sha1(sha1($passwd).$result["keys"]);
				if($db_passwd==$sub_passwd)
				{
					$_array=array(
						"login_time"=>time(),
						"login_ip"=>ip2long(get_ip()),
						"last_time"=>$result["login_time"],
						"last_ip"=>$result["login_ip"],
						"counts"=>$result["counts"]+1,
						"token"=>sha1(microtime()."cc").md5(date("Y-m-d").uniqid()),
					);
					if($this->db->update("admin",$_array,array("id"=>$result["id"])))
					{
						$_login_str=$this->encrypt->encode($result["id"]."|".$result["keys"]."|".$username."|".$_array["token"]);
						setcookie("rs_author",$_login_str,time()+3600*24*3,"/");
						return "success";	
					}
				}
			}
			return "faild";
		}
		
		private function login_check()
		{
			//登录数据验证
			if(isset($_COOKIE["rs_author"]))
			{
				
				if($str=$this->encrypt->decode($_COOKIE["rs_author"]))
				{
					if(substr_count($str,"|")==3)
					{
						$arr=explode("|",$str);	
						$sql="select * from `dg_admin` where `id`='".trim($arr[0])."' and `keys`='".trim($arr[1])."' and `username`='".trim($arr[2])."'  and `token`='".trim($arr[3])."' limit 1";
						$query=$this->db->query($sql);
						setcookie("rs_author",$_COOKIE["rs_author"],time()+3600*24*3,"/");
						return $query->row_array();
					}
				}
				setcookie("rs_author","",time()-3600*24*3,"/");
			}	
			return "faild"; 
		}
		
		public function L()
		{
			//直接获取验证返回值信息
			return $this->login_check();
		}

		
		public function A()
		{
			//Ajax登录验证
			$rs=$this->login_check();
			if(is_array($rs) && isset($rs["id"]) && intval($rs["id"])>0)
			{
				return $rs;
			}
			else
			{
				echo "20000|登录状态已失效";exit();	
			}
		}
		
		public function N()
		{
			//常规登录验证
			$rs=$this->login_check();
			if(is_array($rs) && isset($rs["id"]) && intval($rs["id"])>0)
			{
				return $rs;
			}
			else
			{
				$this->logouts();	
			}
		}
		
		public function R()
		{
			//frame登录验证
			$rs=$this->login_check();
			if(is_array($rs) && isset($rs["id"]) && intval($rs["id"])>0)
			{
				return $rs;
			}
			else
			{
				iframes("20000","登录状态已经失效!");
			}	
		}
		
		public function logouts()
		{
			setcookie("rs_author","",time()-3600*24*3,"/");	
			header("location:".http_url()."admin/login/indexs");
			die();
		}
		
	}