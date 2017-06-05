<?php
	
	//当前进出门接口
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Indexs extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
		}
		
		//用户当前享受折扣接口
		public function preferential()
		{
			if(is_fulls("token"))
			{
				
				$json_array=array();
				
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				$money=0;
				$query=$this->db->query("select `money` from `dg_pay_order` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `money_remaining`>0 order by `id` asc limit 1");
				if($query->num_rows()>0)
				{
					$result=$query->row_array();
					$money=$result["money"];
				}
				
				$querys=$this->db->query("select * from `dg_time_model` where `act`='1'");
				
				foreach($querys->result_array() as $arrays)
				{
					if($arrays["model"]==1)
					{
						$json_array["peak_time_min"]=$arrays["min"];
						$json_array["peak_time_max"]=$arrays["max"];
					}
					if($arrays["model"]==2)
					{
						$json_array["slack_time_min"]=$arrays["min"];
						$json_array["slack_time_max"]=$arrays["max"];							
					}					
				}
				
				$querys=$this->db->query("select * from `dg_pay_model` order by `id` asc");
				foreach($querys->result_array() as $arrays)
				{
					if($arrays["min"]<$arrays["max"])
					{
						if($arrays["min"]<=$money && $arrays["max"]>$money)
						{
							$json_array["peak_money"]=$arrays["money_peak"];
							$json_array["slack_money"]=$arrays["money_slack"];	
						}	
					}
					else
					{
						if($arrays["min"]<=$money && $arrays["max"]==0)
						{
							$json_array["peak_money"]=$arrays["money_peak"];
							$json_array["slack_money"]=$arrays["money_slack"];	
						}							
					}
				}
				
				json_array2(10000,"成功",$json_array);
			}
			else
			{
				error_show();	
			}
		}
		
		
		//用户今日出门进门接口
		public function todays()
		{
			if(is_fulls("token"))
			{
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				$s=strtotime(date("Y-m-d")." 00:00:00");
				$e=strtotime(date("Y-m-d")." 23:59:59");
				$query=$this->db->query("select `start_time`,`end_time` from `dg_doors` where `uid`='".$rs["id"]."' and (`start_time`>='$s' and `start_time`<='$e') || (`end_time`<='$e' and `end_time`>='$s') order by `id` desc");
				json_array2(10000,"成功",$query->result_array());
			}
			else
			{
				error_show();	
			}
		}
		
		//用户消费课程记录
		public function consume()
		{
			if(is_fulls("token"))
			{
				$arr=array();$i=0;
				
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				
				$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;
			
				$sql="select `act`,`money`,`pid`,`class_id` from `dg_orders` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `state` in (1,2,3) order by `id` desc";
				
				$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
				
				$query=$this->db->query($sql);
				
				foreach($query->result_array() as $array)
				{
					$arr[$i]=$array;
					$arr[$i]["class_name"]="";
					$arr[$i]["realname"]="";
					$arr[$i]["date"]="";
					$arr[$i]["start_time"]="";
					$arr[$i]["end_time"]="";
					if($arr[$i]["act"]==1)
					{
						//私课模式查询
						
						$querys1=$this->db->query("select `t`.`realname`,`p`.`start_time`,`p`.`end_time`,`p`.`date` from `dg_tearch_plan_list` as `p` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `p`.`id`='".$arr[$i]["class_id"]."'");		
						if($querys1->num_rows()>0)
						{
							$arr[$i]=array_merge($arr[$i],$querys1->row_array());
						}
					}	
					elseif($arr[$i]["act"]==2)
					{
						//操课查询模式
						$querys1=$this->db->query("select `t`.`realname`,`p`.`start_time`,`p`.`end_time`,`p`.`date`,`p`.`class_name` from `dg_tearch_plans` as `p` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `p`.`id`='".$arr[$i]["pid"]."'");
						if($querys1->num_rows()>0)
						{
							$arr[$i]=array_merge($arr[$i],$querys1->row_array());
						}
					}
					unset($arr[$i]["pid"]);
					unset($arr[$i]["class_id"]);
					$i++;
				}
				
				
				json_array2(10000,"成功",$arr);
			}
			else
			{
				error_show();	
			}				
		}
	
	}
?>