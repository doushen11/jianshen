<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Worker_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		//添加工作人员成功
		public function index_inserts()
		{
			$mobile=$this->input->post("mobile");
			$query=$this->db->query("select `id` from `dg_worker` where `mobile`='$mobile' limit 1");	
			if($query->num_rows()>0)
			{
				ajaxs(30000,"当前手机号已经被注册过");die();
			}
			else
			{
				
				$_array=array(
					"mobile"=>$mobile,
					"passwd"=>"",
					"desc"=>$this->input->post("desc"),
					"salt"=>mt_rand(100000,999999),
					"doors"=>0,
					"state"=>1,
					"reg_time"=>time(),
					"login_time"=>time(),
					"doors_keys"=>"",
					"login_ip"=>get_ip(),
				);
				$_array["passwd"]=sha1(sha1($this->input->post("passwd")).$_array["salt"]);
				if($this->db->insert("worker",$_array))
				{
					ajaxs(10000,"添加成功");	
				}
				else
				{
					ajaxs(30000,"网络连接失败");	
				}
			}
		}
		
		//设置sos登录状态
		public function changes($id)
		{
			$query=$this->db->query("select `state` from `dg_worker` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["state"]==1)
				{
					$st=2;	
				}
				else
				{
					$st=1;		
				}
				$this->db->query("update `dg_worker` set `state`='$st' where `id`='$id'");
				ajaxs(10000,$st);die();
			}
			else
			{
				ajaxs(30000,"没有找到对应用户信息");die();	
			}	
		}	
		
		//删除sos
		public function index_dels($id)
		{
			$this->db->query("delete from `dg_worker` where `id` in (".$id.")");
			$this->db->query("delete from `dg_worker_sos` where `uid` in (".$id.")");
			$this->db->query("delete from `dg_doors` where `uid` in (".$id.") and `act`='3'");
			ajaxs("10000","删除成功");	
		}	
		
		//修改sos工作人员
		public function index_updates($id)
		{
			$mobile=$this->input->post("mobile");
			$passwd=$this->input->post("passwd");
			$query=$this->db->query("select `id`,`salt` from `dg_worker` where `mobile`='$mobile'  and `id`!='$id' limit 1");	
			if($query->num_rows()>0)
			{
				ajaxs(30000,"当前手机号已经被注册过");die();
			}
			else
			{
				$query=$this->db->query("select `id`,`salt` from `dg_worker` where `id`='$id' limit 1");
				if($query->num_rows()<=0)
				{
					ajaxs(30000,"没有找到要修改的工作人员信息");die();
				}
				$result=$query->row_array();
				$_array=array(
					"mobile"=>$mobile,
					//"passwd"=>sha1($this->input->post("passwd")),
					"desc"=>$this->input->post("desc"),
					
				);
				if($passwd!="")
				{
					$_array["passwd"]=sha1(sha1($passwd).$result["salt"]);	
				}
				if($this->db->update("worker",$_array,array("id"=>$id)))
				{
					ajaxs(10000,"修改成功");	
				}
				else
				{
					ajaxs(30000,"网络连接失败");	
				}
			}
		}
		
	}