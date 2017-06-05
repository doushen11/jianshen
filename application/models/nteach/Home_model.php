<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Home_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//组合教练信息
		private function create_tearchs($query)
		{
			require FCPATH."/config/img.inc.php";
			$arr=array();$i=0;
			foreach($query->result_array() as $array)
			{
				$arr[$i]=$array;
				if(trim($arr[$i]["avatar"])=="")
				{
					$arr[$i]["avatar"]=$img_inc["avatar"];
				}
				if(trim($arr[$i]["bg"])=="")
				{
					$arr[$i]["bg"]=$img_inc["bg"];
				}
				$i++;
			}
			return $arr;
		}
	
		//获取对应的新晋教练列表
		public function index()
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			$sql="select `id`,`realname`,`avatar`,`bg`,`focus`,`score`,`level`,`act` from `dg_teacher` order by `id` desc";		
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);	
			$array=$this->create_tearchs($query);
			json_array2("10000","成功",$array);				
		}
		
		//私课教练详情信息
		private function get_teacher_msg($id)
		{
			$query=$this->db->query("select `id`,`realname`,`avatar`,`bg`,`score`,`level`,`focus_text`,`money_desc`,`birthday`,`desc` from `dg_teacher` where `id`='$id' and `act`='1' limit 1");
			
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				
				//开始加载关注信息
				$result["focus_state"]=0;
				if($result["focus_text"]!="" && is_fulls("token"))
				{
					$rs=$this->get_users(trim($_REQUEST["token"]),"id");
					$arr=json_decode($result["focus_text"],true);
					//print_r($arr);exit();
					if(in_array($rs["id"],$arr))
					{
						$result["focus_state"]=1;
					}
				}
				if($result["birthday"]=="")
				{
					$result["birthday"]="未知";		
				}
				else
				{
					$arr=explode("-",$result["birthday"]);
					$result["birthday"]=date("Y")-trim($arr[0]);
				}
				require FCPATH."/config/img.inc.php";
				if(trim($result["avatar"])=="")
				{
					$result["avatar"]=$img_inc["avatar"];
				}
				if(trim($result["bg"])=="")
				{
					$result["bg"]=$img_inc["bg"];
				}
				
				//计算今日可预约时间
				$queyrp=$this->db->query("select `frees` from `dg_tearch_plan_list` where `date`='".date("Y-m")."' and `tid_index`='".right_index($id)."' and `tid`='$id' and `frees`!='' limit 1");
				
				
				if($queyrp->num_rows()>0)
				{
					$resultp=$queyrp->row_array();
					$result["free_time"]=$resultp["frees"];	
				}
				else
				{
					$result["free_time"]="";	
				}
				
				//计算对应的课程信息
				$arr=json_decode($result["money_desc"],true);
				unset($result["money_desc"]);
				unset($result["focus_text"]);
				
				if($arr["money_peak"]=="" || $arr["money_slack"]=="")
				{
					json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰服务价格，无法直接读取！","");		
				}
				else
				{
					$result["money_peak"]=$arr["money_peak"];
					$result["money_slack"]=$arr["money_slack"];
				}
				
				$query1=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='1' order by `min` asc");
				
				if($query1->num_rows()>0)
				{
					$result["money_desc"]["peak"]=$query1->result_array();
				}
				else
				{
					json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
				}
				
				$query2=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='2' order by `min` asc");
				if($query2->num_rows()>0)
				{
					$result["money_desc"]["slack"]=$query2->result_array();
				}
				else
				{
					json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
				}
				return $result;	
			}	
			else
			{
				error_show();	
			}					
		}
		
		//获取对应的教练详细信息
		public function item($id)
		{
			$result=$this->get_teacher_msg($id);
			json_array2("10000","成功",$result);
		}
		
		//获取预约详情接口
		public function items($id)
		{
			$result=$this->get_teacher_msg($id);
			$days=date("Y-m-d");
			if(isset($_REQUEST["days"]) && substr_count(trim($_REQUEST["days"]),"-")==2)
			{
				$days=trim($_REQUEST["days"]);	
			}
			$arr=explode("-",$days);
			$month=$arr[0]."-".$arr[1];
			/*$query=$this->db->query("SELECT `desc` FROM `dg_tearch_plan` WHERE `tid_index` = '".right_index($id)."' AND `tid` = '$id' AND (`date` = '$month')  LIMIT 1");
			if($query->num_rows()>0)
			{
				$rs=$query->row_array();
				$arrs=json_decode($rs["desc"],true);
				//print_r($arrs);
				$k=intval($arr[2])-1;
				if(isset($arrs[$k]) && !empty($arrs[$k])!="")
				{
					$result["class"]=$arrs[$k]["class"];	
				}
				else
				{
					$result["class"]=$this->create_days();	
				}
			}
			else
			{
				$result["class"]=$this->create_days();
			}*/
			//$result["class"]=array();
			$arr=array();
			for($i=0;$i<=14;$i++){
				$i1=$i+8;
				$z=$i+7;
				if($z<10)
				{
					$z="0".$z;	
				}
				if($i1<10)
				{
					$i1="0".$i1;	
				}
				$arr[$i]=array("time"=>$z.":00-".$i1.":00");
				$query_list=$this->db->query("select `state` from `dg_tearch_plan_list` where `tid_index`='".right_index($id)."' and `tid`='$id' and `date`='$days' and `node`='".$arr[$i]["time"]."' limit 1");
				if($query_list->num_rows()>0)
				{
					$result_list=$query_list->row_array();
					$arr[$i]["state"]=$result_list["state"];
				}
				else
				{
					$arr[$i]["state"]=1;
				}
			}
			$result["class"]=$arr;
			//开始计算售价
			for($a=0;$a<count($result["class"]);$a++)
			{
				$arrs=explode("-",$result["class"][$a]["time"]);
				$arrs1=explode(":",$arrs[0]);
				$arrs2=explode(":",$arrs[1]);	
				
				$s=intval($arrs1[0]);
				$e=intval($arrs2[0]);
				
				$result["class"][$a]["sale"]=$result["money_slack"];
				
				for($b=0;$b<count($result["money_desc"]["peak"]);$b++)
				{
					$a1=$result["money_desc"]["peak"][$b];
					if($a1["min"]<$a1["max"])
					{
						if($s>=$a1["min"] && $s<=$a1["max"] && $e<=$a1["max"] && $e>=$a1["min"])
						{
							$result["class"][$a]["sale"]=$result["money_peak"];	
						}		
					}
					else
					{
						if($e<=$a1["max"])
						{
							$result["class"][$a]["sale"]=$result["money_peak"];	
						}	
					}
				}
				
			}
			json_array2("10000","成功",$result);
			
		}
		
		//拼接默认值课程表信息
		private function create_days()
		{
			$arr=array();
			for($i=0;$i<=12;$i++){
				$i1=$i+10;
				$z=$i+9;
				if($z<10)
				{
					$z="0".$z;	
				}
				if($i1<10)
				{
					$i1="0".$i1;	
				}
				$arr[$i]=array("time"=>$z.":00-".$i1.":00","state"=>1);
			}
			return $arr;	
		}
		
		//拼接当日课程表信息
		private function read_days($days,$arrays)
		{
			if(isset($arrays[0]["date"]) && trim($arrays[0]["date"])!="" && substr_count($days,trim($arrays[0]["date"]))>0)
			{
				//开始读取第一个数据
				$arrs=json_decode($arrays[0]["desc"],true);
				//print_r($arrs);
				$arr=explode("-",$days);
				$k=intval($arr[2])-1;
				if(isset($arrs[$k]) && !empty($arrs[$k]))
				{
					return $arrs[$k]["class"];
				}
				else
				{
					return $this->create_days();
				}
			}
			elseif(isset($arrays[1]["date"]) && trim($arrays[1]["date"])!="" && substr_count($days,trim($arrays[1]["date"]))>0)
			{
				//开始读取第二个数据
				$arrs=json_decode($arrays[1]["desc"],true);
				//print_r($arrs);
				$arr=explode("-",$days);
				$k=intval($arr[2]);
				if(isset($arrs[$k]) && !empty($arrs[$k]))
				{
					return $arrs[$k]["class"];
				}
				else
				{
					return $this->create_days();
				}					
			}
			return $this->create_days();
		}
		
		//获取今日及未来一周课程
		public function classs($id)
		{
			$query=$this->db->query("select `id` from `dg_teacher` where `id`='$id' and `act`='1' limit 1");
			if($query->num_rows()>0)
			{
				$array=array();
				for($i=0;$i<7;$i++)
				{
					$date=date("Y-m-d",time()+3600*24*$i);
					$array[$i]["day"]=$date;
					$array[$i]["lists"]=array();
					for($a=0;$a<15;$a++)
					{
						$a_1=$a+7;
						if($a_1<10)
						{
							$a_1="0".$a_1;		
						}
						$a_2=$a+8;
						if($a_2<10)
						{
						    $a_2="0".$a_2;
						}						
						$array[$i]["lists"][$a]["time"]=$a_1.":00-".$a_2.":00";
						$array[$i]["lists"][$a]["today"]=$date;
						$querys=$this->db->query("select `state` from `dg_tearch_plan_list` where `tid_index`='".right_index($id)."' and `tid`='$id' and `date`='$date' and `node`='".$array[$i]["lists"][$a]["time"]."' limit 1");
						if($querys->num_rows()>0)
						{
							$results=$querys->row_array();
							$array[$i]["lists"][$a]["state"]=$results["state"];	
						}
						else
						{
							$array[$i]["lists"][$a]["state"]=1;	
						}
						
					}
				}
				json_array2("10000","成功",$array);
			}
			else
			{
				error_show();	
			}
			
			/*$query=$this->db->query("select `id` from `dg_teacher` where `id`='$id' and `act`='1' limit 1");
			if($query->num_rows()>0)
			{
				$week[0]["time"]=date("Y-m-d");
				$weeks[0]=date("Y-m");
				for($i=1;$i<=6;$i++)
				{
					$time=time()+(3600*24)*$i;
					$week[$i]["time"]=date("Y-m-d",$time);
					if(date("Y-m",$time)!=$weeks[0])
					{
						//不同等于今天这个月，另外开辟一个变量保存下个月数据
						$weeks[1]=date("Y-m",$time);	
					}
				}
				//print_r($weeks);
				if(isset($weeks[1]) && $weeks[1]!=""){
					$query=$this->db->query("SELECT `desc`,`date` FROM `dg_tearch_plan` WHERE `tid_index` = '".right_index($id)."' AND `tid` = '$id' AND (`date` = '".$weeks[0]."' or `date` = '".$weeks[1]."')  LIMIT 2 ");
				}else{
					$query=$this->db->query("SELECT `desc`,`date` FROM `dg_tearch_plan` WHERE `tid_index` = '".right_index($id)."' AND `tid` = '$id' AND (`date` = '".$weeks[0]."')  LIMIT 1");	
				}
				$arrays=$query->result_array();
				//print_r($week);
				for($i=0;$i<count($week);$i++)
				{
					$week[$i]["day"]=$week[$i]["time"];
					unset($week[$i]["time"]);
					$days=$week[$i]["day"];
					$week[$i]["lists"]=$this->read_days($days,$arrays);
				}
				json_array2("10000","成功",$week);
			}
			else
			{
				error_show();	
			}*/
		}
		
		//购买课程
		public function buy($id,$days,$times,$rs)
		{
			if($rs["nickname"]=="")
			{
				json_array2("30000","抱歉：请您先完善自己的会员信息后再来预约购买课程","info");		
			}
						
			$this->db->trans_strict(false);
			$this->db->trans_begin();
			
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='1' limit 1");
			if($query->num_rows()>0)
			{
				//获取对应的教练信息
				$result=$query->row_array();
				
				//if(substr_count($times,",")>0)
				//{
					//多个课程提交模式
					$arr=explode(",",$times);
					//print_r($arr);die();
					foreach($arr as $k=>$v)
					{
						if(trim($v)!="" && substr_count($v,"-")==1)
						{
							//符合比对条件，可以购买
							$querys=$this->db->query("SELECT * FROM `dg_tearch_plan_list` WHERE `tid_index` = '".right_index($id)."' AND `tid` = '$id' AND (`date` = '$days') and `node`='".trim($v)."'  LIMIT 1");	
							if($querys->num_rows()>0)
							{
								$results=$querys->row_array();
								if($results["state"]!=2)
								{
									$this->db->trans_rollback();
									json_array2("30000","抱歉：您预约的时间段中包含已被预约时间，请您稍后再试!","");			
								}
								else
								{
									$start_time=$results["start_time"]-3600*4;
									if($results["start_time"]<=time())
									{
										$this->db->trans_rollback();
										json_array2("30000","抱歉：您预约的时间段中包含不可预约时间，请您稍后再试!","");			
									}
									elseif($start_time<=time())
									{
										$this->db->trans_rollback();
										json_array2("30000","抱歉：开课前四小时内不允许预约，请您稍后再试!","");	
									}
									else
									{
										$qy=$this->db->query("select `balance` from `dg_user` where `id`='".$rs["id"]."'");
										if($qy->num_rows()<=0)
										{
											$this->db->trans_rollback();
											json_array2("30000","抱歉：网络连接失败，请您稍后再试!","");		
										}
										$res=$qy->row_array();
										if($results["money"]>$res["balance"])
										{
											$this->db->trans_rollback();
											json_array2("30000","抱歉：您的账户余额不足，请您及时充值!","");	
										}
										else
										{
											//开始扣费处理
											//更新账户余额
											$this->db->query("update `dg_user` set `balance`=`balance`-'".$results["money"]."' where `id`='".$rs["id"]."'");
											$money=$results["money"];
											//开始扣除余额--节点数据
											$query100=$this->db->query("select `id`,`money`,`money_remaining` from `dg_pay_order` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `money_remaining`>0 order by `id` asc");
											$sxy=array();
											$w=0;
											foreach($query100->result_array() as $array)
											{
												if($money>0)//如果累减金额大于0的时候操作
												{
													if($array["money_remaining"]>$money)
													{
														//直接扣除	
														$this->db->query("update `dg_pay_order` set `money_remaining`=`money_remaining`-'$money' where `id`='".$array["id"]."'");
														
														$sxy[$w]["money"]=$money;
														
														$sxy[$w]["id"]=$array["id"];
														
														$money=0;
													}
													else
													{
														//累计扣除
														$this->db->query("update `dg_pay_order` set `money_remaining`='0' where `id`='".$array["id"]."'");
														
														$money=$money-$array["money_remaining"];
														
														$sxy[$w]["money"]=$array["money_remaining"];
														
														$sxy[$w]["id"]=$array["id"];
														
													}
													$w++;
												}
											}						
											//开始扣除余额--节点数据
											
											//生成对应订单信息
											$sql1="INSERT INTO `dg_orders` (`act`, `order_id`, `money`, `uid`, `uid_index` , `tid`, `class_id`, `time`, `state`,`returns`,`class_time`) values ('1', '".date("YmdHis").substr(microtime(),2,8)."', '".$results["money"]."', '".$rs["id"]."', '".right_index($rs["id"])."' , '$id', '".$results["id"]."', '".time()."', '1','".json_encode($sxy)."','".date("Y-m-d H:i",$results["start_time"]).":00"."')";
											$this->db->query($sql1);
											
											//更新对应的课程预约状态
											$sql="update `dg_tearch_plan_list` set `state`='3' where `date`='$days' and `node`='".trim($v)."' and `tid_index`='".right_index($id)."' and `tid`='$id' limit 1";	
											$this->db->query($sql);	
											
										}	
									}
								}
							}
							else
							{
								$this->db->trans_rollback();
								json_array2("30000","抱歉：网络连接失败，请您稍后再试!","");	
							}
						}	
					}
					//回执语句编写
					if($this->db->trans_status()==true){
						$this->db->trans_commit();
						
						//判断账户余额，推送对应的充值信息
						$m_query=$this->db->query("select `push_key`,`balance`,`nickname`,`mobile` from `dg_user` where `id`='".$rs["id"]."'");
						if($m_query->num_rows()>0)
						{
							$m_result=$m_query->row_array();
							
							if($m_result["balance"]<100 && $m_result["push_key"]!='')
							{
								$nickname="";
								if($m_result["nickname"]!="")
								{
									$nickname=$m_result["nickname"];	
								}
								else
								{
									$nickname=substr($m_result["mobile"],0,3)."****".substr($m_result["mobile"],7,4);	
								}
								require FCPATH."config/push.inc.php";
								
								$msg=str_replace("{name}",$nickname,$push_inc["money_message"]);
								
								$_arrays=array("message"=>$msg,"type"=>1,"id"=>"","push_id"=>$m_result["push_key"],"title"=>$push_inc["money_title"]);
								
								c_push($_arrays);
								
							}	
						}						
						
						
						json_array2("10000","成功","");
					}else{
						$this->db->trans_rollback();
						error_show();
					}
				//}
				//else
				//{
					//$this->db->trans_rollback();
					//json_array2("30000","抱歉：网络连接失败，请您稍后再试!","");		
				//}
			}
			else
			{
				$this->db->trans_rollback();
				json_array2("30000","抱歉：没有找到对应的教练信息!","");
			}
		}
		
		//购买算法
		private function buy_it($money,$id,$days,$times,$rs,$jinqian)
		{	

						
			if($rs["balance"]<$money)
			{
				json_array2("30000","抱歉：您的账户余额不足，请您及时充值!","");		
			}	
			else
			{
				//开启事务回滚处理
				
				//echo $times."_______".$days;die();
				
				//print_r($jinqian);die();
				
				$money_user=$money;
				
				//echo $money_user;die();
				
				//更新账户余额
				$this->db->query("update `dg_user` set `balance`=`balance`-'$money_user' where `id`='".$rs["id"]."'");
				//更新私教计划
				
				$arr=explode("-",$days);
				$month=$arr[0]."-".$arr[1];
							
				$query=$this->db->query("SELECT `id`,`desc` FROM `dg_tearch_plan` WHERE `tid_index` = '".right_index($id)."' AND `tid` = '$id' AND (`date` = '$month')  LIMIT 1");
				
				if($query->num_rows()>0)
				{
					$t_arr=explode(",",trim($times,","));
					
					//print_r($t_arr);die();
					
					//print_r($jinqian);die();
					
					for($z=0;$z<count($jinqian);$z++){
					
						$v=$jinqian[$z]["times"];
						
						//echo $jinqian[$z]["money"];die();
						
					//foreach($t_arr as $k=>$v){
						$money=$jinqian[$z]["money"];
						
						$sxy=array();
						$w=0;						
						
						//开始扣除余额--节点数据
						$query100=$this->db->query("select `id`,`money`,`money_remaining` from `dg_pay_order` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `money_remaining`>0 order by `id` asc");
						foreach($query100->result_array() as $array)
						{
							if($money>0)//如果累减金额大于0的时候操作
							{
								if($array["money_remaining"]>$money)
								{
									//直接扣除	
									$this->db->query("update `dg_pay_order` set `money_remaining`=`money_remaining`-'$money' where `id`='".$array["id"]."'");
									
									$sxy[$w]["money"]=$money;
									
									$sxy[$w]["id"]=$array["id"];
									
									$money=0;
								}
								else
								{
									//累计扣除
									$this->db->query("update `dg_pay_order` set `money_remaining`='0' where `id`='".$array["id"]."'");
									
									$money=$money-$array["money_remaining"];
									
									$sxy[$w]["money"]=$array["money_remaining"];
									
									$sxy[$w]["id"]=$array["id"];
									
								}
							}
						}						
						//开始扣除余额--节点数据
						$querys=$this->db->query("select `id`,`state` from `dg_tearch_plan_list` where `date`='$days' and `node`='".trim($v)."' and `tid_index`='".right_index($id)."' and `tid`='$id' limit 1");
						
						if($querys->num_rows()<=0)
						{
							$this->db->trans_rollback();
							json_array2("30000","抱歉：当前系统忙，请您稍后再试!","");									
						}
						
						$result1s=$querys->row_array();
						//print_r($result1s);
						//die();
						if($result1s["state"]!=2)
						{
							$this->db->trans_rollback();
							json_array2("30000","抱歉：您购买选择时间段已经过期，请您稍后再试!","");	
						}
						
						$class_id=$result1s["id"];
						
						$sql1="INSERT INTO `dg_orders` (`act`, `order_id`, `money`, `uid`, `uid_index` , `tid`, `class_id`, `time`, `state`,`returns`) values ('1', '".date("YmdHis").substr(microtime(),2,8)."', '".$jinqian[$z]["money"]."', '".$rs["id"]."', '".right_index($rs["id"])."' , '$id', '$class_id', '".time()."', '2','".json_encode($sxy)."');";
						
						$this->db->query($sql1);
						
						$sql="update `dg_tearch_plan_list` set `state`='3' where `date`='$days' and `node`='".trim($v)."' and `tid_index`='".right_index($id)."' and `tid`='$id' limit 1";
						
						$this->db->query($sql);
					
					//}
					}
					//exit();
					$result=$query->row_array();
					$arrs=array();
					$a=0;	
					$arrays=json_decode($result["desc"],true);
					//print_r($arrays);die();
					for($i=0;$i<count($arrays);$i++)
					{
						//print_r($arrays[$i]["class"]);	
						$arrs[$a]=$arrays[$i];
						
						//print_r($arrs[$a]);
						
						for($b=0;$b<count($arrs[$a]["class"]);$b++)
						{
							//echo ;die();
							//print_r($arrs[$a]["class"][$b]);
							//die();
							//print_r($arrs[$a][$b]);
							//echo $arrs[$a]["class"][$b]["time"]."<br>";die();
							if(in_array(trim($arrs[$a]["class"][$b]["time"]),$t_arr) && $i==trim($arr[2]))
							{
								//print_r($arrays[$i]);
								//找到对应时间，更改其中的时间段
								//echo trim($arrs[$a]["class"][$b]["time"]);
								$arrs[$a]["class"][$b]["state"]=3;	
							}								
						}
						
						$a++;
					}
					$text=json_encode($arrs);
					$this->db->query("update `dg_tearch_plan` set `desc`='$text' where `id`='".$result["id"]."'");
				}
				else
				{
					$this->db->trans_rollback();
					json_array2("30000","抱歉：您购买的预约时间已经失效，请您稍后再试!","");				
				}
				if($this->db->trans_status()==true){
					//$this->db->trans_commit();
					$this->db->trans_commit();
					json_array2("10000","成功","");
				}else{
					$this->db->trans_rollback();
					error_show();
				}										
				//结束事务回滚处理
			}
		}

		//关注教练
		public function focus($id,$rs)
		{
			$query=$this->db->query("select `focus`,`focus_text` from `dg_teacher` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$f=1;
				$arrs=array();
				if(trim($result["focus_text"])!="")
				{
					$arrs=json_decode($result["focus_text"],true);
					//print_r($arrs);
					if(in_array($rs["id"],$arrs))
					{
						$f=2;	
					}
				}
				//$arr=explode(",",trim($result["focus"],","));
				//print_r($arr);
				if($f==1)
				{
					$vs=$rs["id"];
					$arrs[$vs]=$rs["id"];
					$_array=array("focus_text"=>json_encode($arrs),"focus"=>$result["focus"]+1);
					$text="关注成功";	
				}
				else
				{
					$vs=$rs["id"];
					//echo array_search($rs["id"],$arr);die();
					unset($arrs[$vs]);
					$_array=array("focus_text"=>json_encode($arrs),"focus"=>$result["focus"]-1);
					$text="取消关注成功";
				}
				$this->db->update("teacher",$_array,array("id"=>$id));
				json_array2("10000",$text,"success");	
			}
			else
			{
				error_show();	
			}	
		}
		
		//我追踪的教练信息
		public function collect($rs)
		{
			$query1=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='1' order by `min` asc");
			if($query1->num_rows()>0)
			{
				$time_peak=$query1->result_array();
			}
			else
			{
				json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
			}
			$query2=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='2' order by `min` asc");
			if($query2->num_rows()>0)
			{
				$time_slack=$query2->result_array();
			}
			else
			{
				json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
			}
						
			$likes='"'.$rs["id"].'":'.$rs["id"].'';
			$likes_1='"'.$rs["id"].'":"'.$rs["id"].'"';
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;
			$sql="select `id`,`realname`,`avatar`,`birthday`,`level`,`score`,`desc`,`money_desc`,`act` from `dg_teacher` where `focus_text` like '%".$likes."%' or `focus_text` like '%".$likes_1."%'  order by `score` desc";	
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			//echo $sql;
			$array=array();$i=0;
			$query=$this->db->query($sql);
			foreach($query->result_array() as $arrays)
			{
				$array[$i]=$arrays;
				//开始计算生日
				$arrs=explode("-",$array[$i]["birthday"]);
				$array[$i]["birthday"]=date("Y")-$arrs[0];
				//开始计算教授课程
				$array[$i]["classs"]=array();
				$array[$i]["class_name"]="";
				if($array[$i]["act"]==2)
				{
					$arrs=json_decode($array[$i]["money_desc"],true);
					$array[$i]["classs"]=$this->create_new_class($arrs,$class_name);
					$array[$i]["class_name"]=$class_name;
				}
				
				//合并私教的高低峰时间
				$array[$i]["money_peak"]="";
				$array[$i]["money_slack"]="";
				$array[$i]["time_peak"]=array();
				$array[$i]["time_slack"]=array();
				$array[$i]["time_free"]=array();
				if($array[$i]["act"]==1)
				{
					$arrs=json_decode($array[$i]["money_desc"],true);	
					if(is_array($arrs) && !empty($arrs))
					{
						$array[$i]["money_peak"]=$arrs["money_peak"];
						$array[$i]["money_slack"]=$arrs["money_slack"];	
						$array[$i]["time_peak"]=$time_peak;
						$array[$i]["time_slack"]=$time_slack;	
						$qy=$this->db->query("select `frees` from `dg_tearch_plan_list` where `date`='".date("Y-m-d")."' and `tid_index`='".right_index($array[$i]["id"])."' and `tid`='".$array[$i]["id"]."' and `frees`!='' order by `id` asc limit 1");
						$array[$i]["time_free"][0]["node"]="";
						if($qy->num_rows()>0)
						{
							$res=$qy->row_array();
							$array[$i]["time_free"][0]["node"]=$res["frees"];
						}
						//$array[$i]["time_free"]=$qy->result_array();	
					}
					else
					{
						json_array2("30000","抱歉：读取教练服务时间失败，请您稍后再试","");	
					}
				}
				
				unset($array[$i]["money_desc"]);
				
				$i++;
			}
			json_array2("10000","成功",$array);
		}
		
		//组合新的课程
		private function create_new_class($arrs,&$class_name)
		{
			$class_name="";
			if(is_array($arrs) && !empty($arrs))
			{
				for($i=0;$i<count($arrs);$i++)
				{
					$query=$this->db->query("select `name` from `dg_class` where `id`='".$arrs[$i]["class"]."'");
					if($query->num_rows()<=0)
					{
						json_array2("30000","抱歉：读取教练服务时间失败，请您稍后再试","");	
					}
					else
					{
						$result=$query->row_array();
						$arrs[$i]["class_name"]=$result["name"];
						$class_name.=" ".$result["name"];
					}
				}
				$class_name=trim($class_name);
				return $arrs;
			}
			json_array2("30000","抱歉：读取教练服务时间失败，请您稍后再试","");
		}
		
		//查看某个操课教练所教授的课程
		public function hisclass($id)
		{
			$query=$this->db->query("select `id`,`money_desc` from `dg_teacher` where `id`='$id' and `act`='2' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$query1=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='3' and `model`='1' order by `min` asc");
				if($query1->num_rows()>0)
				{
					$time_peak=$query1->result_array();
				}
				else
				{
					json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
				}
				$query2=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='3' and `model`='2' order by `min` asc");
				if($query2->num_rows()>0)
				{
					$time_slack=$query2->result_array();
				}
				else
				{
					json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
				}
				$arrs=json_decode($result["money_desc"],true);
				$result["class"]=$this->create_new_class($arrs,$class_name);
				$result["time_peak"]=$time_peak;
				$result["time_slack"]=$time_slack;
				unset($result["money_desc"]);
				//print_r($result);		
				json_array2("10000","成功",$result);			
			}
			else
			{
				error_show();			
			}
		}
	}