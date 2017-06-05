<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Index_model extends CI_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//我的资料
		public function infos($rs)
		{
			$arr=explode("-",$rs["birthday"]);
			$rs["age"]=date("Y")-$arr[0];
			unset($rs["birthday"]);
			json_array("10000","成功",$rs);	
		}
		
		//我的钱包-主页
		public function mys($rs)
		{
			$array["balance"]=$rs["balance"];
			$data=strtotime(date("Y-m-d H:i:s"))-3600*24;
			$data=date("Y-m-d",$data);
			$s=strtotime($data." 00:00:00");
			$e=strtotime($data." 23:59:59");
			$query=$this->db->query("select sum(`money`) as `money` from `dg_teacher_money` where `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' and `models`='1' and `time`>='$s' and `time`<='$e'");
			$result=$query->row_array();
			$result["money"]==""?$array["balance_yesterday"]=0:$array["balance_yesterday"]=$result["money"];
			json_array("10000","成功",$array);
		}
		
		//账单明细
		public function lists($rs)
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;
			
			$s="";
			$e="";
			if(isset($_REQUEST["month"]) && trim($_REQUEST["month"])!="" && substr_count($_REQUEST["month"],"-")==1)
			{
				$BeginDate=strtotime(date('Y-m-01',strtotime($_REQUEST["month"]."-01"))." 00:00:00");
				$BeginDate1=date('Y-m-01',strtotime($_REQUEST["month"]."-01"));
				$EndDate=strtotime(date('Y-m-d',strtotime("$BeginDate1 +1 month -1 day"))." 23:59:59");
				$sql="select `money`,`id`,`models`,`act`,`time` from `dg_teacher_money` where `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' and `time`>='$BeginDate' and `time`<='$EndDate' order by `id` desc";	
			}	
			else
			{
				$sql="select `money`,`id`,`models`,`act`,`time` from `dg_teacher_money` where `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' order by `id` desc";	
			}
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);
			json_array("10000","成功",$query->result_array());
		}
		
		//账单明细详情
		public function listss($rs,$id)
		{
			$sql="select * from `dg_teacher_money` where `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' and `id`='$id' limit 1";	
			$query=$this->db->query($sql);
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$result["text"]=json_decode($result["text"],true);
				unset($result["tid_index"]);
				unset($result["tid"]);
				json_array("10000","成功",$result);
			}	
			else
			{
				json_array2(30000,"抱歉：没有找到对应的账单明细信息","");	
			}
		}
		
		//意见反馈处理
		public function notes($rs,$contents)
		{
			$_array=array(
				"uid"=>$rs["id"],
				"contents"=>$contents,
				"ip"=>ip2long(get_ip()),
				"act"=>2,
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
		
		//更新头像
		public function avatars($rs)
		{
			$file=img_upload($_FILES["file"],$result);
			if($result=="ok")
			{
				$_array=array(
					"avatar"=>$file,
				);	
				if($this->db->update("teacher",$_array,array("id"=>$rs["id"])))
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
		
	}