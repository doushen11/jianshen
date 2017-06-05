<?php
	
	//对位操作开门出门的model类
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Opens_model extends CI_Model
	{

		protected $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		private function get_moneys($money,$money_remaining)
		{
			//$query=$this->db->query("select `min`,`max`,`model` from `dg_time_model` where `act`='1'");
			
			$querys=$this->db->query("select * from `dg_pay_model`");
			foreach($querys->result_array() as $arrays)
			{
				if($arrays["min"]<=$arrays["max"])
				{
					if($arrays["min"]<=$money && $money<=$arrays["max"])
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
						
						$end_times=$t+$times;
						
						//判断当前这笔款能够容纳的时间是否超过当前时间的最后时间
						if($end_times>=$y)
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
						
						$end_times=$t+$times;
						
						if($end_times>=$y)
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
		
		//进门统计金额
		public function openings($rs)
		{
			if($rs["push_key"]!="")
			{
				//开始计算对应的进门出门价格	
				$query=$this->db->query("select `money`,`money_remaining` from `dg_pay_order` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `money_remaining`>0");
				
				$arr=array();
				
				$i=0;
				
				
				
				foreach($query->result_array() as $array)
				{
					$arr[$i]=$this->get_moneys($array["money"],$array["money_remaining"]);
					
					$i++;
				}
				
				$times=$this->get_times($arr,time());//1472522360"1472522360"
				
				$out_time=date("Y-m-d H:i:s",$times);	
				
				//开始加载推送信息进行推送
				require FCPATH."config/push.inc.php";	
				
				$msg=str_replace("{time}",$out_time,$push_inc["door_message"]);
	
				$_arrays=array("message"=>$msg,"type"=>1,"id"=>"0","push_id"=>$rs["push_key"],"title"=>$push_inc["door_title"]);
			
				
				c_push($_arrays);			

			}		
		}
		
		//进门到出门所需要花费的金额
		public function get_now_money($rs,$time,$end_time=null)
		{
			//开启金额换算调试
			/*$files_r=fopen(FCPATH."times_debug.php","a");
			fwrite($files_r,$rs["id"]."____________".$time."______________".$end_time."_________________".date("Y-m-d H:i:s")."<hr></hr>");
			//开启金额换算调试
			
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
			if($end_time=="")
			{
				$end_time=time();	
			}
			
			//echo date("Y-m-d H:i:s",$end_time);die();
			
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
									
									//echo date("Y-m-d H:i:s",$n_time);die();
									//echo $this->get_time_modes($n_time,$time,$t,$y);die();
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
			//exit();
			if($state==2)
			{
				return sprintf("%.2f",$money);	
			}
			else
			{
				return 0;	
			}*/
			//echo $state."<hr></hr>";
			//echo sprintf("%.2f",$money);
			if($end_time=="")
			{
				$end_time=time();	
			}
			if($time<$end_time)
			{
				//计算出当前出门金额值信息
				$money=0;//计算总体金额
				$query=$this->db->query("select `money`,`money_remaining` from `dg_pay_order` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `money_remaining`>0 order by `id` asc");
				$arr=array();
				$i=0;
				$state=1;//设置账户余额问题
				foreach($query->result_array() as $array)
				{
					$arr[$i]=$this->get_moneys($array["money"],$array["money_remaining"]);
					
					$i++;
				}			

				
				$time_value=$end_time-$time;//获取当前用户进门时间差值
				if(isset($_SESSION["money_sums"]))
				{
					unset($_SESSION["money_sums"]);
				}
				if(isset($_SESSION["time_messages"]))
				{
					unset($_SESSION["time_messages"]);
				}
				$_SESSION["money_sums"]=0;
				$_SESSION["money_checks"]=0;
				$time_query=$this->db->query("select * from `dg_time_model` where `act`='1'");
				for($i=0;$i<count($arr);$i++)
				{
					//开始根据对应的折扣信息扣费了
					//第一步：获取当前的时间段在哪个段位里面，开始累计扣费
					if($_SESSION["money_checks"]==0)
					{
						if(isset($_SESSION["time_messages"]))
						{
							$this->more_counts($arr[$i],$_SESSION["time_messages"]["time"],$_SESSION["time_messages"]["end_time"],$time_query,$_SESSION["time_messages"]["time_value"]);
						}
						else
						{
							$this->more_counts($arr[$i],$time,$end_time,$time_query,$time_value);
						}
					}
					//sprintf("%.2f",)
					//echo $s_money;
				}
				if($_SESSION["money_checks"]==1)
				{
					return $_SESSION["money_sums"];
				}
				else
				{
					return 0;
				}
				
				//print_r($arr);				
			}
			else
			{
				return 0;
			}
		}
		
		private function more_counts($array,$time,$end_time,$time_query,$time_value)
		{
			set_time_limit(0);
			//if($array["money"]<=1200)
			//{
				//echo "<pre>";
				//print_r($array);
				//exit();
			//}
			//对每个时间段进行累减处理
			if($time_value>0 && $array["money"]>0)
			{
				//剩余时间大于0秒的时候，进行换算数据
				$time_models=$this->get_time_modeler($time,$time_query,$next_time);
				//echo $time_models;
				if($time_models==2)
				{
					//低峰期时间段价格核算减除
					$s_money=$array["slack"]/3600;
					//获取账户余额能够支撑多久秒数
					$u_times=ceil($array["money"]/$s_money);
			
					//获取当前的时间端，合计一下当前的时间
					$sum_times=$time+$u_times;
					if($next_time>=$end_time)
					{
						
						//一个时间段内能搞定对应的结束时间
						if($sum_times>=$end_time)
						{
							//足足有余，获取当前费用信息
							$_SESSION["money_sums"]=$_SESSION["money_sums"]+sprintf("%.2f",$time_value*$s_money);
							$time_value=0;
							$_SESSION["money_checks"]=1;//终止核算节点生成
							return $_SESSION["money_sums"];
						}
						else
						{
							//费用不够，进行下次累减
							$time=$time+$u_times;
							//生成session信息供下次金额使用
							$_SESSION["time_messages"]["time"]=$time;
							$_SESSION["time_messages"]["end_time"]=$end_time;
							$_SESSION["time_messages"]["time_value"]=$end_time-$time;
							//echo $time;die();
							//减去当前的时间端的所有金额，累加，进行下一次的金额换算
							$_SESSION["money_sums"]=$_SESSION["money_sums"]+$array["money"];
							return $_SESSION["money_sums"];
						}
					}
					else
					{
						//两个时间端搞定的信息
						
						//先搞定低峰时间段内的数据
						if($sum_times>=$next_time)
						{
							
							//echo date("Y-m-d H:i:s",$time)."_____________".date("Y-m-d H:i:s",$end_time)."_______________".$time_value."____1<hr></hr>";
							//余额足够支付对应的低峰期的信息
							$s_money=sprintf("%.2f",($next_time-$time)*$s_money);
							$array["money"]=$array["money"]-$s_money;
							$_SESSION["money_sums"]=$_SESSION["money_sums"]+$s_money;
							$time_value=$end_time-$next_time;
							
							return $this->more_counts($array,$next_time,$end_time,$time_query,$time_value);
						}
						else
						{
							//余额不够支付低峰期的信息，减完信息后重组再换算
							$time=$time+$u_times;
							//生成session信息供下次金额使用
							$_SESSION["time_messages"]["time"]=$time;
							$_SESSION["time_messages"]["end_time"]=$end_time;
							$_SESSION["time_messages"]["time_value"]=$end_time-$time;
							//减去当前的时间端的所有金额，累加，进行下一次的金额换算
							$_SESSION["money_sums"]=$_SESSION["money_sums"]+$array["money"];
							return $_SESSION["money_sums"];
						}
					}
				}
				elseif($time_models==1)
				{
					
					//当前为高峰期
					//高峰期时间段价格核算减除
					$s_money=$array["peak"]/3600;
					//获取账户余额能够支撑多久秒数
					$u_times=ceil($array["money"]/$s_money);
					//获取当前的时间端，合计一下当前的时间
					$sum_times=$time+$u_times;
					if($next_time>=$end_time)
					{
						//一个时间段内能搞定对应的结束时间
						if($sum_times>=$end_time)
						{
							//足足有余，获取当前费用信息
							$_SESSION["money_sums"]=$_SESSION["money_sums"]+sprintf("%.2f",$time_value*$s_money);
							$time_value=0;
							$_SESSION["money_checks"]=1;//终止核算节点生成
							return $_SESSION["money_sums"];
						}
						else
						{
							//费用不够，进行下次累减
							$time=$time+$u_times;
							//生成session信息供下次金额使用
							$_SESSION["time_messages"]["time"]=$time;
							$_SESSION["time_messages"]["end_time"]=$end_time;
							$_SESSION["time_messages"]["time_value"]=$end_time-$time;
							//echo $time;die();
							//减去当前的时间端的所有金额，累加，进行下一次的金额换算
							$_SESSION["money_sums"]=$_SESSION["money_sums"]+$array["money"];
							return $_SESSION["money_sums"];
						}
					}
					else
					{
						//多个时间段内能搞定对应的结束信息
						
						//先搞定高峰时间段内的数据
						if($sum_times>=$next_time)
						{
							//echo date("Y-m-d H:i:s",$time)."_____________".date("Y-m-d H:i:s",$end_time)."_______________".$time_value."____".$next_time."_____________2<hr></hr>";
							//余额足够支付对应的低峰期的信息
							$s_money=sprintf("%.2f",($next_time-$time)*$s_money);
							$array["money"]=$array["money"]-$s_money;
							$_SESSION["money_sums"]=$_SESSION["money_sums"]+$s_money;
							$time_value=$end_time-$next_time;
							//echo $time_value;die();
							//print_r($array);die();
							return $this->more_counts($array,$next_time,$end_time,$time_query,$time_value);
						}
						else
						{
							//余额不够支付低峰期的信息，减完信息后重组再换算
							$time=$time+$u_times;
							//生成session信息供下次金额使用
							$_SESSION["time_messages"]["time"]=$time;
							$_SESSION["time_messages"]["end_time"]=$end_time;
							$_SESSION["time_messages"]["time_value"]=$end_time-$time;
							//减去当前的时间端的所有金额，累加，进行下一次的金额换算
							$_SESSION["money_sums"]=$_SESSION["money_sums"]+$array["money"];
							return $_SESSION["money_sums"];
						}
					}
				}
			}
			else
			{  
				return 0;
			}
		}
		
		private function get_time_modeler($time,$time_query,&$next_time)
		{
			//获取当前时间段的类型--高峰/低峰
			$h=intval(date("H",$time));
			if($h==0)
			{
				$h=24;
			}
			
			//echo $time."______________<hr></hr>";
			foreach($time_query->result_array() as $arrays)
			{
				if($arrays["max"]>=$arrays["min"])
				{
					if($arrays["max"]>$h && $h>=$arrays["min"])
					{
						//一天内的核算
						$next_time=strtotime(date("Y-m-d ",$time).$arrays["max"].":00:00");
						//echo "100__________<hr></hr>";
						return $arrays["model"];
					}
				}
				else
				{
					if(($arrays["min"]<=$h && $h<=24) || ($h<$arrays["min"]))
					{
						if($arrays["max"]<=$h)
						{
							//跨天的核算
							$next_time=strtotime(date("Y-m-d ",$time+3600*24).$arrays["max"].":00:00");
						}
						else
						{
							//一天内的核算
							$next_time=strtotime(date("Y-m-d ").$arrays["max"].":00:00");
						}
						
						return $arrays["model"];
					}
				}
				
			}
			
		}
		
	}