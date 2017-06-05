<?php
	
	//教练端--消息控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/master/Alls.php";
	
	class Message extends Alls
	{
		
		public function __construct()
		{
			parent::__construct();
			
		}
		
		public function indexs()
		{
			//获取消息数量
			if(is_fulls("token")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				$_array=array();
				$query1=$this->db->query("select count(id) as `alls` from `dg_message_t` where `uid`='".$rs["id"]."'");
				$result1=$query1->row_array();
				$result1["alls"]==""?$_array["all_count"]=0:$_array["all_count"]=$result1["alls"];
				$query2=$this->db->query("select count(id) as `alls` from `dg_message_t` where `uid`='".$rs["id"]."' and `read`='1'");
				$result2=$query2->row_array();
				$result2["alls"]==""?$_array["all_noread_count"]=0:$_array["all_noread_count"]=$result2["alls"];
				$query3=$this->db->query("select count(id) as `alls` from `dg_message_t` where `uid`='".$rs["id"]."' and `read`='2'");
				$result3=$query3->row_array();
				$result3["alls"]==""?$_array["all_read_count"]=0:$_array["all_read_count"]=$result3["alls"];
				json_array2(10000,"成功",$_array);
			}else{
				error_show();
			}	
		}
		
		public function pages()
		{
			//获取消息翻页信息
			if(is_fulls("token"))
			{
				$token=I("token");
				$rs=$this->check_token($token);
				$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
				$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;
				$read= isset($_REQUEST["read"]) && is_numeric($_REQUEST["read"])?intval($_REQUEST["read"]):0;
				if($read==0)
				{
					$sql="select `id`,`title`,`time`,`contents`,`read` from `dg_message_t` where `uid`='".$rs["id"]."' order by `id` desc";
				}
				else
				{
					$sql="select `id`,`title`,`time`,`contents`,`read` from `dg_message_t` where `uid`='".$rs["id"]."' and `read`='$read' order by `id` desc";	
				}
				
				$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
				$query=$this->db->query($sql);	
				json_array2(10000,"成功",$query->result_array());	
			}
			else
			{
				error_show();	
			}	
		}	
		
		//消息详情h5
		public function item()
		{
			if(is_fulls("token") && is_fulls("id",1))
			{
				$token=I("token");
				$id=I("id");
				$rs=$this->check_token($token);
				$query=$this->db->query("select `id`,`title`,`time`,`contents`,`read` from `dg_message_t` where `uid`='".$rs["id"]."' and `id`='$id'");
			
				if($query->num_rows()>0)
				{
					$data["result"]=$query->row_array();
					if($data["result"]["read"]==1)
					{
						//更新阅读状态
						$this->db->query("update `dg_message_t` set `read`='2' where `id`='$id'");
					}
					$this->load->view("html5/message_u_item.php",$data);	
				}
				else
				{
					error_show();	
				}
			}
			else
			{
				error_show();	
			}	
		}			
		
		public function dels()
		{
			//删除消息
			if(is_fulls("token") && is_fulls("id"))
			{
				$token=I("token");
				$id=I("id");
				$rs=$this->check_token($token);
				$this->db->query("delete from `dg_message_t` where `id` in (".$id.") and `uid`='".$rs["id"]."'");
				json_array2(10000,"删除成功","");
			}
			else
			{
				error_show();	
			}
		}
		
	}