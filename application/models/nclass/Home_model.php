<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Home_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//开始算价格
		private function buy_loads($result,$rs)
		{
			$this->db->trans_strict(false);
			$this->db->trans_begin();	
					
			$query=$this->db->query("select `id` from `dg_orders` where `act`='2' and `pid`='".$result["id"]."' and `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `state`='2' limit 1");
			
			if($query->num_rows()>0)
			{
				json_array2("30000","抱歉：您已经预约购买本次课程","");	
			}
			else
			{
				if($result["sale"]>$rs["balance"])
				{
					json_array2("30000","抱歉：您的账户余额不足，不能购买，请您先充值","");		
				}
				else
				{

					
					$money=$result["sale"];
					
					//减去账户金额
					$this->db->query("update `dg_user` set `balance`=`balance`-'".$result["sale"]."' where `id`='".$rs["id"]."'");
					
					//开始计算每个节点的金额数量，做累减处理
					$arrs=array();
					$z=0;
					
					$query_lo=$this->db->query("select `id`,`money`,`money_remaining` from `dg_pay_order` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `money_remaining`>0 order by `id` asc");
					foreach($query_lo->result_array() as $array_lo)
					{
						if($money>0)//如果累减金额大于0的时候操作
						{
							if($array_lo["money_remaining"]>$money)
							{
								//直接扣除	
								$this->db->query("update `dg_pay_order` set `money_remaining`=`money_remaining`-'$money' where `id`='".$array_lo["id"]."'");
								
								$arrs[$z]["money"]=$money;
								
								$arrs[$z]["id"]=$array_lo["id"];
								
								$money=0;
							}
							else
							{
								//累计扣除
								$this->db->query("update `dg_pay_order` set `money_remaining`='0' where `id`='".$array_lo["id"]."'");
								
								$money=$money-$array_lo["money_remaining"];
								
								$arrs[$z]["money"]=$array_lo["money_remaining"];
								
								$arrs[$z]["id"]=$array_lo["id"];
								
							}
							$z++;
						}
					}
					
					//添加订单信息
					$_array=array(
						"act"=>2,
						"order_id"=>date("YmdHis").substr(microtime(),2,8),
						"money"=>$result["sale"],
						"uid"=>$rs["id"],
						"uid_index"=>right_index($rs["id"]),
						"tid"=>$result["tid"],
						"pid"=>$result["id"],
						"class_id"=>$result["class_id"],
						"class_time"=>date("Y-m-d H:i",$result["start_time"]).":00",
						"time"=>time(),
						"state"=>1,
						"returns"=>json_encode($arrs),//拼合退款信息
					);
					$this->db->insert("orders",$_array);
					
					//开始处理对应的课程信息
					$this->db->query("update `dg_tearch_plans` set `loads`=`loads`+'1' where `id`='".$result["id"]."'");
					
					$qys=$this->db->query("select `min`,`max`,`loads`,`state` from `dg_tearch_plans` where `id`='".$result["id"]."'");	
					$res=$qys->row_array();
					//达到开课人数了，并且状态值还没有更改
					if($res["loads"]>=$res["min"] && $res["state"]==1)
					{
						
						//更新每个对应课程的订单信息为已成立
						$this->db->query("update `dg_orders` set `state`='2' where `act`='2' and `pid`='".$result["id"]."'");
						
						$this->db->query("update `dg_tearch_plans` set `state`='2' where `id`='".$result["id"]."'");//第一种情况，达到了对应的开课人数了，直接更新已成立状态
						
						//解散对应点的其他课程
						$querys=$this->db->query("select `id` from `dg_tearch_plans` where `date`='".$result["date"]."' and  `node`='".$result["node"]."' and  `room_id`='".$result["room_id"]."' and `id`!='".$result["id"]."'");
						foreach($querys->result_array() as $arrays)
						{
							$this->db->query("update `dg_tearch_plans` set `state`='4' where `id`='".$arrays["id"]."'");//取消每个课程
							//查询每个对应订单，然后取消订单状态
							$querys_list=$this->db->query("select `id`,`money`,`returns`,`uid` from `dg_orders` where `class_id`='".$arrays["id"]."' and `act`='2'");
							
							foreach($querys_list->result_array() as $arrays_list)
							{
								//更新每个正常状态的订单，并且返还现金
								$this->db->query("update `dg_orders` set `state`='4' where `state`='2' and `id`='".$arrays_list["id"]."'");
								//返还余额现金?????
								$arrers=json_decode($arrays_list["returns"],true);
								if(is_array($arrers) && !empty($arrers))
								{
									//开始累计还原金额到用户账户中
									for($d=0;$d<count($arrers);$d++)
									{
										$this->db->query("update `dg_pay_order` set `money_remaining`=`money_remaining`+'".$arrers[$d]["money"]."' where `id`='".$arrers[$d]["id"]."'");//还原对应的金额到节点中
									}
								}
								$this->db->query("update `dg_user` set `balance`=`balance`+'".$arrays_list["money"]."' where `id`='".$arrays_list["uid"]."'");//还原对应金额到每个用户的余额里面
							}
						}
						
						//开始推送退款信息开始
						
						//开始推送退款信息结束					
						
					}
					//人数满了，并且状态值还是2
					elseif($res["loads"]>=$res["max"] && $res["state"]==2)
					{
						$this->db->query("update `dg_tearch_plans` set `state`='3' where `id`='".$result["id"]."'");//第二种情况，人数满了，直接更新人满状态
					}	
					
					if($this->db->trans_status()==true){
						//$this->db->trans_commit();
						$this->db->trans_commit();
						json_array2("10000","成功","");
					}else{
						$this->db->trans_rollback();
						error_show();
					}					

				}
				//state:1.等待中2成立3.完成4.系统取消退款5.自行退款
				//$this->db->trans_rollback();
			}
		}
		
		//购买课程
		public function buys($id,$rs)
		{
			if($rs["nickname"]=="")
			{
				json_array2("30000","抱歉：请您先完善自己的会员信息后再来预约购买课程","info");		
			}
			$this->db->trans_strict(false);
			$this->db->trans_begin();
			$qy=$this->db->query("select * from `dg_user` where `id`='".$rs["id"]."'");
			if($qy->num_rows()<=0)
			{
				$this->db->trans_rollback();
				json_array2("30000","抱歉：会员登录信息读取失败，请您稍后再试","");					
			}	
			$rs=$qy->row_array();
			$query=$this->db->query("select * from `dg_tearch_plans` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["state"]==0)
				{
					$this->db->trans_rollback();
					json_array2("30000","抱歉：服务器响应超时，请您稍后再试","");	
				}
				elseif($result["state"]==1 || $result["state"]==2)
				{
					if($result["loads"]>=$result["max"])
					{
						$this->db->trans_rollback();
						json_array2("30000","抱歉：当前报名人数已经满额，请您选择其他课程预约","");	
					}
					else
					{
						if($result["start_time"]<time())
						{
							$this->db->trans_rollback();
							json_array2("30000","抱歉：当前课程已经过期失效，请您选择其他课程预约","");		
						}
						else
						{
							//$this->buy_loads($result,$rs);
							//可以购买了
							$query=$this->db->query("select `id` from `dg_orders` where `act`='2' and `pid`='".$result["id"]."' and `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and (`state`='2' or `state`='1' or `state`='3') limit 1");
							
							//$f=fopen(FCPATH."aaa.php","a");
							//fwrite($f,"select `id` from `dg_orders` where `act`='2' and `pid`='".$result["id"]."' and `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and (`state`='2' or `state`='1') limit 1");
							
							if($query->num_rows()>0)
							{
								$this->db->trans_rollback();
								json_array2("30000","抱歉：您已经预约购买本次课程","");	
							}
							else
							{
								if($result["sale"]>$rs["balance"])
								{
									$this->db->trans_rollback();
									json_array2("30000","抱歉：您的账户余额不足，请您先充值","");		
								}
								else
								{
									$money=$result["sale"];
									//减去账户金额
									$this->db->query("update `dg_user` set `balance`=`balance`-'".$result["sale"]."' where `id`='".$rs["id"]."'");	
									//开始计算每个节点的金额数量，做累减处理
									$arrs=array();
									$z=0;
									
									$query_lo=$this->db->query("select `id`,`money`,`money_remaining` from `dg_pay_order` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `money_remaining`>0 order by `id` asc");
									foreach($query_lo->result_array() as $array_lo)
									{
										if($money>0)//如果累减金额大于0的时候操作
										{
											if($array_lo["money_remaining"]>$money)
											{
												//直接扣除	
												$this->db->query("update `dg_pay_order` set `money_remaining`=`money_remaining`-'$money' where `id`='".$array_lo["id"]."'");
												
												$arrs[$z]["money"]=$money;
												
												$arrs[$z]["id"]=$array_lo["id"];
												
												$money=0;
											}
											else
											{
												//累计扣除
												$this->db->query("update `dg_pay_order` set `money_remaining`='0' where `id`='".$array_lo["id"]."'");
												
												$money=$money-$array_lo["money_remaining"];
												
												$arrs[$z]["money"]=$array_lo["money_remaining"];
												
												$arrs[$z]["id"]=$array_lo["id"];
												
											}
											$z++;
										}
									}
									
									//添加订单信息
									$_array=array(
										"act"=>2,
										"order_id"=>date("YmdHis").substr(microtime(),2,8),
										"money"=>$result["sale"],
										"uid"=>$rs["id"],
										"uid_index"=>right_index($rs["id"]),
										"tid"=>$result["tid"],
										"pid"=>$result["id"],
										"class_id"=>$result["class_id"],
										"class_time"=>date("Y-m-d H:i",$result["start_time"]).":00",
										"time"=>time(),
										"state"=>1,
										"returns"=>json_encode($arrs),//拼合退款信息
									);
									$this->db->insert("orders",$_array);
									
									//开始处理对应的课程信息
									$this->db->query("update `dg_tearch_plans` set `loads`=`loads`+'1' where `id`='".$result["id"]."'");
									
									
									
									$qys=$this->db->query("select `min`,`max`,`loads`,`state` from `dg_tearch_plans` where `id`='".$result["id"]."'");	
									$res=$qys->row_array();
									
									$cg_t_arr=array();
									$cg_x_arr=array();$a=0;
									
									//达到开课人数了，并且状态值还没有更改
									if($res["loads"]>=$res["min"] && $res["state"]==1)
									{
										
										//更新每个对应课程的订单信息为已成立
									    $sql_tmp = "select id  from dg_orders where state = 1 and act = 2 and pid = {$result['id']}";
									    $list = $this->db->query($sql_tmp)->result_array();
									    foreach ($list as $update_id) {
									        $this->db->query("update `dg_orders` set `state`='2' where `id`={$update_id['id']}");
									    }
										
										$this->db->query("update `dg_tearch_plans` set `state`='2' where `id`='".$result["id"]."'");//第一种情况，达到了对应的开课人数了，直接更新已成立状态
										
										//准备推送给对应的教练和学生
										
										
										/**查询对应的教练信息**/
										$ts=$this->db->query("select `push_key` from `dg_teacher` where `id`='".$result["tid"]."'");
										
										if($ts->num_rows()>0)
										{
											$tss=$ts->row_array();
											$cg_t_arr["push_key"]=$tss["push_key"];
											$cg_t_arr["date"]=$result["date"];
											$cg_t_arr["start"]=date("H:i",$result["start_time"]);
											$cg_t_arr["class"]=$result["class_name"];
											$cg_t_arr["end"]=date("H:i",$result["end_time"]);
											$cg_t_arr["room"]=$result["room_name"];
										}
										
										/**查询对应的学生**/
										$xs=$this->db->query("select `u`.`push_key` from `dg_orders` as `o` left join `dg_user` as `u` on `o`.`uid`=`u`.`id` where `o`.`pid`='$id'");
										foreach($xs->result_array() as $xss)
										{
											$cg_x_arr["push_key"].=",".$xss["push_key"];
											$cg_x_arr["date"]=$result["date"];
											$cg_x_arr["start"]=date("H:i",$result["start_time"]);
											$cg_x_arr["end"]=date("H:i",$result["end_time"]);
											$cg_x_arr["room"]=$result["room_name"];
											$cg_x_arr["class"]=$result["class_name"];
											$cg_x_arr["teacher"]=$result["tid_name"];
											$a++;
										}
										//print_r($cg_x_arr);die;
										$qx_t_arr=array();$x=0;
										$qx_x_arr=array();$y=0;
										//解散对应点的其他课程
										$querys=$this->db->query("select `id`,`tid`,`room_name`,`class_name`,`tid_name`,`start_time`,`end_time` from `dg_tearch_plans` where `date`='".$result["date"]."' and  `node`='".$result["node"]."' and  `room_id`='".$result["room_id"]."' and `id`!='".$result["id"]."'");
										foreach($querys->result_array() as $arrays)
										{
											
											//查询被取消课程的老师信息
											$ts=$this->db->query("select `push_key` from `dg_teacher` where `id`='".$arrays["tid"]."'");
											
											if($ts->num_rows()>0)
											{
												$tss=$ts->row_array();
												$qx_t_arr[$x]["push_key"]=$tss["push_key"];
												$qx_t_arr[$x]["date"]=$arrays["date"];
												$qx_t_arr[$x]["start"]=date("H:i",$arrays["start_time"]);
												$qx_t_arr[$x]["class"]=$arrays["class_name"];
												$qx_t_arr[$x]["room"]=$arrays["room_name"];
												$qx_t_arr[$x]["end"]=date("H:i",$arrays["end_time"]);
												
											}
											$x++;
											
											//查询被取消的学生信息
											$xs=$this->db->query("select `u`.`push_key` from `dg_orders` as `o` left join `dg_user` as `u` on `o`.`uid`=`u`.`id` and `o`.`pid`='".$arrays["id"]."'");
											foreach($xs->result_array() as $xss)
											{
												
												$qx_x_arr[$y]["push_key"].=",".$xss["push_key"];
												$qx_x_arr[$y]["date"]=$arrays["date"];
												$qx_x_arr[$y]["start"]=date("H:i",$arrays["start_time"]);
												$qx_x_arr[$y]["end"]=date("H:i",$arrays["end_time"]);
												$qx_x_arr[$y]["room"]=$arrays["room_name"];
												$qx_x_arr[$y]["class"]=$arrays["class_name"];
												$qx_x_arr[$y]["teacher"]=$arrays["tid_name"];
											}	
																					
											$y++;	
											
											$this->db->query("update `dg_tearch_plans` set `state`='4' where `id`='".$arrays["id"]."'");//取消每个课程
											//查询每个对应订单，然后取消订单状态
											$querys_list=$this->db->query("select `id`,`money`,`returns`,`uid` from `dg_orders` where `class_id`='".$arrays["id"]."' and `act`='2'");
											
											foreach($querys_list->result_array() as $arrays_list)
											{
												//更新每个正常状态的订单，并且返还现金
												$this->db->query("update `dg_orders` set `state`='4' where `state`='2' and `id`='".$arrays_list["id"]."'");
												//返还余额现金?????
												$arrers=json_decode($arrays_list["returns"],true);
												if(is_array($arrers) && !empty($arrers))
												{
													//开始累计还原金额到用户账户中
													for($d=0;$d<count($arrers);$d++)
													{
														$this->db->query("update `dg_pay_order` set `money_remaining`=`money_remaining`+'".$arrers[$d]["money"]."' where `id`='".$arrers[$d]["id"]."'");//还原对应的金额到节点中
													}
												}
												$this->db->query("update `dg_user` set `balance`=`balance`+'".$arrays_list["money"]."' where `id`='".$arrays_list["uid"]."'");//还原对应金额到每个用户的余额里面
											}
										}
									
										//开始推送退款信息开始
										
										//开始推送退款信息结束					
										
									}
									//人数满了，并且状态值还是2
									elseif($res["loads"]>=$res["max"] && $res["state"]==2)
									{
										$this->db->query("update `dg_tearch_plans` set `state`='3' where `id`='".$result["id"]."'");//第二种情况，人数满了，直接更新人满状态
										
										//更新每个对应课程的订单信息为已成立
										$this->db->query("update `dg_orders` set `state`='2' where `act`='2' and `pid`='".$result["id"]."'");
									}
								
								
									if($this->db->trans_status()==true)
									{
										$this->db->trans_commit();
									    //$this->db->trans_rollback();
										require FCPATH."config/push.inc.php";
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
											
												$msg=str_replace("{name}",$nickname,$push_inc["money_message"]);
												
												$_arrays=array("message"=>$msg,"type"=>1,"id"=>"","push_id"=>$m_result["push_key"],"title"=>$push_inc["money_title"]);
												
												c_push($_arrays);
												
											}	
										}
										if(isset($cg_t_arr) && !empty($cg_t_arr) && isset($cg_t_arr["push_key"]) && trim($cg_t_arr["push_key"])!="")
										{
											//成功开课信息推送给教练
											$msg=str_replace("{class}",$cg_t_arr["class"],$push_inc["ck_class_cl_message"]);
											
											$msg=str_replace("{date}",$cg_t_arr["date"],$msg);
											
											$msg=str_replace("{start}",$cg_t_arr["start"],$msg);
											
											$msg=str_replace("{end}",$cg_t_arr["end"],$msg);
											
											$msg=str_replace("{room}",$cg_t_arr["room"],$msg);	
											
											$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>$cg_t_arr["push_key"],"title"=>$push_inc["ck_class_cl_title"]);
											
											a_push($_arrays);												
										}
										
										if(isset($cg_x_arr) && is_array($cg_x_arr) && !empty($cg_x_arr))
										{
											//成功开课推送给学员
											$msg=str_replace("{class}",$cg_x_arr["class"],$push_inc["ck_class_message"]);
											
											$msg=str_replace("{date}",$cg_x_arr["date"],$msg);
											
											$msg=str_replace("{start}",$cg_x_arr["start"],$msg);
											
											$msg=str_replace("{end}",$cg_x_arr["end"],$msg);
											
											$msg=str_replace("{room}",$cg_x_arr["room"],$msg);	
											
											$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>trim($cg_x_arr["push_key"],","),"title"=>$push_inc["ck_class_title"]);
											
											//c_all_push($_arrays);
												
										}
										
										if(isset($qx_t_arr) && is_array($qx_t_arr) && !empty($qx_t_arr))
										{
											//开始推送取消信息给教练
											for($a=0;$a<count($qx_t_arr);$a++)
											{
												$msg=str_replace("{class}",$qx_t_arr[$a]["class"],$push_inc["ck_class_qx_message"]);
												
												$msg=str_replace("{date}",$qx_t_arr[$a]["date"],$msg);
												
												$msg=str_replace("{start}",$qx_t_arr[$a]["start"],$msg);
												
												$msg=str_replace("{end}",$qx_t_arr[$a]["end"],$msg);
												
												$msg=str_replace("{room}",$qx_t_arr[$a]["room"],$msg);	
												
												$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>$qx_t_arr[$a]["push_key"],"title"=>$push_inc["ck_class_qx_title"]);
												
												a_push($_arrays);													
											}	
										}
										if(isset($qx_x_arr) && is_array($qx_x_arr) && !empty($qx_x_arr))
										{
											//开始推送取消信息给学员
											for($a=0;$a<count($qx_x_arr);$a++)
											{
												$msg=str_replace("{class}",$qx_x_arr[$a]["class"],$push_inc["ck_class_qx_message_member"]);
												
												$msg=str_replace("{date}",$qx_x_arr[$a]["date"],$msg);
												
												$msg=str_replace("{start}",$qx_x_arr[$a]["start"],$msg);
												
												$msg=str_replace("{end}",$qx_x_arr[$a]["end"],$msg);
												
												$msg=str_replace("{room}",$qx_x_arr[$a]["room"],$msg);	
												$msg=str_replace("{teacher}",$qx_x_arr[$a]["teacher"],$msg);	
												
												$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>trim($qx_x_arr[$a]["push_key"],","),"title"=>$push_inc["ck_class_qx_title_member"]);
												
												//c_all_push($_arrays);													
											}	
										}
										
										json_array2("10000","成功","");
									}
									else
									{
										$this->db->trans_rollback();
										error_show();
									}								
								
								}								
							}
						}
					}
				}
				else
				{
					$this->db->trans_rollback();
					json_array2("30000","抱歉：课程已经被取消，请您选择其他课程预约","");	
				}
			}
			else
			{
				$this->db->trans_rollback();
				error_show();
			}
		}
		
		//推送操课成立给对应的学员和教练
		private function push_class_success($id)
		{
			$query=$this->db->query("select * from `dg_tearch_plans` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				//开始查询对应的教练信息进行推送
				$t_query=$this->db->query("select * from `dg_teacher` where `id`='".$result["tid"]."'");
				
			}	
		}
		
		//获取对应的新晋课程列表
		public function index()
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			$sql="select `id`,`name`,`alt`,`bg_file` from `dg_class` order by `id` desc";		
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);	
			json_array2("10000","成功",$query->result_array());				
		}
		
		//获取对应的课程详情信息
		public function item($id)
		{
			$query=$this->db->query("select * from `dg_class` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				//开始加载关注信息
				$result["focus_state"]=0;
				if($result["focus"]!="" && is_fulls("token"))
				{
					$rs=$this->get_users(trim($_REQUEST["token"]),"id");
					//$arr=explode(",",trim($result["focus"],","));
					$arr=json_decode($result["focus"],true);
					if(in_array($rs["id"],$arr))
					{
						$result["focus_state"]=1;
					}
				}
				unset($result["focus"]);
				unset($result["pub_time"]);
				unset($result["bg_file"]);
				json_array2("10000","成功",$result);	
			}	
			else
			{
				error_show();	
			}
		}
		
		//课程关注取消接口
		public function focus($id,$rs)
		{
			$query=$this->db->query("select `focus` from `dg_class` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$f=1;
				$arrs=array();
				if(trim($result["focus"])!="")
				{
					$arrs=json_decode($result["focus"],true);
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
					$arrs[$rs["id"]]=$rs["id"];
					$_array=array("focus"=>json_encode($arrs));
					$text="关注成功";	
				}
				else
				{
					//echo array_search($rs["id"],$arr);die();
					unset($arrs[$rs["id"]]);
					$_array=array("focus"=>json_encode($arrs));
					$text="取消关注成功";
				}
				$this->db->update("class",$_array,array("id"=>$id));
				json_array2("10000",$text,"success");	
			}
			else
			{
				error_show();	
			}				
		}
		
		//课程对应的教师信息接口
		public function teachers($id)
		{
			//高峰、低峰时间计算开始
			$h_time="";
			$querys=$this->db->query("select `min`,`max` from `dg_time_model` where `model`='1' and `act`='3' order by `id` asc");
			foreach($querys->result_array() as $arrays)
			{
				$h_time.=",".$arrays["min"].":00-".$arrays["max"].":00";
			}
			$h_time=trim($h_time,",");
			$l_time="";
			$querys=$this->db->query("select `min`,`max` from `dg_time_model` where `model`='2' and `act`='3' order by `id` asc");
			foreach($querys->result_array() as $arrays)
			{
				$l_time.=",".$arrays["min"].":00-".$arrays["max"].":00";
			}	
			$l_time=trim($l_time,",");	
			
			$class_id_a='"class":'.$id.',';
			
			$class_id_b='"class":"'.$id.'",';
			
			$query=$this->db->query("select `id`,`realname`,`money_desc` from `dg_teacher` where `act`='2' and (`money_desc` like '%$class_id_a%' or `money_desc` like '%$class_id_b%')");
			$array=array();$i=0;
			foreach($query->result_array() as $arrays)
			{
				
				$array[$i]=$arrays;
				$as=json_decode($array[$i]["money_desc"],true);
				if(is_array($as) && !empty($as)){
					for($a=0;$a<count($as);$a++)
					{
						if($as[$a]["class"]==$id)
						{
							$array[$i]["money_peak"]=$as[$a]["money_peak"];
							$array[$i]["money_slack"]=$as[$a]["money_slack"];
						}	
					}
				}
				else
				{
					json_array2("30000","抱歉：细节数据读取失败，请您稍后再试","");	
				}
				$array[$i]["h_time"]=$h_time;
				$array[$i]["l_time"]=$l_time;
				unset($array[$i]["money_desc"]);
				$i++;	
			}	
			json_array2("10000","成功",$array);			
		}
		
		//为当前课程获取教练信息
		/*private function create_plan_for_class($querys,$id)
		{
			$array=array();$i=0;
			foreach($querys->result_array() as $arrays)
			{
				$array[$i]=$arrays;
				if($array[$i]["class_id"]!=$id)
				{
					//不符合当前的教练
					$array[$i]["class_name"]="";
					$array[$i]["class_id"]="";
					$array[$i]["state"]=0;								
				}
				$i++;	
			}	
			return $array;				
		}*/
		
		private function create_plan_for_class($id,$data,$rid)
		{
			$array=array();
			$a=0;
			for($i=7;$i<=21;$i++)
			{
				if($i<10)
				{
					$start="0".$i.":00";	
				}
				else
				{
					$start=$i.":00";
				}
				
				if (($end=($i+1)) < 10) {
				    $end="0".($i+1).":00";
				    
				}else{
				    $end=($i+1).":00";
				}
				$array[$a]["node"]=$start."-".$end;
				$array[$a]["id"]="";
				
				//查询数据库是否有当前的数据
				$sql="select `id`,`class_id`,`class_name`,`state`,`tid`,`tid_name`,`room_name`,`room_id` from `dg_tearch_plans` where `class_id`='$id' and `room_id`='$rid' and `node`='".$array[$a]["node"]."' and `date`='".$data."' limit 1";
				
				//echo $sql;
				
				//print_r($array);
				
				$query=$this->db->query($sql);
				$array[$a]["class_id"]="";
				$array[$a]["class_name"]="";
				$array[$a]["state"]="";
				$array[$a]["tid"]="";
				$array[$a]["tid_name"]="";
				$array[$a]["room_id"]="";
				$array[$a]["room_name"]="";
				//$array[$a]["node"]="";
				if($query->num_rows()>0)
				{
					$result=$query->row_array();
					$array[$a]=array_merge($array[$a],$result);
				}
				
				$a++;	
			}	
			return $array;				
		}
		
		//获取对应课程近七日教练的安排
		public function classall($id,$rid)
		{
			$query=$this->db->query("select `id` from `dg_class` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{		
				$week[0]["time"]=date("Y-m-d");
				for($i=0;$i<=6;$i++)
				{
					$time=time()+(3600*24)*$i;
					$week[$i]["time"]=date("Y-m-d",$time);
					//$querys=$this->db->query("select `node`,`id`,`class_id`,`class_name`,`state`,`tid` ,`tid_name` from `dg_tearch_plans` where `date`='".$week[$i]["time"]."' order by `id` asc");
					//$week[$i]["lists"]=$this->create_plan_for_class($querys,$id);
					$week[$i]["lists"]=$this->create_plan_for_class($id,$week[$i]["time"],$rid);
				}	
				json_array2("10000","成功",$week);
			}
			else
			{
				error_show();	
			}	
		}
		
		//获取对应的人气操课信息
		public function joins()
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):6;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			$sql="select `join_m`,`join`,`id`,`name`,`alt`,`bg_file` from `dg_class` order by `join_m` desc";		
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);	
			json_array2("10000","成功",$query->result_array());				
		}
		
		//我追踪的课程
		public function collect($rs)
		{
			$likes='"'.$rs["id"].'":"'.$rs["id"].'"';
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;
			$sql="select `id`,`name`,`alt`,`bg_file`,`contents` from `dg_class` where `focus` like '%".$likes."%' order by `join` desc";
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);
			json_array2("10000","成功",$query->result_array());		
		}
		
	}