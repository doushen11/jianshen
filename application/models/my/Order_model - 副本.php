<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Order_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//点评操课
		public function comments($rs,$id,$star_a,$star_b)
		{
			//开启事务处理
			$this->db->trans_strict(false);
			$this->db->trans_begin();
			
			$query=$this->db->query("select `l`.`date`,`l`.`start_time`,`l`.`end_time`,`l`.`node`,`o`.`state`,`o`.`class_id`,`o`.`comments`,`o`.`tid`,`l`.`class_name`,`l`.`tid_name`,`l`.`room_name`,`l`.`min`,`l`.`max`,`l`.`loads` from `dg_orders` as `o` left join `dg_tearch_plans` as `l` on `l`.`id`=`o`.`pid` where `o`.`uid_index`='".right_index($rs["id"])."' and `o`.`uid`='".$rs["id"]."' and `o`.`id`='$id' and `o`.`act`='2'");	
			
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["state"]==3)
				{
					if($result["comments"]==0)
					{
						//更新订单星级
						$this->db->query("update `dg_orders` set `star`='$star_a',`star_class`='$star_b',`comments`='1' where `id`='$id'");
						
						//查询教练星级信息，重组
						$query_a=$this->db->query("select `score_all`,`score_count` from `dg_teacher` where `id`='".$result["tid"]."'");
						
						if($query_a->num_rows()>0)
						{
							$results=$query_a->row_array();	
							$score_all=$results["score_all"]+$star_a;
							$score_count=$results["score_count"]+1;
							$stars=sprintf("%.1f ",$score_all/$score_count);
							
							//更新教练星级和人数
							$this->db->query("update `dg_teacher` set `score_all`=`score_all`+'".$star_a."',`score_count`=`score_count`+'1',`score`='$stars' where `id`='".$result["tid"]."'");
							
						}
						else
						{
							$this->db->trans_rollback();
							json_array2("30000","当前教练信息已经失效，无法继续评价，抱歉","");									
						}
						
						//查询课程星级，重组
						$query_b=$this->db->query("select `score_all`,`score_count` from `dg_class` where `id`='".$result["class_id"]."'");
						
						if($query_b->num_rows()>0)
						{
							$results=$query_b->row_array();
							$score_all=$results["score_all"]+$star_b;
							$score_count=$results["score_count"]+1;
							$stars=sprintf("%.1f ",$score_all/$score_count);
							
							
							//更新课程星级和人数
							$this->db->query("update `dg_class` set `score_all`=`score_all`+'".$star_b."',`score_count`=`score_count`+'1',`score`='$stars' where `id`='".$result["class_id"]."'");
							
						}
						else
						{
							$this->db->trans_rollback();
							json_array2("30000","当前班级信息已经失效，无法继续评价，抱歉","");									
						}
						
						//开始对余额和对应的充值记录做处理
						
						require FCPATH."config/money_inc.php";
						
						$this->db->query("update `dg_user` set `balance`=`balance`+'".$money_inc["comment"]."' where `id`='".$rs["id"]."'");	//更新账户余额
						
						$oid=date("YmdHis").substr(microtime(),2,8);
						$_arrays=array(
							"uid"=>$rs["id"],
							"uid_index"=>right_index($rs["id"]),
							"money"=>$money_inc["comment"],
							"money_remaining"=>$money_inc["comment"],
							"order_id"=>$oid,
							"trade_index"=>$oid,
							"pay_act"=>3,
							"time"=>time(),
						);
						$this->db->insert("pay_order",$_arrays);
						
						//print_r($results);die();					
						if($this->db->trans_status()==true){
							$this->db->trans_commit();
							
							//开始执行推送内容
							
							if($rs["push_key"]!="")
							{
								
								require FCPATH."config/push.inc.php";
								
								$nickname="";
								if($rs["nickname"]!="")
								{
									$nickname=$rs["nickname"];	
								}
								else
								{
									$nickname=substr($rs["mobile"],0,3)."****".substr($rs["mobile"],7,4);	
								}	
								
								$msg=str_replace("{name}",$nickname,$push_inc["comment_message"]);
									
								$msg=str_replace("{money}",$money_inc["comment"],$msg);
								
								$msg=str_replace("{time}",time(),$msg);
								
								$_arrays=array("message"=>$msg,"type"=>3,"id"=>"0","push_id"=>$rs["push_key"],"title"=>$push_inc["comment_title"]);
									
								c_push($_arrays);
								
														
							}
							
							json_array2("10000","成功","");
						}else{
							$this->db->trans_rollback();
							//echo 100;die();
							error_show();
						}						
						
					}
					else
					{
						json_array2("30000","当前订单已经评价，无法继续操作","");			
					}
				}
				else
				{
					json_array2("30000","当前订单状态不允许评价","");	
				}
				
			}
			else
			{
				json_array2("30000","没有找到对应的订单信息","");	
			}
							
		}
		
		//点评私课
		public function comment($rs,$id,$star)
		{
			//开启事务处理
			$this->db->trans_strict(false);
			$this->db->trans_begin();
			
			$query=$this->db->query("select `l`.`date`,`l`.`start_time`,`l`.`end_time`,`l`.`node`,`o`.`state`,`o`.`money`,`o`.`class_id`,`o`.`tid`,`o`.`comments`,`o`.`star`,`o`.`star_class` from `dg_orders` as `o` left join `dg_tearch_plan_list` as `l` on `l`.`id`=`o`.`class_id` where `o`.`uid_index`='".right_index($rs["id"])."' and `o`.`uid`='".$rs["id"]."' and `o`.`id`='$id' and `o`.`act`='1'");
			
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["state"]==3)
				{
					if($result["comments"]==0)
					{
						//更新订单星级
						$this->db->query("update `dg_orders` set `star`='$star',`comments`='1' where `id`='$id'");
					
						//查询教练星级信息，重组
						$querys=$this->db->query("select `score_all`,`score_count` from `dg_teacher` where `id`='".$result["tid"]."'");
						if($querys->num_rows()>0)
						{
							$results=$querys->row_array();
							$score_all=$results["score_all"]+$star;
							$score_count=$results["score_count"]+1;
							$stars=sprintf("%.1f ",$score_all/$score_count);
							
							//更新教练星级和人数
							$this->db->query("update `dg_teacher` set `score_all`=`score_all`+'".$star."',`score_count`=`score_count`+'1',`score`='$stars' where `id`='".$result["tid"]."'");

							require FCPATH."config/money_inc.php";
							
							$this->db->query("update `dg_user` set `balance`=`balance`+'".$money_inc["comment"]."' where `id`='".$rs["id"]."'");	//更新账户余额
							
							$oid=date("YmdHis").substr(microtime(),2,8);
							$_arrays=array(
								"uid"=>$rs["id"],
								"uid_index"=>right_index($rs["id"]),
								"money"=>$money_inc["comment"],
								"money_remaining"=>$money_inc["comment"],
								"order_id"=>$oid,
								"trade_index"=>$oid,
								"pay_act"=>3,
								"time"=>time(),
							);
							$this->db->insert("pay_order",$_arrays);							
							
							if($this->db->trans_status()==true){
								$this->db->trans_commit();
								
								//开始执行推送内容
								
								if($rs["push_key"]!="")
								{
									
									require FCPATH."config/push.inc.php";
									
									$nickname="";
									if($rs["nickname"]!="")
									{
										$nickname=$rs["nickname"];	
									}
									else
									{
										$nickname=substr($rs["mobile"],0,3)."****".substr($rs["mobile"],7,4);	
									}	
									
									$msg=str_replace("{name}",$nickname,$push_inc["comment_message"]);
										
									$msg=str_replace("{money}",$money_inc["comment"],$msg);
									
									$msg=str_replace("{time}",time(),$msg);
									
									$_arrays=array("message"=>$msg,"type"=>3,"id"=>"0","push_id"=>$rs["push_key"],"title"=>$push_inc["comment_title"]);
										
									c_push($_arrays);
																
								}						
								
								json_array2("10000","成功","");
							}else{
								$this->db->trans_rollback();
								error_show();
							}
							
						}
						else
						{
							$this->db->trans_rollback();
							json_array2("30000","当前教练信息已经失效，无法继续评价，抱歉","");									
						}
					}	
					else
					{
						json_array2("30000","当前订单已经评价，无法继续操作","");	
					}
				}
				else
				{
					json_array2("30000","当前订单状态不允许评价","");	
				}
			}
			else
			{
				json_array2("30000","没有找到对应的订单信息","");
			}
			
							
		}
		
		//私课订单详情接口
		public function item($rs,$id)
		{
			$query=$this->db->query("select `l`.`date`,`l`.`start_time`,`l`.`end_time`,`l`.`node`,`o`.`state`,`o`.`money`,`o`.`class_id`,`o`.`tid`,`o`.`star`,`o`.`star_class`,`o`.`star`,`o`.`star_class` from `dg_orders` as `o` left join `dg_tearch_plan_list` as `l` on `l`.`id`=`o`.`class_id` where `o`.`uid_index`='".right_index($rs["id"])."' and `o`.`uid`='".$rs["id"]."' and `o`.`id`='$id' and `o`.`act`='1'");	
			
			if($query->num_rows()>0)
			{
				$result=$query->row_array();

				//开始查询教师对应信息
				$query_a=$this->db->query("select `avatar`,`birthday`,`level`,`score`,`desc`,`bg`,`money_desc`,`focus_text`,`realname` from `dg_teacher` where `id`='".$result["tid"]."'");
				if($query_a->num_rows()>0)
				{
					
					$result_a=$query_a->row_array();
					$result=array_merge($result_a,$result);
					
					$arr=explode("-",$result["birthday"]);
							
					$result["birthday"]=date("Y")-$arr[0];	
					
					
					$arr=json_decode($result["money_desc"],true);
					
					if(is_array($arr) && !empty($arr))
					{
						$result["money_peak"]=$arr["money_peak"];
						$result["money_slack"]=$arr["money_slack"];
					}
					else
					{
						json_array2("30000","没有找到对应的订单教练信息","");	
					}
					unset($result["money_desc"]);
					$result["focus_state"]=0;
					if($result["focus_text"]!="" && is_fulls("token"))
					{
						$arr=json_decode($result["focus_text"],true);
						if(in_array($rs["id"],$arr))
						{
							$result["focus_state"]=1;
						}
					}

					$query1=$this->db->query("select `min`,`max` from `dg_time_model` where `model`='1' and `act`='1'");
					$query2=$this->db->query("select `min`,`max` from `dg_time_model` where `model`='2' and `act`='1'");					
					
					$result["time_peak"]=$query1->result_array();
					
					$result["time_slack"]=$query2->result_array();
					
					//$arr=explode("-",$result["date"]);
					
					//计算对应日期可预约时间
					
					//$day_in=$arr[0]."-".$arr[1];
					$result["free_time"]="";
					$queyrp=$this->db->query("select `frees` from `dg_tearch_plan_list` where `date`='".$result["date"]."' and `tid_index`='".right_index($result["tid"])."' and `tid`='".$result["tid"]."' and `frees`!='' limit 1");
					if($queyrp->num_rows()>0)
					{
						$resultp=$queyrp->row_array();
						$result["free_time"]=$resultp["frees"];	
					}				
					
					
					unset($result["focus_text"]);
					//print_r($result);		
					
					json_array2("10000","成功",$result);			
				}
				else
				{
					json_array2("30000","没有找到对应的订单教练信息","");		
				}
			}
			else
			{
				json_array2("30000","没有找到对应的订单信息","");	
			}
		}
		
		//操课订单详情接口
		public function items($rs,$id)
		{
			$query=$this->db->query("select `l`.`date`,`l`.`start_time`,`l`.`end_time`,`l`.`node`,`o`.`money`,`o`.`class_id`,`o`.`tid`,`l`.`state` as `state1`,`l`.`class_name`,`l`.`tid_name`,`l`.`room_name`,`l`.`min`,`l`.`max`,`l`.`loads`,`o`.`star`,`o`.`star_class` from `dg_orders` as `o` left join `dg_tearch_plans` as `l` on `l`.`id`=`o`.`pid` where `o`.`uid_index`='".right_index($rs["id"])."' and `o`.`uid`='".$rs["id"]."' and `o`.`id`='$id' and `o`.`act`='2'");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				//开始查询教师对应信息
				$query_a=$this->db->query("select `avatar`,`birthday`,`level`,`score`,`desc`,`money_desc` from `dg_teacher` where `id`='".$result["tid"]."'");
				if($query_a->num_rows()>0)
				{
					$result_a=$query_a->row_array();
					$result=array_merge($result_a,$result);
					
					$arr=explode("-",$result["birthday"]);
							
					$result["birthday"]=date("Y")-$arr[0];		
					
					//组合教授课程
					$arrs=json_decode($result["money_desc"],true);
					
					$result["teache_class_name"]="";
					
					for($z=0;$z<count($arrs);$z++)
					{
						$qys=$this->db->query("select `name` from `dg_class` where `id`='".$arrs[$z]["class"]."'");
						if($qys->num_rows()>0)
						{
							$rse=$qys->row_array();
							$result["teache_class_name"].=" ".$rse["name"];
						}	
					}
					
					$result["teache_class_name"]=trim($result["teache_class_name"]);
						
					//开始查询课程信息
					$query_b=$this->db->query("select `bg_file`,`focus` from `dg_class` where `id`='".$result["class_id"]."'");
					if($query_b->num_rows()>0)
					{
						$result_b=$query_b->row_array();
						$result=array_merge($result_b,$result);	
						
						$result["focus_state"]=0;
						if($result["focus"]!="" && is_fulls("token"))
						{
							$arr=json_decode($result["focus"],true);
							if(in_array($rs["id"],$arr))
							{
								$result["focus_state"]=1;
							}
						}
					
						unset($result["focus"]);
						
						//print_r($result);		
						
						json_array2("10000","成功",$result);			
					}
					else
					{
						json_array2("30000","没有找到对应的订单课程信息","");	
					}
						
								
					//print_r($result);
				}
				else
				{
					json_array2("30000","没有找到对应的订单教练信息","");	
				}
			}
			else
			{
				json_array2("30000","没有找到对应的订单信息","");	
			}
		}
		
		//操课取消退款
		public function clear($rs,$id)
		{
			//开启事务处理
			$this->db->trans_strict(false);
			$this->db->trans_begin();	
				
				
			
					
			$query=$this->db->query("select `l`.`date`,`l`.`start_time`,`l`.`end_time`,`l`.`node`,`o`.`state`,`o`.`returns`,`o`.`money`,`o`.`class_id`,`o`.`tid`,`l`.`state` as `state1`,`l`.`id` from `dg_orders` as `o` left join `dg_tearch_plans` as `l` on `l`.`id`=`o`.`pid` where `o`.`uid_index`='".right_index($rs["id"])."' and `o`.`uid`='".$rs["id"]."' and `o`.`id`='$id' and `o`.`act`='2'");	
			
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["state1"]==1)
				{				

					/*$f=fopen(FCPATH."aaa.php","w");
					
					fwrite($f,"select `l`.`date`,`l`.`start_time`,`l`.`end_time`,`l`.`node`,`o`.`state`,`o`.`returns`,`o`.`money`,`o`.`class_id`,`o`.`tid`,`l`.`state` as `state1`,`l`.`id` from `dg_orders` as `o` left join `dg_tearch_plans` as `l` on `l`.`id`=`o`.`pid` where `o`.`uid_index`='".right_index($rs["id"])."' and `o`.`uid`='".$rs["id"]."' and `o`.`id`='$id' and `o`.`act`='2'");	*/					
					
					if($result["state"]==1)
					{					
						//开始减掉对应的课程参与人数
						$this->db->query("update `dg_tearch_plans` set `loads`=`loads`-1 where `id`='".$result["id"]."'");
						
						//开始更改当前状态为自己取消订单
						$this->db->query("update `dg_orders` set `state`='5' where `id`='$id'");
						
						
						if($result["money"]>0){
						
							//开始退款至原来的节点中处理
							$arr=json_decode($result["returns"],true);
							
							if(is_array($arr) && !empty($arr))
							{
								
								for($i=0;$i<count($arr);$i++)
								{
									$this->db->query("update `dg_pay_order` set `money_remaining`=`money_remaining`+'".$arr[$i]["money"]."' where `id`='".$arr[$i]["id"]."'");
								}
								
							}
							//else
							//{
								//$this->db->trans_rollback();
								//json_array2("30000","退款信息读取失败，请您稍后再试","");	
							//}
							
							//开始更新自己的账户余额
							$this->db->query("update `dg_user` set `balance`=`balance`+'".$result["money"]."' where `id`='".$rs["id"]."'");
						
						}
						
						if($this->db->trans_status()==true){
							//$this->db->trans_commit();
							$this->db->trans_commit();
							
							json_array2("10000","取消成功","");
						}else{
							$this->db->trans_rollback();
							error_show();
						}
						
						}
					else
					{
						$this->db->trans_rollback();
						json_array2("30000","当前订单状态不允许取消","");	
					}
					
					
				}
				elseif($result["state1"]==2)
				{
					$this->db->trans_rollback();
					json_array2("30000","当前课程已经达到开课人数，不允许退款","");
				}
				else
				{
					$this->db->trans_rollback();
					json_array2("30000","当前订单状态不允许取消","");	
				}
			}
			else
			{
				$this->db->trans_rollback();
				json_array2("30000","没有找到对应的订单信息","");	
			}
		}
		
		//取消私课订单信息
		public function clears($rs,$id)
		{
			//可以退款，处理退款信息
			$this->db->trans_strict(false);
			$this->db->trans_begin();
								
			$query=$this->db->query("select `l`.`date`,`l`.`start_time`,`l`.`end_time`,`l`.`node`,`o`.`state`,`o`.`returns`,`o`.`money`,`o`.`class_id`,`o`.`tid` from `dg_orders` as `o` left join `dg_tearch_plan_list` as `l` on `l`.`id`=`o`.`class_id` where `o`.`uid_index`='".right_index($rs["id"])."' and `o`.`uid`='".$rs["id"]."' and `o`.`id`='$id' and `o`.`act`='1'");	
			
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["state"]==2 || $result["state"]==1)
				{
					//echo date("Y-m-d H:i:s","1471003200");die();
					//可以取消
					require FCPATH."config/sys.inc.php";
					if($result["start_time"]-time()<$_sys_inc["class_close_time_b"])
					{
						$fs=round($_sys_inc["class_close_time_b"]/3600);
						$this->db->trans_rollback();
						json_array2("30000","开课前大约".$fs."小时内不允许取消订单","");	
					}
					else
					{
						
						$this->db->query("update `dg_orders` set `state`='5' where `id`='$id'");
						//更新订单状态为自己手动退款模式
						
						//退款到每个节点里面去
						//$arr=json_decode($result["returns"],true);
						
						/*if(is_array($arr) && !empty($arr))
						{
							for($i=0;$i<count($arr);$i++)
							{
								$this->db->query("update `dg_pay_order` set `money_remaining`=`money_remaining`+'".$arr[$i]["money"]."' where `id`='".$arr[$i]["id"]."'");
							}
						}
						else
						{
							$this->db->trans_rollback();
							json_array2("30000","退款信息读取失败，请您稍后再试","");	
						}*/
						//退款到每个节点结束
						$back_money=$result["money"];
						//退款到用户账户余额里面去
						$this->db->query("update `dg_user` set `balance`=`balance`+'".$result["money"]."' where `id`='".$rs["id"]."'");
						//退款到用户余额结束
						
						//开始还原课程的预约状态
						$this->db->query("update `dg_tearch_plan_list` set `state`='2' where `id`='".$result["class_id"]."'");
						//开始还原课程的预约状态
						
						//还原周期课程列表的预约信息
						
						if($this->db->trans_status()==true){
							//$this->db->trans_commit();
							$this->db->trans_commit();
							require FCPATH."config/push.inc.php";
							//开始给老师和学生推送推送取消的信息
							if(isset($rs["push_key"]) && $rs["push_key"]!="")
							{
								//开始推送给学生
								$msg=str_replace("{date}",$result["date"],$push_inc["class_clear_message"]);
								$msg=str_replace("{start}",date("H:i",$result["start_time"]),$msg);
								$msg=str_replace("{end}",date("H:i",$result["end_time"]),$msg);
								
								$msg=str_replace("{time}",date("Y-m-d H:i:s"),$msg);	
								
								$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>$rs["push_key"],"title"=>$push_inc["class_clear_title"]);
								
								c_push($_arrays);
							}
							//开始推给教师
							$t=$this->db->query("select `push_key` from `dg_teacher` where `id`='".$result["tid"]."'");
							if($t->num_rows()>0)
							{
								$r=$t->row_array();
								if($r["push_key"]!="")
								{
									//开始真正意义上的推给教师
									$msg=str_replace("{date}",$result["date"],$push_inc["class_clear_teacher_mssage"]);
									$msg=str_replace("{start}",date("H:i",$result["start_time"]),$msg);
									$msg=str_replace("{end}",date("H:i",$result["end_time"]),$msg);
									$msg=str_replace("{money}",$back_money,$msg);
									$msg=str_replace("{time}",date("Y-m-d H:i:s"),$msg);	
									
									$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>$r["push_key"],"title"=>$push_inc["class_clear_teacher_title"]);
									
									a_push($_arrays);										
								}	
							}
							
							json_array2("10000","取消成功","");
						}else{
							$this->db->trans_rollback();
							error_show();
						}
					}	
				}
				else
				{
					$this->db->trans_rollback();
					json_array2("30000","当前订单状态不允许取消","");		
				}
			}
			else
			{
				$this->db->trans_rollback();
				json_array2("30000","没有找到对应的订单信息","");	
			}
		}
		
		//显示我对应的订单信息
		public function index($rs)
		{
			$model=isset($_REQUEST["model"]) && intval($_REQUEST["model"])>=0?intval($_REQUEST["model"]):0;
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			
			if($model==0)
			{
				//全部订单
				$sql="select `id`,`act`,`money`,`pid`,`tid`,`class_id`,`time`,`state`,`comments` from `dg_orders`  where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' order by `class_time` desc";	
			}
			elseif($model==1)
			{
				//待成立订单
				$sql="select `id`,`act`,`money`,`pid`,`tid`,`class_id`,`time`,`state`,`comments` from `dg_orders` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `state`='1' order by `class_time` desc";	
			}
			elseif($model==2)
			{
				//未成立的订单
				$sql="select `id`,`act`,`money`,`pid`,`tid`,`class_id`,`time`,`state`,`comments` from `dg_orders` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and (`state`='4' or `state`='5') order by `class_time` desc";	
			}
			elseif($model==3)
			{
				//待上课的订单，已经成立的订单
				$sql="select `id`,`act`,`money`,`pid`,`tid`,`class_id`,`time`,`state`,`comments` from `dg_orders` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `state`='2' order by `class_time` desc";	
			}
			elseif($model==4)
			{
				//已完成等待评价的订单
				$sql="select `id`,`act`,`money`,`pid`,`tid`,`class_id`,`time`,`state`,`comments` from `dg_orders` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `state`='3' and `comments`='0' order by `class_time` desc";
			}
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);
			
			
			$query1=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='1' order by `min` asc");
			if($query1->num_rows()>0)
			{
				$time_peak=$query1->result_array();
			}
			else
			{
				$time_peak=array();
			}
			$query2=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='2' order by `min` asc");
			if($query2->num_rows()>0)
			{
				$time_slack=$query2->result_array();
			}
			else
			{
				$time_slack=array();
			}			
			
			$array=array();$i=0;
			foreach($query->result_array() as $arrays)
			{
				$array[$i]=$arrays;
				$array[$i]["a_list"]=array("tid"=>"");//设置私课数据存储变量
				$array[$i]["b_list"]=array("tid"=>"");//设置操课数据存储变量
				
				
				if($array[$i]["act"]==2)
				{
					//计算对应的操课信息
					//查询购买课程节点信息
					$query_a=$this->db->query("select `tid_name`,`class_id`,`class_name`,`room_name`,`node`,`tid`,`date`,`min`,`start_time`,`end_time`,`max`,`loads` from `dg_tearch_plans` where `id`='".$array[$i]["pid"]."' limit 1");
					
					if($query_a->num_rows()<=0)
					{
						$arrs=array("tid_name"=>"0","class_id"=>"0","class_name"=>"0","room_name"=>"0","node"=>"0","tid"=>"0","date"=>"0","min"=>"0","start_time"=>0,"end_time"=>0,"max"=>0,"loads"=>0);
						
						$array[$i]["b_list"]=$arrs;
							
					}
					else
					{
						$array[$i]["b_list"]=$query_a->row_array();
					}
					
					//找出具体的课程图片信息
					$query_b=$this->db->query("select `bg_file` from `dg_class` where `id`='".$array[$i]["class_id"]."'");
					$result_b=$query_b->row_array();
					
					$array[$i]["b_list"]["class_files"]=$result_b["bg_file"];
					
				}
				elseif($array[$i]["act"]==1)
				{
					//计算对应的私课信息
					$query_a=$this->db->query("select `tid`,`date`,`node`,`start_time`,`end_time` from `dg_tearch_plan_list` where `id`='".$array[$i]["class_id"]."' limit 1");	
					
					$array[$i]["a_list"]=$query_a->row_array();
					
					//查询教练信息
					$query_b=$this->db->query("select `avatar`,`realname`,`birthday`,`level`,`score`,`money_desc` from `dg_teacher` where `id`='".$array[$i]["a_list"]["tid"]."'");
					
					$result_b=$query_b->row_array();
					
					$array[$i]["a_list"]=array_merge($array[$i]["a_list"],$result_b);
					
					$ars=explode("-",$array[$i]["a_list"]["birthday"]);
					
					$array[$i]["a_list"]["birthday"]=date("Y")-$ars[0];
					
					//高低峰时间计算
					
					$ars=json_decode($result_b["money_desc"],true);
					
					$array[$i]["a_list"]["money_peak"]="读取失败";
					
					$array[$i]["a_list"]["money_slack"]="读取失败";
					
					if(is_array($ars) && !empty($ars) && isset($ars["money_peak"]) && isset($ars["money_slack"]))
					{
						$array[$i]["a_list"]["money_peak"]=$ars["money_peak"];
						$array[$i]["a_list"]["money_slack"]=$ars["money_slack"];
					}
					
					//计算今日可预约时间
					$array[$i]["a_list"]["free_time"]="";
					$queyrp=$this->db->query("select `frees` from `dg_tearch_plan_list` where `date`='".date("Y-m-d")."' and `tid_index`='".right_index($array[$i]["a_list"]["tid"])."' and `tid`='".$array[$i]["a_list"]["tid"]."' and `frees`!='' limit 1");
					if($queyrp->num_rows()>0)
					{
						$resultp=$queyrp->row_array();
						$array[$i]["a_list"]["free_time"]=$resultp["frees"];

					}
				

					$array[$i]["a_list"]["time_peak"]=$time_peak;
					
					$array[$i]["a_list"]["time_slack"]=$time_slack;				
					
					unset($array[$i]["a_list"]["money_desc"]);
				}
				
				$i++;	
			}
			json_array2("10000","成功",$array);
		}
		
	}