<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Classs_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}

		
		//课类添加
		public function adds_subs()
		{
			$name=$this->input->post("name");
			$query=$this->db->query("select `id` from `dg_class` where `name`='$name' limit 1");
			if($query->num_rows()>0)
			{
				ajaxs(30000,"课类名称已经存在");die();	
			}
			else
			{
				$_array=array(
					"name"=>$name,
					"counts"=>$this->input->post("counts"),
					"alt"=>$this->input->post("alt"),
					"contents"=>$this->input->post("contents"),
					"bg_file"=>$this->input->post("tupian"),
					"act"=>$this->input->post("act"),
					"pub_time"=>time(),
				);
				if($_array["act"]==1)
				{
					$_array["path"]=$this->input->post("tupian1");	
				}
				else
				{
					$_array["path"]=$this->input->post("mv");		
				}
				$this->db->insert("class",$_array);
				ajaxs(10000,"添加成功");
			}
		}
		
		//删除课程
		public function dels($id)
		{
			$query1=$this->db->query("select `id` from `dg_tearch_plans` where `class_id`='$id' limit 1");
			if($query1->num_rows()>0)
			{
				ajaxs(30000,"当前课类已被安排课程，无法删除");	
			}	
			else
			{
				$ids='"class":'.$id.',';
				$query2=$this->db->query("select `id` from `dg_teacher` where `money_desc` like '%$ids%' limit 1");
				if($query2->num_rows()>0)
				{
					ajaxs(30000,"当前课类已被绑定过操课教练，无法删除");	
				}	
				else
				{
					$this->db->query("delete from `dg_class` where `id`='$id'");
					ajaxs(10000,"删除成功");	
				}
			}
		}
		
		//修改课程
		public function edits_subs($id)
		{
			$name=$this->input->post("name");
			$query=$this->db->query("select `id` from `dg_class` where `name`='$name' and `id`!='$id' limit 1");
			if($query->num_rows()>0)
			{
				ajaxs(30000,"课类名称已经存在");die();	
			}
			else
			{
				$_array=array(
					"name"=>$name,
					"counts"=>$this->input->post("counts"),
					"alt"=>$this->input->post("alt"),
					"contents"=>$this->input->post("contents"),
					"bg_file"=>$this->input->post("tupian"),
					"act"=>$this->input->post("act"),
					"pub_time"=>time(),
				);
				if($_array["act"]==1)
				{
					$_array["path"]=$this->input->post("tupian1");	
				}
				else
				{
					$_array["path"]=$this->input->post("mv");		
				}
				$this->db->update("class",$_array,array("id"=>$id));
				ajaxs(10000,"修改成功");
			}				
		}
		
		//修改私课
		public function sikes_edits_subs()
		{
			$id=intval($this->uri->segment(4));
			$date=$this->input->post("datemax");

			$time_x=strtotime($date." 00:00:00");//最后发布时间集合
			$time_tamp=time()+3600*24;
			$time_y=strtotime(date("Y-m-d",$time_tamp)." 00:00:00")+3600*24*30;
			if($time_y<$time_x)
			{
				ajaxs(30000,"抱歉：私课仅允许添加30日内课程，请重新选择课程时间");	
			}

			$s=$this->input->post("s");
			if($s<10)
			{
				$s="0".$s;	
			}
			$e=$this->input->post("e");
			$frees=$this->input->post("frees");
			$sql="select `p`.*,`t`.`realname`,`t`.`mobile`,`t`.`avatar`,`t`.`gender`,`t`.`level`,`t`.`score`,`t`.`desc`,`t`.`balance`,`t`.`reg_time`,`t`.`login_time`,`t`.`contents`,`t`.`user_agent`,`money_desc` from `dg_tearch_plan_list` as `p` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `p`.`id`='$id' limit 1";
			$query=$this->db->query($sql);
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["state"]==2)
				{
					$querys=$this->db->query("select `id` from `dg_tearch_plan_list` where `tid_index`='".right_index($result["tid"])."' and `tid`='".$result["tid"]."' and `date`='$date' and `node`='".$s.":00"."-".$e.":00"."' and `id`!='$id'");
					if($querys->num_rows()>0)
					{
						ajaxs(30000,"当前教练在".$date."的".$s.":00"."-".$e.":00已经发布过课程");	
					}
					else
					{
						//开始修改
						$moneys=$this->get_moneys($result["money_desc"],$date,$s,$e);
						$_array=array(
							"date"=>$date,
							"node"=>$s.":00"."-".$e.":00",
							"money"=>$moneys,
							"start_time"=>strtotime($date." ".$s.":00:00"),
							"end_time"=>strtotime($date." ".$e.":00:00"),
							"frees"=>$this->input->post("frees"),
							"time"=>time(),
						);
						if($this->db->update("tearch_plan_list",$_array,array("id"=>$id)))
						{
							$this->db->query("update `dg_tearch_plan_list` set `frees`='".$_array["frees"]."' where `tid_index`='".right_index($result["tid"])."' and `tid`='".$result["tid"]."' and `date`='$date'");
							ajaxs(10000,"修改成功");	
						}
						else
						{
							ajaxs(30000,"网络连接失败");	
						}
					}
						
				}
				else
				{
					ajaxs(30000,"抱歉：当前课程状态不允许修改");	
				}
			}
			else
			{
				ajaxs(30000,"抱歉：修改私课信息不存在，请稍后再试");	
			}				
		}
		
		//获取高低峰价格
		private function get_moneys($money_desc,$date,$s,$e)
		{
			$rs=$this->get_moneys_model($money_desc,$date,$s,$e);
			$arr=json_decode($money_desc,true);
			if($rs==1)
			{
				return $arr["money_peak"];
			}	
			else
			{
				return $arr["money_slack"];	
			}
		}
	
		//获取高低峰价格
		private function get_moneys_model($money_desc,$date,$s,$e)
		{
			$s=intval($s);
			$arr=json_decode($money_desc,true);
			$query=$this->db->query("SELECT * FROM `dg_time_model` WHERE `act` = '2'");
			foreach($query->result_array() as $array)
			{
				if($array["min"]<$array["max"])
				{
					//echo $s;
					//正常判断模式	
					//echo $array["min"]."_____".$array["max"];
					if($array["min"]<=$s && $array["max"]>$s)
					{
						//成交
						return $array["model"];
					}
				}
				else
				{
					//24点截止判断模式
					if(($array["min"]<=$s && $s<=24) || ($s>=0 && $array["max"]>$s))
					{
						//成交
						return $array["model"];
					}	
				}
			}
		}
		
		//删除私课
		public function sikes_dels()
		{
			$id=$this->input->post("id");
			$query=$this->db->query("select `state`,`id` from `dg_tearch_plan_list` where `id` in (".$id.")");
			$st=1;
			foreach($query->result_array() as $array)
			{
				if($array["state"]!=2 && $array["state"]!=4 && $array["state"]!=5)
				{
					$st=2;	
				}	
				$qu=$this->db->query("select `id` from `dg_orders` where `class_id`='".trim($array["id"])."' and `act`='1'");
				if($qu->num_rows()>0)
				{
					$st=2;	
				}
			}
			if($st==2)
			{
				ajaxs(30000,"抱歉：您够选择课程中含有不可删除课程");	
			}
			else
			{
				$this->db->query("delete from `dg_tearch_plan_list` where `id` in (".$id.")");
				ajaxs(10000,"删除成功");	
			}
		}
		
		//添加私课
		public function sikes_adds_subs()
		{
			$id=intval($this->uri->segment(4));
			$date=$this->input->post("datemax");
			$time_x=strtotime($date." 00:00:00");//最后发布时间集合
			$time_tamp=time()+3600*24;
			$time_y=strtotime(date("Y-m-d",$time_tamp)." 00:00:00")+3600*24*30;
			if($time_y<$time_x)
			{
				ajaxs(30000,"抱歉：私课仅允许添加30日内课程，请重新选择课程时间");	
			}
			
			//修改后的代码
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='1'");
			if($query->num_rows()<=0)
			{
				ajaxs(30000,"教练信息读取失败");
			}
			$result=$query->row_array();
			$frees=$this->input->post("frees");
			$nodes=$this->input->post("nodes");
			$arr=explode(",",$nodes);
			//print_r($arr);die();
			foreach($arr as $k=>$v)
			{
				if(is_numeric($v))
				{
					$s=$v;
					$e=$s+1;
					if($s<10)
					{
						$s="0".$s;	
					}
					if($e<10)
					{
						$e="0".$e;	
					}
					$querys=$this->db->query("select `id` from `dg_tearch_plan_list` where `tid_index`='".right_index($id)."' and `tid`='".$id."' and `date`='$date' and `node`='".$s.":00"."-".$e.":00"."' limit 1");
					if($querys->num_rows()<=0)
					{
						//开始添加
						$moneys=$this->get_moneys($result["money_desc"],$date,$s,$e);
						$_array=array(
							"tid"=>$id,
							"tid_index"=>right_index($id),
							"date"=>$date,
							"node"=>$s.":00"."-".$e.":00",
							"money"=>$moneys,
							"start_time"=>strtotime($date." ".$s.":00:00"),
							"end_time"=>strtotime($date." ".$e.":00:00"),
							"frees"=>$frees,
							"time"=>time(),
							"state"=>2
						);
						if($this->db->insert("tearch_plan_list",$_array))
						{
							
							//echo 100;	
						}					
					}
				}
				
			}
			$this->db->query("update `dg_tearch_plan_list` set `frees`='".$frees."' where `tid_index`='".right_index($id)."' and `tid`='$id' and `date`='$date'");
			ajaxs(10000,"添加成功");	
			/*$s=$this->input->post("s");
			if($s<10)
			{
				$s="0".$s;	
			}
			$e=$this->input->post("e");
			$frees=$this->input->post("frees");
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='1'");
			if($query->num_rows()>0)
			{		
				$result=$query->row_array();	
				$querys=$this->db->query("select `id` from `dg_tearch_plan_list` where `tid_index`='".right_index($id)."' and `tid`='".$id."' and `date`='$date' and `node`='".$s.":00"."-".$e.":00"."'");
				if($querys->num_rows()>0)
				{
					ajaxs(30000,"当前教练在".$date."的".$s.":00"."-".$e.":00已经发布过课程");	
				}
				else
				{
					//开始添加
					$moneys=$this->get_moneys($result["money_desc"],$date,$s,$e);
					$_array=array(
						"tid"=>$id,
						"tid_index"=>right_index($id),
						"date"=>$date,
						"node"=>$s.":00"."-".$e.":00",
						"money"=>$moneys,
						"start_time"=>strtotime($date." ".$s.":00:00"),
						"end_time"=>strtotime($date." ".$e.":00:00"),
						"frees"=>$this->input->post("frees"),
						"time"=>time(),
						"state"=>2
					);
					if($this->db->insert("tearch_plan_list",$_array))
					{
						$this->db->query("update `dg_tearch_plan_list` set `frees`='".$_array["frees"]."' where `tid_index`='".right_index($id)."' and `tid`='$id' and `date`='$date'");
						ajaxs(10000,"添加成功");	
					}
					else
					{
						ajaxs(30000,"网络连接失败");	
					}
				}
			}
			else
			{
				ajaxs(30000,"教练信息读取失败");	
			}*/
					
		}
		
		//删除操作
		public function caokes_dels()
		{
			$id=$this->input->post("id");
			$query=$this->db->query("select `state`,`loads`,`id` from `dg_tearch_plans` where `id` in (".$id.")");
			$st=1;
			foreach($query->result_array() as $array)
			{
				if($array["state"]!=1 && $array["state"]!=4)
				{
					$st=2;	
				}	
				else
				{
					if($array["state"]==1 && $array["loads"]>0)
					{
						$st=2;	
					}	
				}
				$qu=$this->db->query("select `id` from `dg_orders` where `pid`='".trim($array["id"])."' and `act`='2'");
				if($qu->num_rows()>0)
				{
					$st=2;	
				}				
			}
			if($st==2)
			{
				ajaxs(30000,"抱歉：您够选择课程中含有不可删除课程");	
			}
			else
			{
				$this->db->query("delete from `dg_tearch_plans` where `id` in (".$id.")");
				ajaxs(10000,"删除成功");	
			}				
		}
		
		//发布操课
		public function caokes_adds_subs()
		{
			$id=intval($this->uri->segment(4));	
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='2'");
			if($query->num_rows()>0)
			{		
				$rs=$query->row_array();	
				$class_id=$this->input->post("classs");

				$room_id=$this->input->post("rooms");

				$days=$this->input->post("datemax");
				
				$time_x=strtotime($days." 00:00:00");//最后发布时间集合
				$time_tamp=time()+3600*24;
				$time_y=strtotime(date("Y-m-d",$time_tamp)." 00:00:00")+3600*24*14;
				if($time_y<$time_x)
				{
					ajaxs(30000,"抱歉：操课仅允许添加14日内课程，请重新选择课程时间");	
				}				
				
				$start_time=$this->input->post("s").":00";
				$end_time=$this->input->post("e").":00";
				$frees=$this->input->post("frees");
				$moneysss=$this->input->post("moneys");
				
				$arr_a=explode(":",$start_time);
				$arr_b=explode(":",$end_time);	
				if(intval($arr_a[0])+1==intval($arr_b[0]) && $arr_b[1]==0 && $arr_a[1]==0)
				{
					//echo $days." ".$end_time.":00";die();
					$start_time1=$days." ".$start_time.":00";
					$end_time1=$days." ".$end_time.":00";
					$nodes=$start_time."-".$end_time;
					$start_time100=$start_time;
					if($start_time<10)
					{
						$start_time100="0".$start_time;	
					}
					$nodes1=$start_time100."-".$end_time;
					$nodes=$nodes1;
					$query_a=$this->db->query("select `id`,`name`,`counts` from `dg_class` where `id`='$class_id'");
					$query_b=$this->db->query("select `id`,`name`,`count` from `dg_rooms` where `id`='$room_id'");
					if($query_a->num_rows()>0 && $query_b->num_rows()>0)
					{
						$result_a=$query_a->row_array();
						$result_b=$query_b->row_array();
						
						//开启事务处理
						$this->db->trans_strict(false);
						$this->db->trans_begin();	
	
						//判断当前时间段是否在这个大厅发布过这个课程
						$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' and `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' limit 1");
						
						if($query->num_rows()>0)
						{
							$this->db->trans_rollback();
							ajaxs(30000,"抱歉：当前教练已经在当前时间点发布过课程了，请勿继续发布");	
						}
						
						//判断当前节点课程发布条数
						$sql="select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' limit 0,4";
						$query=$this->db->query($sql);
						
						if($query->num_rows()>=3)
						{
							$this->db->trans_rollback();
							ajaxs(30000,"抱歉：当前时间节点课程已经发布3条，无法继续发布");
						}
						else
						{
							//判断当前时间是否在其他大厅发布过这个课程
							$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`!='$room_id' limit 1");
							
							if($query->num_rows()>0)
							{
								$this->db->trans_rollback();
								
								ajaxs(30000,"抱歉：当前教练发布的时间在其他大厅同时有课，无法继续发布");
							}	
							else
							{
								//判断当前时间段是否有发布过同一class_id的课程
								$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' and `class_id`='$class_id' limit 1");
								
								if($query->num_rows()>0)
								{
									$this->db->trans_rollback();
									
									ajaxs(30000,"抱歉：当前时间段已经发布有您选择的课程类型，请更换其他课程类型进行发布");
								}
								else
								{
									
									require FCPATH."config/sys.inc.php";
									
									//判断每日最多发布数量
									$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' limit 4");
									
									if($query->num_rows()>=$_sys_inc["class_insert_count_day"])
									{
										$this->db->trans_rollback();
										ajaxs(30000,"抱歉：您每日最多能发布".$_sys_inc["class_insert_count_day"]."节课程");	
									}
									else
									{
										//计算当前时间段的价格
										$array=json_decode($rs["money_desc"],true);
										$sales="";
										$sales_a="";//高峰期价格
										$sales_b="";//低峰期价格
										$moneys_a="";//高峰费率
										$moneys_b="";//低峰费率
										
										if(is_array($array) && isset($array[0]["class"]))
										{
											for($i=0;$i<count($array);$i++)
											{
												if($class_id==$array[$i]["class"])
												{
													$sales_a=$array[$i]["money_peak"];
													$sales_b=$array[$i]["money_slack"];	
													$moneys_a=$array[$i]["money_peak_sys"];	
													$moneys_b=$array[$i]["money_slack_sys"];	
												}
											}
										}
										else
										{
											$this->db->trans_rollback();
											
											ajaxs(30000,"抱歉：当前教练的授课信息读取失败，系统拒绝您的操作，请您及时联系官方客服进行解决");
										}
										
										//开始根据高峰期时间或者低峰期来取对应价格
										$s=intval($arr_a[0]);
										
										$querys=$this->db->query("select `model` from `dg_time_model` where `min`<='$s' and `max`>'$s' limit 1");
										
										if($querys->num_rows()>0)
										{
											$results=$querys->row_array();
										}
										else
										{
											$this->db->trans_rollback();
											ajaxs(30000,"抱歉：系统高低峰时间配置有误，请您稍后再尝试发布");	
										}
										
										if($results["model"]==1)
										{
											$sales=$sales_a;
											$moneyss=$moneys_a;	
										}
										else
										{
											$sales=$sales_b;
											$moneyss=$moneys_b;		
										}
										
										if($sales=="")
										{
											$this->db->trans_rollback();
											ajaxs(30000,"抱歉：您的授课信息读取失败，系统拒绝您的操作，请您及时联系官方客服进行解决");	
										}
										$mins="";
										$maxs="";
										$arr=json_decode($rs["money_desc"],true);
										//print_r($arr);die();
										for($i=0;$i<count($arr);$i++)
										{
											if($arr[$i]["class"]==$class_id && $arr[$i]["alls"][0]["room_id"]==$room_id)
											{
												$mins=$arr[$i]["alls"][0]["class_min"];
												$maxs=$arr[$i]["alls"][0]["class_max"];
											}
											if($arr[$i]["class"]==$class_id && $arr[$i]["alls"][1]["room_id"]==$room_id)
											{
												$mins=$arr[$i]["alls"][1]["class_min"];
												$maxs=$arr[$i]["alls"][1]["class_max"];
											}

										}
										if($mins=="" || $maxs=="")
										{
											ajaxs(30000,"抱歉：数据读取失败，请稍后再试");	
										}
										$_array=array(
											"date"=>$days,
											"tid"=>$rs["id"],
											"tid_index"=>right_index($rs["id"]),
											"tid_name"=>$rs["realname"],
											"class_id"=>$class_id,
											"class_name"=>$result_a["name"],
											"room_id"=>$room_id,
											"room_name"=>$result_b["name"],
											"node"=>$nodes1,
											"start_time"=>strtotime($start_time1),
											"end_time"=>strtotime($end_time1),
											"sale"=>$sales,
											"min"=>$mins,
											"max"=>$maxs,
											"loads"=>0,
											"state"=>1,
											"sys"=>$moneyss,
											"moneys"=>0,
											"times"=>time(),
										);
										if($frees==2)
										{
											$_array["sys"]="";	
											$_array["moneys"]=$moneysss;	
											$_array["sale"]="0";	
										}
										
										$this->db->insert("tearch_plans",$_array);	
										
										if($this->db->trans_status()==true){
											$this->db->trans_commit();
											ajaxs("10000","发布成功");
										}else{
											$this->db->trans_rollback();
											ajaxs("30000","网络连接失败");
										}									
													
									}
										
								}
									
							}
						}
						
										
						
					}
					else
					{
						$this->db->trans_rollback();
						json_array2(30000,"抱歉：没有找到对应操课厅或课程信息","");		
					}
					
				}
				else
				{
					$this->db->trans_rollback();
					json_array2(30000,"抱歉：发布时间节点有误","");	
				}			
			}
			else
			{
				ajaxs(30000,"教练信息读取失败");	
			}
		}

		//修改操课
		public function caokes_edit_subs()
		{
			$id=intval($this->uri->segment(4));	
			$query=$this->db->query("select `p`.*,`t`.`realname`,`t`.`mobile`,`t`.`avatar`,`t`.`gender`,`t`.`level`,`t`.`score`,`t`.`desc`,`t`.`money_desc`,`t`.`balance`,`t`.`reg_time`,`t`.`login_time`,`t`.`contents`,`t`.`user_agent` from `dg_tearch_plans` as `p` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `p`.`id`='$id' limit 1");
			if($query->num_rows()>0)
			{		
				$rs=$query->row_array();	
				$class_id=$this->input->post("classs");
				//$class_id=explode("_",$class_id);
				//$class_id=$class_id[0];
				$room_id=$this->input->post("rooms");
				//$room_id=explode("_",$room_id);
				//$room_id=$room_id[0];
				$days=$this->input->post("datemax");
				
				$time_x=strtotime($days." 00:00:00");//最后发布时间集合
				$time_tamp=time()+3600*24;
				$time_y=strtotime(date("Y-m-d",$time_tamp)." 00:00:00")+3600*24*14;
				if($time_y<$time_x)
				{
					ajaxs(30000,"抱歉：操课仅允许添加14日内课程，请重新选择课程时间");	
				}				
				
				$start_time=$this->input->post("s").":00";
				$end_time=$this->input->post("e").":00";
				$frees=$this->input->post("frees");
				$moneysss=$this->input->post("moneys");
				
				$arr_a=explode(":",$start_time);
				$arr_b=explode(":",$end_time);	
				if(intval($arr_a[0])+1==intval($arr_b[0]) && $arr_b[1]==0 && $arr_a[1]==0)
				{
					$start_time1=$days." ".$start_time.":00";
					$end_time1=$days." ".$end_time.":00";
					$nodes=$start_time."-".$end_time;
					$start_time100=$start_time;
					if($start_time<10)
					{
						$start_time100="0".$start_time;	
					}
					$nodes1=$start_time100."-".$end_time;
					$nodes=$nodes1;
					$query_a=$this->db->query("select `id`,`name`,`counts` from `dg_class` where `id`='$class_id'");
					$query_b=$this->db->query("select `id`,`name`,`count` from `dg_rooms` where `id`='$room_id'");
					if($query_a->num_rows()>0 && $query_b->num_rows()>0)
					{
						$result_a=$query_a->row_array();
						$result_b=$query_b->row_array();
						
						//开启事务处理
						$this->db->trans_strict(false);
						$this->db->trans_begin();	
	
						//判断当前时间段是否在这个大厅发布过这个课程
						$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' and `tid_index`='".right_index($rs["tid"])."' and `tid`='".$rs["tid"]."' and `id`!='$id' limit 1");
						
						if($query->num_rows()>0)
						{
							$this->db->trans_rollback();
							ajaxs(30000,"抱歉：当前教练已经在当前时间点发布过课程了，请勿继续发布");	
						}
						
						//判断当前节点课程发布条数
						$sql="select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' and `id`!='$id' limit 0,4";
						$query=$this->db->query($sql);
						
						if($query->num_rows()>=3)
						{
							$this->db->trans_rollback();
							ajaxs(30000,"抱歉：当前时间节点课程已经发布3条，无法继续发布");
						}
						else
						{
							//判断当前时间是否在其他大厅发布过这个课程
							$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`!='$room_id' and `id`!='$id' and `tid_index`='".right_index($rs["tid"])."' and `tid`='".$rs["tid"]."' limit 1");
							
							if($query->num_rows()>0)
							{
								$this->db->trans_rollback();
								
								ajaxs(30000,"抱歉：当前教练发布的时间在其他大厅同时有课，无法继续发布");
							}	
							else
							{
								//判断当前时间段是否有发布过同一class_id的课程
								$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' and `class_id`='$class_id' and `id`!='$id' limit 1");
								
								if($query->num_rows()>0)
								{
									$this->db->trans_rollback();
									
									ajaxs(30000,"抱歉：当前时间段已经发布有您选择的课程类型，请更换其他课程类型进行发布");
								}
								else
								{
									
									require FCPATH."config/sys.inc.php";
									

										//计算当前时间段的价格
										$array=json_decode($rs["money_desc"],true);
										$sales="";
										$sales_a="";//高峰期价格
										$sales_b="";//低峰期价格
										$moneys_a="";//高峰费率
										$moneys_b="";//低峰费率
										
										if(is_array($array) && isset($array[0]["class"]))
										{
											for($i=0;$i<count($array);$i++)
											{
												if($class_id==$array[$i]["class"])
												{
													$sales_a=$array[$i]["money_peak"];
													$sales_b=$array[$i]["money_slack"];	
													$moneys_a=$array[$i]["money_peak_sys"];	
													$moneys_b=$array[$i]["money_slack_sys"];	
												}
											}
										}
										else
										{
											$this->db->trans_rollback();
											
											ajaxs(30000,"抱歉：当前教练的授课信息读取失败，系统拒绝您的操作，请您及时联系官方客服进行解决");
										}
										
										//开始根据高峰期时间或者低峰期来取对应价格
										$s=intval($arr_a[0]);
										
										$querys=$this->db->query("select `model` from `dg_time_model` where `min`<='$s' and `max`>'$s' limit 1");
										
										if($querys->num_rows()>0)
										{
											$results=$querys->row_array();
										}
										else
										{
											$this->db->trans_rollback();
											ajaxs(30000,"抱歉：系统高低峰时间配置有误，请您稍后再尝试发布");	
										}
										
										if($results["model"]==1)
										{
											$sales=$sales_a;
											$moneyss=$moneys_a;	
										}
										else
										{
											$sales=$sales_b;
											$moneyss=$moneys_b;		
										}
										
										if($sales=="")
										{
											$this->db->trans_rollback();
											ajaxs(30000,"抱歉：您的授课信息读取失败，系统拒绝您的操作，请您及时联系官方客服进行解决");	
										}
										
										$mins="";
										$maxs="";
										$arr=json_decode($rs["money_desc"],true);
										//print_r($arr);die();
										for($i=0;$i<count($arr);$i++)
										{
											if($arr[$i]["class"]==$class_id && $arr[$i]["alls"][0]["room_id"]==$room_id)
											{
												$mins=$arr[$i]["alls"][0]["class_min"];
												$maxs=$arr[$i]["alls"][0]["class_max"];
											}
											if($arr[$i]["class"]==$class_id && $arr[$i]["alls"][1]["room_id"]==$room_id)
											{
												$mins=$arr[$i]["alls"][1]["class_min"];
												$maxs=$arr[$i]["alls"][1]["class_max"];
											}

										}
										if($mins=="" || $maxs=="")
										{
											ajaxs(30000,"抱歉：数据读取失败，请稍后再试");	
										}	
																			
										$_array=array(
											"date"=>$days,
											"tid"=>$rs["tid"],
											"tid_index"=>right_index($rs["tid"]),
											"tid_name"=>$rs["realname"],
											"class_id"=>$class_id,
											"class_name"=>$result_a["name"],
											"room_id"=>$room_id,
											"room_name"=>$result_b["name"],
											"node"=>$nodes1,
											"start_time"=>strtotime($start_time1),
											"end_time"=>strtotime($end_time1),
											"sale"=>$sales,
											"min"=>$mins,
											"max"=>$maxs,
											"loads"=>0,
											"state"=>1,
											"sys"=>$moneyss,
											"moneys"=>0,
											"times"=>time(),
										);
										if($frees==2)
										{
											$_array["sys"]="";	
											$_array["moneys"]=$moneysss;	
											$_array["sale"]="0";	
										}
										
										$this->db->update("tearch_plans",$_array,array("id"=>$id));	
										
										if($this->db->trans_status()==true){
											$this->db->trans_commit();
											ajaxs("10000","修改成功");
										}else{
											$this->db->trans_rollback();
											ajaxs("30000","网络连接失败");
										}									
													
									}
										
							
									
							}
						}
						
										
						
					}
					else
					{
						$this->db->trans_rollback();
						json_array2(30000,"抱歉：没有找到对应操课厅或课程信息","");		
					}
					
				}
				else
				{
					$this->db->trans_rollback();
					json_array2(30000,"抱歉：发布时间节点有误","");	
				}			
			}
			else
			{
				ajaxs(30000,"教练信息读取失败");	
			}				
		}
		
	}