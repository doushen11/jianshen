<?php
	
	class Demos extends CI_Controller
	{
		
		function abcde()
		{
			message_insert("上课提醒","您在今日下午有一节瑜伽课哦，请准时出发",1,$this->db);	
		}
		
		function mmmsss()
		{
			$query=$this->db->query("select * from `dg_user` order by `id` asc");
			foreach($query->result_array() as $array)
			{
				$b=$array["balance"];
				$querys=$this->db->query("select sum(`money_remaining`) as `moneys` from `dg_pay_order` where `uid`='".$array["id"]."'");
				$results=$querys->row_array();
				if($results["moneys"]==$b || ($b==0 && $results["moneys"]==""))
				{
						
				}
				else
				{
					echo $array["mobile"]."数据不合格,账户余额：".$b.";实际段落金额：".$results["moneys"]."<hr></hr>";	
					$this->db->query("delete from `dg_pay_order` where `uid`='".$array["id"]."'");//删除这个会员的所有信息
					$_array=array(
						"uid"=>$array["id"],
						"uid_index"=>right_index($array["id"]),
						"money"=>$b,
						"money_remaining"=>$b,
						"order_id"=>date("YmdHis").substr(microtime(),2,8),
						"trade_index"=>"",
						"pay_act"=>3,
						"time"=>time(),
					);
					$_array["trade_index"]=$_array["order_id"];
					$this->db->insert("pay_order",$_array);
				}
			}	
		}
		
		function haha()
		{
			$query=$this->db->query("select * from `dg_orders`");
			foreach($query->result_array() as $array)
			{
				if($array["act"]==1)
				{
					$qy=$this->db->query("select * from `dg_tearch_plan_list` where `id`='".$array["class_id"]."'");
					$rs=$qy->row_array();
					$date=date("Y-m-d H:i",$rs["start_time"]).":00";
					$this->db->query("update `dg_orders` set `class_time`='$date' where `id`='".$array["id"]."'");
				}
				else
				{
					$qy=$this->db->query("select * from `dg_tearch_plans` where `id`='".$array["pid"]."'");
					$rs=$qy->row_array();
					$date=date("Y-m-d H:i",$rs["start_time"]).":00";
					$this->db->query("update `dg_orders` set `class_time`='$date' where `id`='".$array["id"]."'");	
				}
				echo "<pre>";
				echo $date."_______".$rs["start_time"]."_______";
				//print_r($rs);
			}	
		}
		
		function tests()
		{
			$cid="5431251c92bfe79c852135a65029708f";
				
			if(isset($_GET["cid"]) && trim($_GET["cid"])!="")
			{
				$cid=trim($_GET["cid"]);		
			}	
				
			$_arrays=array("message"=>"hello，this class is over","type"=>2,"id"=>"0","push_id"=>$cid,"title"=>"class msg title");
			
			a_all_push($_arrays);
		}
		
		function koma()
		{
			$time=strtotime("2016-09-01 08:30:00");
			$query=$this->db->query("select * from `dg_user` where `id`='8'");
			$rs=$query->row_array();
			$this->get_now_money($rs,$time);
		}
		
		//进门到出门所需要花费的金额
		public function get_now_money($rs,$time)
		{
			$money=0;//计算总体金额
			
			$query=$this->db->query("select `money`,`money_remaining` from `dg_pay_order` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `money_remaining`>0");
			
			$arr=array();
			
			$i=0;
			
			$state=1;//设置账户余额问题
			
			
			foreach($query->result_array() as $array)
			{
				$arr[$i]=$this->get_moneys($array["money"],$array["money_remaining"]);
				
				$i++;
			}			
			
			$end_time=time();
			$query=$this->db->query("select * from `dg_time_model` where `act`='1'");
			
			//计算高峰期和低峰期时间花的时间结束
			
			for($i=0;$i<count($arr);$i++){
			
				//print_r($arr[$i]);
			
				foreach($query->result_array() as $array)
				{
					
					$hours=date("H",$time);
					if($time<$end_time){
						if($array["min"]<$array["max"])
						{
							if($array["min"]<=$hours && $hours<$array["max"])
							{
								//成立，开始从这个节点计算金额
								if($array["model"]==1)
								{
									//高峰期计算价格
									$times=($arr[$i]["peak"]/3600);//计算高峰期的每秒的价格
									$t=intval($arr[$i]["money"]/$times);
									
									$n_time=$time+$t;//组成新的新的时间，来对高峰期和低峰期比对，如果进入低峰期的话，要拆解，反之则继续进行
									

									//还没有达到或者持平当前时间节点，直接删除取得价格
									$nh=date("H",$n_time);
									if($this->get_time_modes($n_time,$time,$t,$y)==2)
									{
										//如果合并后的时间已经达到了低峰期，那么重新核算时间和价格
										$l_time=$y-$time;//得到高峰期需要的秒数
										
										$d_money=$times*$l_time; //高峰期在这个节点里面需要的金额
										$money=$money+$d_money;//获取到高峰期所需要花的金额
										
										$s_money=$arr[$i]["money"]-$d_money;//获取到对应的剩余金额，剩余的金额来做低峰期的数据处理
										$time=$time+$l_time;
										
										$times=($arr[$i]["slack"]/3600);//计算低峰期的每秒的价格
										$t=intval($s_money/$times);//获取到低峰期能用的秒数
										$n_time=$time+$t;
										
										if($n_time<=$end_time)
										{
											//计算对应的金额，累加起来
											$time=$time+$t;
											$money=$money+$s_money;	
											//费用累计起来
										}
										else
										{
											//超过了对应的金额，对时间重新进行核算，然后扣除其中的一点钱，时间累加上，搞定
											$l_time=$end_time-$time;//计算出还需要的时间，根据时间得到价格
											$money=$money+$l_time*$times;
											
											$time=$end_time;	
											
											$state=2;
											
										}
									}
									else
									{
										//$time=$time+$t;
										//$money=$money+$arr[$i]["money"];//直接累加金额和时间
										if($n_time<=$end_time)
										{
											//计算中对应的金额，开始累加
											$time=$time+$t;
											$money=$money+$arr[$i]["money"];
										}
										else
										{
											//如果超过了对应金额，对时间重新核算一下，然后扣除其中的一点钱，时间累加上，搞定
											$l_time=$end_time-$time;//计算出还需要的时间，根据时间得到价格	
											$money=$money+$l_time*$times;
											
											$time=$end_time;
											
											$state=2;
											
										}
									}	
	
									
								}
								else
								{
									//低峰期计算价格
									
									$times=($arr[$i]["slack"]/3600);//计算高峰期的每秒的价格
									$t=intval($arr[$i]["money"]/$times);
									
									$n_time=$time+$t;//组成新的新的时间，来对高峰期和低峰期比对，如果进入低峰期的话，要拆解，反之则继续进行
									

									//还没有达到或者持平当前时间节点，直接删除取得价格
									$nh=date("H",$n_time);
									if($this->get_time_modes($n_time,$time,$t,$y)==1)
									{
										//如果合并后的时间已经达到了低峰期，那么重新核算时间和价格
										$l_time=$y-$time;//得到低峰期需要的秒数
										
										$d_money=$times*$l_time; //低峰期在这个节点里面需要的金额
										$money=$money+$d_money;//获取到低峰期所需要花的金额
										
										$s_money=$arr[$i]["money"]-$d_money;//获取到对应的剩余金额，剩余的金额来做高峰期的数据处理
										$time=$time+$l_time;
										
										$times=($arr[$i]["peak"]/3600);//计算高峰期的每秒的价格
										$t=intval($s_money/$times);//获取到高峰期能用的秒数
										$n_time=$time+$t;
										
										if($n_time<=$end_time)
										{
											//计算对应的金额，累加起来
											$time=$time+$t;
											$money=$money+$s_money;	
											//费用累计起来
										}
										else
										{
											//超过了对应的金额，对时间重新进行核算，然后扣除其中的一点钱，时间累加上，搞定
											$l_time=$end_time-$time;//计算出还需要的时间，根据时间得到价格
											$money=$money+$l_time*$times;
											
											$time=$end_time;	
											
											$state=2;
											
										}
									}
									else
									{
										//$time=$time+$t;
										//$money=$money+$arr[$i]["money"];//直接累加金额和时间
										if($n_time<=$end_time)
										{
											//计算中对应的金额，开始累加
											$time=$time+$t;
											$money=$money+$arr[$i]["money"];
										}
										else
										{
											//如果超过了对应金额，对时间重新核算一下，然后扣除其中的一点钱，时间累加上，搞定
											$l_time=$end_time-$time;//计算出还需要的时间，根据时间得到价格	
											$money=$money+$l_time*$times;
											
											$time=$end_time;
											
											$state=2;
											
										}
									}									
									
								}
							}	
						}
						else
						{
							if($array["min"]<=$hours || $hours<$array["max"])
							{
								//成立，开始从这个节点计算金额
								
//成立，开始从这个节点计算金额
								if($array["model"]==1)
								{
									//高峰期计算价格
									$times=($arr[$i]["peak"]/3600);//计算高峰期的每秒的价格
									$t=intval($arr[$i]["money"]/$times);
									
									$n_time=$time+$t;//组成新的新的时间，来对高峰期和低峰期比对，如果进入低峰期的话，要拆解，反之则继续进行
									

									//还没有达到或者持平当前时间节点，直接删除取得价格
									$nh=date("H",$n_time);
									if($this->get_time_modes($n_time,$time,$t,$y)==2)
									{
										//如果合并后的时间已经达到了低峰期，那么重新核算时间和价格
										$l_time=$y-$time;//得到高峰期需要的秒数
										
										$d_money=$times*$l_time; //高峰期在这个节点里面需要的金额
										$money=$money+$d_money;//获取到高峰期所需要花的金额
										
										$s_money=$arr[$i]["money"]-$d_money;//获取到对应的剩余金额，剩余的金额来做低峰期的数据处理
										$time=$time+$l_time;
										
										$times=($arr[$i]["slack"]/3600);//计算低峰期的每秒的价格
										$t=intval($s_money/$times);//获取到低峰期能用的秒数
										$n_time=$time+$t;
										
										if($n_time<=$end_time)
										{
											//计算对应的金额，累加起来
											$time=$time+$t;
											$money=$money+$s_money;	
											//费用累计起来
										}
										else
										{
											//超过了对应的金额，对时间重新进行核算，然后扣除其中的一点钱，时间累加上，搞定
											$l_time=$end_time-$time;//计算出还需要的时间，根据时间得到价格
											$money=$money+$l_time*$times;
											
											$time=$end_time;	
											
											$state=2;
											
										}
									}
									else
									{
										//$time=$time+$t;
										//$money=$money+$arr[$i]["money"];//直接累加金额和时间
										if($n_time<=$end_time)
										{
											//计算中对应的金额，开始累加
											$time=$time+$t;
											$money=$money+$arr[$i]["money"];
										}
										else
										{
											//如果超过了对应金额，对时间重新核算一下，然后扣除其中的一点钱，时间累加上，搞定
											$l_time=$end_time-$time;//计算出还需要的时间，根据时间得到价格	
											$money=$money+$l_time*$times;
											
											$time=$end_time;
											
											$state=2;
											
										}
									}	
	
									
								}
								else
								{
									//低峰期计算价格
	
									$times=($arr[$i]["slack"]/3600);//计算高峰期的每秒的价格
									$t=intval($arr[$i]["money"]/$times);
									
									//echo $t;die();
									
									$n_time=$time+$t;//组成新的新的时间，来对高峰期和低峰期比对，如果进入低峰期的话，要拆解，反之则继续进行
									

									//还没有达到或者持平当前时间节点，直接删除取得价格
									$nh=date("H",$n_time);
									
									//echo $this->get_time_modes($n_time,$time,$t,$y);
									
									if($this->get_time_modes($n_time,$time,$t,$y)==1)
									{
										//如果合并后的时间已经达到了低峰期，那么重新核算时间和价格
										$l_time=$y-$time;//得到低峰期需要的秒数
										
										$l_time;
										
										$d_money=$times*$l_time; //低峰期在这个节点里面需要的金额
										
										$money=$money+$d_money;//获取到低峰期所需要花的金额
										
										$s_money=$arr[$i]["money"]-$d_money;//获取到对应的剩余金额，剩余的金额来做高峰期的数据处理
										$time=$time+$l_time;
										
										$times=($arr[$i]["peak"]/3600);//计算高峰期的每秒的价格
										$t=intval($s_money/$times);//获取到高峰期能用的秒数
										$n_time=$time+$t;
										
										//echo date("Y-m-d H:i:s",$n_time);die();
										
										if($n_time<=$end_time)
										{
											//计算对应的金额，累加起来
											$time=$time+$t;
											$money=$money+$s_money;	
											
											//费用累计起来
										}
										else
										{
											//超过了对应的金额，对时间重新进行核算，然后扣除其中的一点钱，时间累加上，搞定
											$l_time=$end_time-$time;//计算出还需要的时间，根据时间得到价格
											//echo $l_time;die();
											
											$money=$money+$l_time*$times;
											
											$time=$end_time;	
											
											$state=2;
											
										}
									}
									else
									{
										//$time=$time+$t;
										//$money=$money+$arr[$i]["money"];//直接累加金额和时间
										if($n_time<=$end_time)
										{
											//计算中对应的金额，开始累加
											$time=$time+$t;
											$money=$money+$arr[$i]["money"];
										}
										else
										{
											//如果超过了对应金额，对时间重新核算一下，然后扣除其中的一点钱，时间累加上，搞定
											$l_time=$end_time-$time;//计算出还需要的时间，根据时间得到价格	
											$money=$money+$l_time*$times;
											
											$time=$end_time;
											
											$state=2;
											
										}
									}									
									
								}								
								
								
							}						
						}
						
					}
				}
				
			}
			if($state==2)
			{
				return sprintf("%.2f",$money);	
			}
			else
			{
				return 0;	
			}
			//echo $state."<hr></hr>";
			//echo sprintf("%.2f",$money);
		}		
		
		function abcd()
		{
			$str="sSB/FkBfzox4SiFtfX2x4ZIm7xQu9iQcaQOgNbz5+FKG6mXi9QeNkGrImHwEg5XGIIgPrWHhsqo+WM+FmNxJjcnB9J9L+nUYVaNvKFOs2nkpxAFGVunivlStkTU7vDFoyWa8TbeEWtFL+1jywE8uMxO7hhoobimVdqsehGFvYis=";
			
			$result=$this->encrypt->decode($str);
			
			print_r($result);	
			
			//echo time();
		}
		
		function beyond()
		{
			
			//计算高低峰时间
			$query_a=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='1' and `model`='1'");
			
			$query_b=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='1' and `model`='2'");
			
			$result_a=$query_a->row_array();
			
			$result_b=$query_b->row_array();
			
			$height_time=$result_a["min"].":00-".$result_a["max"].":00";
			
			$low_time=$result_b["min"].":00-".$result_b["max"].":00";
			//计算高低峰时间
			
			$money=8000;
			
			//计算高低峰价格
			
			$query_model=$this->db->query("select `money_peak`,`money_slack` from `dg_pay_model` where (`min`<='$money' and `max`>'$money') or (`min`<='$money' and `max`='0')");
			
			if($query_model->num_rows()>0)
			{
				$result_model=$query_model->row_array();
				$height_money=$result_model["money_peak"];
				$low_money=$result_model["money_slack"];
			}
			else
			{
				$height_money="未知";
				$low_money="未知";	
			}
			
			
			$nickname="小沈阳";
			
			$m_result["push_key"]="df67d3ab0d676ba08a4333ceeacefeea";
			
			
			
			require FCPATH."config/push.inc.php";
			
			
			$msg=str_replace("{name}",$nickname,$push_inc["pay_message"]);
			
			$msg=str_replace("{money}",$money,$msg);
			
			$msg=str_replace("{height}",$height_time,$msg);
			
			$msg=str_replace("{low}",$low_time,$msg);
			
			$msg=str_replace("{height_money}",$height_money,$msg);
			
			$msg=str_replace("{low_money}",$low_money,$msg);
			
			//echo $msg;
			
			$m_result["push_key"]="40f656f33efac5a740f81d7457e594f5";
			
			$_arrays=array("message"=>$msg,"type"=>2,"id"=>"0","push_id"=>$m_result["push_key"],"title"=>$push_inc["pay_title"]);
			
			print_r($_arrays);
			
			c_push($_arrays);
			
						
			//echo c_push(array("message"=>"您的账户余额已不足，请您及时充值","type"=>1,"id"=>1,"push_id"=>"df67d3ab0d676ba08a4333ceeacefeea","title"=>"您有新的未读消息"));		
		}
		
		private function get_moneys($money,$money_remaining)
		{
			//$query=$this->db->query("select `min`,`max`,`model` from `dg_time_model` where `act`='1'");
			
			$querys=$this->db->query("select * from `dg_pay_model`");
			foreach($querys->result_array() as $arrays)
			{
				if($arrays["min"]<=$arrays["max"])
				{
					if($arrays["min"]<=$money && $money<$arrays["max"])
					{
						//成立返回高峰期价格和低峰期价格
						return array("peak"=>$arrays["money_peak"],"slack"=>$arrays["money_slack"],"money"=>$money_remaining);	
					}	
				}
				else
				{
					if($arrays["min"]<=$money)
					{
						//成立返回高峰期价格和低峰期价格
						return array("peak"=>$arrays["money_peak"],"slack"=>$arrays["money_slack"],"money"=>$money_remaining);	
					}	
				}	
			}	
		}
		
		private function get_time_modes($hour,$times,$t,&$y)
		{
			//根据时间求高低峰model类型
			$hour=intval($hour);
			//echo $hour;
			
			$query=$this->db->query("select `min`,`max`,`model` from `dg_time_model` where `act`='1'");
			foreach($query->result_array() as $array)
			{	
				if($array["min"]<$array["max"])
				{
					
					if($hour>=$array["min"] && $hour<$array["max"])
					{
						
						//在当天
						$y=strtotime(date("Y-m-d")." ".$array["max"].":00:00");	

						
						if(($t/3600)>12)
						{
							if($array["model"]==1)
							{
								return 2;	
							}	
							return 1;
						}
						return $array["model"];
					}
				}
				else
				{
					if($hour>=$array["min"] || $hour<$array["max"])
					{
						
						if($hour<24)
						{
							//在前一天	
							$y=strtotime(date("Y-m-d",time()+3600*24)." ".$array["max"].":00:00");	
						}
						else
						{
							//在当天
							$y=strtotime(date("Y-m-d")." ".$array["max"].":00:00");	
						}
						
							
						if(($t/3600)>12)
						{
							
							if($array["model"]==1)
							{
								return 2;	
							}	
							return 1;
						}
						else
						{
							
						}
						return $array["model"];
					}
				}
			}
		}
		
		private function get_times($arr,$time)
		{
			echo date("Y-m-d H:i:s",$time);
			echo "<pre></pre>";
			$query=$this->db->query("select `min`,`max`,`model` from `dg_time_model` where `act`='1'");
			//echo time();
			for($i=0;$i<count($arr);$i++)
			{
				foreach($query->result_array() as $array)
				{
					$hour=date("H",$time);
					if($array["min"]<$array["max"])
					{
						if($hour>=$array["min"] && $hour<$array["max"])
						{
							//在这个区间里面，开始进行对应的数据换算
							//echo $array["min"]."______";
							if($array["model"]==1)
							{
								//高峰期算法	
								$times=($arr[$i]["peak"]/3600);//计算高峰期的每秒的价格
								$t=ceil($arr[$i]["money"]/$times);
								$n_time=date("H",$time+$t);//组成新的新的时间，来对高峰期和低峰期比对，如果进入低峰期的话，要拆解，反之则继续进行
								if($this->get_time_modes($n_time,$time,$t,$y)==2)
								{
									//如果是低峰期时间，重新核算时间和价格
									
									$l_time=$y-$time;//得到高峰期需要的秒数
									
									$d_money=$times*$l_time; //高峰期在这个节点里面需要的金额
									
									$s_money=$arr[$i]["money"]-$d_money;//获取到对应的剩余金额
									
									$time=$y;
									
									$time=$time+$this->get_di_time($s_money,$arr[$i]["slack"]);
									
								}
								else
								{
									$time=$time+$t;	
								}
							}	
							else
							{
								//低峰期算法
								$times=($arr[$i]["slack"]/3600);//计算低峰期的每秒的价格
								
								//echo $times;die();
								//echo $arr[$i]["money"];die();
								
								$t=ceil($arr[$i]["money"]/$times);
								
								$n_time=date("H",$time+$t);//组成新的新的时间，来对高峰期和低峰期比对，如果进入高峰期的话，要拆解，反之则继续进行
								//echo $t;die();
								//echo $this->get_time_modes($n_time);
								
								if($this->get_time_modes($n_time,$time,$t,$y)==1)
								{
									//echo date("Y-m-d H:i:s",$y);die();
									
									$l_time=$y-$time;//得到低峰期需要的秒数
									
									$d_money=$times*$l_time; //低峰期在这个节点里面需要的金额
									
									$s_money=$arr[$i]["money"]-$d_money;//获取到对应的剩余金额
									
									$time=$y;
									
									$time=$time+$this->get_gao_time($s_money,$arr[$i]["peak"]);

									//echo $l_time;
									//如果为高峰期，拆解信息,计算余额，然后对剩余余额进行高峰期计算，得出后者时间
									//$l_time/
									//echo 100;	
								}
								else
								{
									$time=$time+$t;	
								}
									
									
								//echo date("Y-m-d H:i:s",$time);
								//die();
								//echo $times;	
								//print_r($arr[$i]);
							}	
						}	
					}	
					else
					{
						if($hour>=$array["min"] || $hour<$array["max"])
						{
							//在这个区间里面，开始进行对应的数据换算
							//echo $array["min"]."______";
							if($array["model"]==1)
							{
								//高峰期算法	
								$times=($arr[$i]["peak"]/3600);//计算高峰期的每秒的价格
								$t=ceil($arr[$i]["money"]/$times);
								$n_time=date("H",$time+$t);//组成新的新的时间，来对高峰期和低峰期比对，如果进入低峰期的话，要拆解，反之则继续进行
								if($this->get_time_modes($n_time,$time,$t,$y)==2)
								{
									//如果是低峰期时间，重新核算时间和价格
									
									$l_time=$y-$time;//得到高峰期需要的秒数
									
									$d_money=$times*$l_time; //高峰期在这个节点里面需要的金额
									
									$s_money=$arr[$i]["money"]-$d_money;//获取到对应的剩余金额
									
									$time=$y;
									
									$time=$time+$this->get_di_time($s_money,$arr[$i]["slack"]);
									
								}
								else
								{
									$time=$time+$t;	
								}
							}	
							else
							{
								//低峰期算法
								$times=($arr[$i]["slack"]/3600);//计算低峰期的每秒的价格
								
								//echo $times;die();
								//echo $arr[$i]["money"];die();
								
								$t=ceil($arr[$i]["money"]/$times);
								
								$n_time=date("H",$time+$t);//组成新的新的时间，来对高峰期和低峰期比对，如果进入高峰期的话，要拆解，反之则继续进行
								//echo $t;die();
								//echo $this->get_time_modes($n_time);
								
								if($this->get_time_modes($n_time,$time,$t,$y)==1)
								{
									//echo date("Y-m-d H:i:s",$y);die();
									
									$l_time=$y-$time;//得到低峰期需要的秒数
									
									$d_money=$times*$l_time; //低峰期在这个节点里面需要的金额
									
									$s_money=$arr[$i]["money"]-$d_money;//获取到对应的剩余金额
									
									$time=$y;
									
									$time=$time+$this->get_gao_time($s_money,$arr[$i]["peak"]);

									//echo $l_time;
									//如果为高峰期，拆解信息,计算余额，然后对剩余余额进行高峰期计算，得出后者时间
									//$l_time/
									//echo 100;	
								}
								else
								{
									$time=$time+$t;	
								}
									
									
								//echo date("Y-m-d H:i:s",$time);
								//die();
								//echo $times;	
								//print_r($arr[$i]);
							}	
						}	
					}
				}	
			}	
			return $time;		
		}
		
		private function get_gao_time($money,$peak)
		{
			$m=$peak/3600;
			return $money/$m;	
		}
		
		private function get_di_time($money,$slack)
		{
			$m=$slack/3600;
			return $money/$m;	
		}
			
		function a()
		{
			//$this->load->view("a.php");
			//$_arrays=array("message"=>"011111","type"=>5,"id"=>"0","push_id"=>"df67d3ab0d676ba08a4333ceeacefeea","title"=>"beyond");
			//c_push($_arrays);
			
			$query=$this->db->query("select `money`,`money_remaining` from `dg_pay_order` where `uid_index`='4' and `uid`='4' and `money_remaining`>0");
			
			$arr=array();
			
			$i=0;
			
			foreach($query->result_array() as $array)
			{
				$arr[$i]=$this->get_moneys($array["money"],$array["money_remaining"]);
				
				$i++;
				//print_r($arr);
			}
			
			//print_r($arr);
			$times=$this->get_times($arr,time());//1472522360"1472522360"
			
			echo date("Y-m-d H:i:s",$times);
			
			//msn(15871422133,"hello,小沈阳");
		}
	}