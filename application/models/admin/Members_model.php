<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Members_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
	
		public function door_out_sub($id)
		{
			//学员出门
			$date=$this->input->post("y1")."-".$this->input->post("m1")."-".$this->input->post("d1")." ".$this->input->post("h1").":".$this->input->post("i1").":".$this->input->post("s1");
			$date=strtotime($date);//组合成结束时间戳
			//查询除对应的会员信息
			$query=$this->db->query("select * from `dg_user` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["doors"]==1)
				{
					$querys=$this->db->query("select `start_time` from `dg_doors` where `uid`='".$result["id"]."' and `act`='1' order by `id` desc limit 1");
					$s_result=$querys->row_array(); 
					if($date<=$s_result["start_time"])
					{
						ajaxs(30000,"抱歉:出门时间不能小于或者等于进门时间");		
					}	
					else
					{
						$money=$this->demos1($result,$s_result["start_time"],$date);
						//echo $money;die();
						if($money<=0)
						{
							ajaxs(30000,"抱歉:出门金额获取失败");		
						}
						else
						{
							//开始出门操作
							echo $this->outs_do($result["id"],$date,$money);		
						}
					}
				}	
				else
				{
					ajaxs(30000,"抱歉:当前会员不在健身房内，无法继续操作");		
				}
			}	
			else
			{
				ajaxs(30000,"抱歉:没有找到会员信息");	
			}
		}
		
		
		public function outs_do($uid,$ends,$moneys)
		{
			
			//出门计费处理，bingo
			$end=$ends;	
			$sql="select `id`,`end_time`,`start_time` from `dg_doors` where `uid`='$uid' and `act`='1' order by `id` desc limit 1";
			$query=$this->db->query($sql);
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["end_time"]=="" && !is_numeric($result["end_time"]))
				{
					
					$this->db->trans_strict(false);
					$this->db->trans_begin();	
					
					$query1=$this->db->query("select * from `dg_user` where `id`='$uid'");
					
					if($query1->num_rows()<=0)
					{
						$this->db->trans_rollback();
						ajaxs(30000,"没有找到对应用户信息");					
					}
					
					$rs=$query1->row_array();
					
					//开始做事务信息处理
					
					$moneys1=$moneys;
					
					if($moneys<=0)
					{
						$this->db->trans_rollback();
						ajaxs(30000,"抱歉:出门金额获取失败");
					}			
					else
					{
						//开始扣费处理
						
						$querys=$this->db->query("select `money_remaining`,`id` from `dg_pay_order` where `uid`='$uid' and `uid_index`='".right_index($uid)."' and `money_remaining`>0 order by `id` asc");
						
						foreach($querys->result_array() as $arrays)
						{
							if($moneys>0)
							{
								if($arrays["money_remaining"]>$moneys)
								{
									$ye=$arrays["money_remaining"]-$moneys;
									$this->db->query("update `dg_pay_order` set `money_remaining`='$ye' where `id`='".$arrays["id"]."'");
									$moneys=0;
								}	
								else
								{
									$moneys=$moneys-$arrays["money_remaining"];
									$this->db->query("update `dg_pay_order` set `money_remaining`='0' where `id`='".$arrays["id"]."'");
								}
							}
						}
						
						if($moneys>0)
						{
							$this->db->trans_rollback();
							ajaxs(30000,"抱歉:账户余额不足");
						}
						//更新账户余额
						$this->db->query("update `dg_user` set `balance`=`balance`-'$moneys1',`doors`='0',`doors_keys`='' where `id`='$uid'");
						//更新账户余额
						
						//更新出门记录
						$_array=array(
							"end_time"=>$end,
							"money"=>$moneys1,
						);	
						$this->db->update("doors",$_array,array("id"=>$result["id"]));
						//更新这条出门记录
						
						//减去实时人数信息
						mysql_query("update `dg_config` set `people`=`people`-'1' where `id`='1'");
						//查询实时人数，如果为0，断电操作
						
						
						if($this->db->trans_status()==true){
							//$this->db->trans_commit();
							$this->db->trans_commit();
							$this->close_l();
							ajaxs(10000,"出门成功");
						}else{
							$this->db->trans_rollback();
							ajaxs(30000,"抱歉:网络连接失败");
						}						
					}	
				}
				else
				{
					ajaxs(30000,"抱歉:网络连接失败");
				}
			}
			else
			{
				ajaxs(30000,"抱歉:网络连接失败");
			}

		}
		
		private function close_l()
		{
			//$f=fopen(FCPATH."1111.php","w");
			//fwrite($f,date("Y-m-d H:i:s"));
			$query=$this->db->query("select * from `dg_config` where `id`='1'");
			$result=$query->row_array();
			if($result["people"]==0)
			{
				//健身房没人了关电
				$urls=base_url()."apis/l_close.php?keys=1282d94a4f461110b676f711b221d86a76b8a8008982064b6bc94d08ec2b58fff9023a3699ba8e03adebe8a12359bb772ee639a5c418c908";		
				
					
				$ch = curl_init($urls) ;  
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
				curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
				$output = curl_exec($ch);
				//echo $output;die();
				curl_close($ch);
				return $output;	
				
			}			
		}		
		
		public function demos1($rs,$time,$end_time)
		{
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
				if($end_time=="")
				{
					$end_time=time();	
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
				//print_r($arr);die();
				for($i=0;$i<count($arr);$i++)
				{
					//开始根据对应的折扣信息扣费了
					//第一步：获取当前的时间段在哪个段位里面，开始累计扣费
					if($_SESSION["money_checks"]==0)
					{
						
						if(isset($_SESSION["time_messages"]))
						{
							//print_r($arr[$i]);die();
							//echo 1;
							$this->more_counts($arr[$i],$_SESSION["time_messages"]["time"],$_SESSION["time_messages"]["end_time"],$time_query,$_SESSION["time_messages"]["time_value"]);
							
						}
						else
						{
							//echo 2;
							$this->more_counts($arr[$i],$time,$end_time,$time_query,$time_value);
							
						}
					}
					
					
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
			//if(isset($_SESSION["time_messages"]))
			//{
				//echo $time_value."___________".$array["money"];die();
			//}
			if($time_value>0 && $array["money"]>0)
			{
				//剩余时间大于0秒的时候，进行换算数据
				$time_models=$this->get_time_modeler($time,$time_query,$next_time);

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
							/*if(isset($_SESSION["time_messages"]) && $a==2)
							{
								//echo $u_times;die();
								//print_r($_SESSION["time_messages"]);
								//echo $sum_times."_____".$end_time."______".$time;
								//echo $_SESSION["money_sums"];
								//die();
							}*/							
							
	
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
					//echo $next_time."___________".$end_time;
					//echo "}}}}}}}}}}}}}}}}]";
					if($next_time>=$end_time)
					{
						//echo 2;die();
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
							
							//echo "<pre>";
							//print_r($_SESSION);
							//die();
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
		}//b5c41e190043c05ab56d41b95195c25447915ff578fd1ac7ae494381754f989a2620dd90_ee2d5b980cde09bfc37459d93a6330065d88437e_44713050
		
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
		
		public function sikes_dels($id)
		{
			$query=$this->db->query("select `id` from `dg_orders` where `id` in (".$id.") and (`state`='1' or `state`='2' or `state`='3') limit 1");	
			if($query->num_rows()>0)
			{
				ajaxs(30000,"抱歉：当前删除订单中含有不可删除类型订单，系统拒绝您的请求");	
			}
			else
			{
				$this->db->query("delete from `dg_orders` where `id` in (".$id.")");
				ajaxs(10000,"删除成功");	
			}
		}
		
		public function indexs_updates($id)
		{
			$mobile=$this->input->post("mobile");
			$nickname=$this->input->post("nickname");
			$passwd=$this->input->post("passwd");
			$query=$this->db->query("select `id` from `dg_user` where `mobile`='$mobile' and `id`!='$id' limit 1");
			if($query->num_rows()>0)
			{
				ajaxs(30000,"手机号已被注册");die();
			}	
			else
			{
				if($nickname!="")
				{
					$query=$this->db->query("select `id` from `dg_user` where `nickname`='$nickname' and `id`!='$id' limit 1");
					if($query->num_rows()>0)
					{
						ajaxs(30000,"用户名已经被注册");die();
					}						
				}	
				$_array=array(
					"mobile"=>$mobile,
					"nickname"=>$nickname,
					"avatar"=>$this->input->post("avatar"),
					"gender"=>$this->input->post("gender"),
					"brithday"=>$this->input->post("brithday"),
					"height"=>$this->input->post("height"),
					"weight"=>$this->input->post("weight"),
					"professional"=>$this->input->post("professional"),
					"state"=>$this->input->post("state"),
				);
				
				if($this->db->update("user",$_array,array("id"=>$id)))
				{
					ajaxs(10000,"修改成功");	
				}
				else
				{
					ajaxs(30000,"网络连接失败");	
				}
			}
		}
		
		public function changes($id)
		{
			$query=$this->db->query("select `state` from `dg_user` where `id`='$id' limit 1");
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
				$this->db->query("update `dg_user` set `state`='$st' where `id`='$id'");
				ajaxs(10000,$st);die();
			}
			else
			{
				ajaxs(30000,"没有找到对应用户信息");die();	
			}	
		}
		
		public function oneline_pay_sub($id)
		{
			$moneys=$this->input->post("moneys");
			$this->db->trans_strict(false);
			$this->db->trans_begin();
			$_array=array(
				"uid"=>$id,
				"uid_index"=>right_index($id),
				"money"=>$moneys,
				"money_remaining"=>$moneys,
				"order_id"=>date("YmdHis").substr(microtime(),2,8),
				"trade_index"=>date("YmdHis").substr(microtime(),2,8),
				"pay_act"=>3,
				"time"=>time(),
			);	
			$this->db->insert("pay_order",$_array);
			$this->db->query("update `dg_user` set `balance`=`balance`+'$moneys' where `id`='$id'");
			if($this->db->trans_status()==true)
			{
				$this->db->trans_commit();
				ajaxs(10000,"充值成功");
			}
			else
			{
				$this->db->trans_rollback();
				ajaxs(30000,"充值失败：网络连接错误");	
			}
		}
		
		public function oneline_draw_sub($id)
		{
			$this->db->trans_strict(false);
			$this->db->trans_begin();	
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_user` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$moneys=$this->input->post("moneys");
				$desc=$this->input->post("desc");	
				if($result["balance"]<$moneys)
				{
					$this->db->trans_rollback();
					ajaxs(30000,"账户余额不足");
				}			
				else
				{
					$this->db->query("update `dg_user` set `balance`=`balance`-'$moneys' where `id`='$id'");//更新账户信息
					$_array=array(
						"uid"=>$id,
						"money"=>$moneys,
						"desc"=>$desc,
						"time"=>time(),
					);	
					$this->db->insert("user_draw",$_array);
					//添加一条提现纪录
					$querys=$this->db->query("select `money_remaining`,`id` from `dg_pay_order` where `money_remaining`>0 and `uid`='$id' order by `id` asc");
					foreach($querys->result_array() as $arrays)
					{
						if($moneys>0){
							if($arrays["money_remaining"]>$moneys)
							{
								$money=$arrays["money_remaining"]-$moneys;
								$this->db->query("update `dg_pay_order` set `money_remaining`='$money' where `id`='".$arrays["id"]."'");	
								$moneys=0;
							}
							else
							{
								$moneys=$moneys-$arrays["money_remaining"];
								$this->db->query("update `dg_pay_order` set `money_remaining`='0' where `id`='".$arrays["id"]."'");
							}
						}
					}
					//减去对应的节点记录
					if($this->db->trans_status()==true){
						//$this->db->trans_commit();
						$this->db->trans_commit();
						ajaxs(10000,"提现操作成功");
					}else{
						$this->db->trans_rollback();
						ajaxs(30000,"网络连接失败！");
					}
					
				}
			}	
			else
			{
				ajaxs(30000,"抱歉：用户信息读取失败！");
			}				
		}
		
		//删除会员
		public function dels($id)
		{
			$this->db->query("delete from `dg_user` where `id` in (".$id.")");	
			$this->db->query("delete from `dg_pay_order` where `uid` in (".$id.")");	
			$this->db->query("delete from `dg_orders` where `uid` in (".$id.")");	
			$this->db->query("delete from `dg_note` where `uid` in (".$id.")");	
			$this->db->query("delete from `dg_doors` where `uid` in (".$id.") and `act`='1'");	
			//$this->db->query("delete from `dg_note` where `uid` in (".$id.")");	
			//$this->db->query("delete from `dg_note` where `uid` in (".$id.")");	
			//$this->db->query("delete from `dg_note` where `uid` in (".$id.")");	
			ajaxs(10000,"删除成功");
		}
		
	}