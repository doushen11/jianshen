<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Insert_model extends CI_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//获取当日课程时间段的状态值
		private function get_insert_states($rs,$class_id,$room_id,$days,$i,$start_time,$end_time)
		{
			
		
			$date_1=$days;
			
			$time=strtotime($date_1);
			
			$date_2=date("Y-m-d",$time);	
			
			if(trim($date_1)!=trim($date_2))
			{
				return false;	
			}		

			if($rs["act"]==1)
			{
				return false;	
			}
			
			
			$mins=0;
			$maxs=0;

			
			$array=json_decode($rs["money_desc"],true);
			
			/*$arr=array();
			$a=0;
			for($i=0;$i<count($array);$i++)
			{
				if($array[$i]["class"]==$class_id)
				{
					$mins=$array[$i]["class_min"];
					$maxs=$array[$i]["class_max"];
				}
			}	
			
			if($mins==0 && $maxs==0)
			{
				return false;	
			}*/	
			
			$arr_a=explode(":",$start_time);
			$arr_b=explode(":",$end_time);	
			if(intval($arr_a[0])+1==intval($arr_b[0]) && $arr_b[1]==0 && $arr_a[1]==0)
			{
				
				$start_time1=$days." ".$start_time.":00";
				$end_time1=$days." ".$end_time.":00";
				$nodes=$start_time."-".$end_time;
				$start_time100=$start_time;
				if($arr_a[0]<10)
				{
					$start_time100="0".intval($arr_a[0]).":00";	
				}
				$nodes1=$start_time100."-".$end_time;
				$nodes=$nodes1;
				$query_a=$this->db->query("select `id`,`name`,`counts` from `dg_class` where `id`='$class_id'");
				$query_b=$this->db->query("select `id`,`name`,`count` from `dg_rooms` where `id`='$room_id'");
				
				if(strtotime($start_time1)<time())
				{
					return false;
				}
				
				if($query_a->num_rows()>0 && $query_b->num_rows()>0)
				{
					$result_a=$query_a->row_array();
					$result_b=$query_b->row_array();

					//判断当前时间段是否在这个大厅发布过这个课程
					$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' and `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' limit 1");
					
					if($query->num_rows()>0)
					{
						return false;	
					}
					
					//判断当前节点课程发布条数
					$sql="select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' limit 0,4";
					$query=$this->db->query($sql);
					
					if($query->num_rows()>=3)
					{
						return false;
					}
					else
					{
						
						//判断当前时间是否在其他大厅发布过这个课程
						$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`!='$room_id' and `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' limit 1");
						
						if($query->num_rows()>0)
						{
							return false;		
						}	
						else
						{
							//判断当前时间段是否有发布过同一class_id的课程
							$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' and `class_id`='$class_id' limit 1");
							
							if($query->num_rows()>0)
							{
								return false;	
							}
							else
							{
								
								require FCPATH."config/sys.inc.php";
								
								//判断每日最多发布数量
								$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' limit 4");
								
								if($query->num_rows()>=$_sys_inc["class_insert_count_day"])
								{
									
									return false;		
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
										return false;
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
										return false;	
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
										return false;	
									}
									
									return true;									
												
								}
									
							}
								
						}
					}
					
									
					
				}
				else
				{
					return false;		
				}
				
			}
			else
			{
				return false;
			}			
		}
		
		//获取当日课程发布状态
		public function gets($rs)
		{
			if(isset($_REQUEST["class_id"]) && trim($_REQUEST["class_id"])!="" && isset($_REQUEST["room_id"]) && trim($_REQUEST["room_id"])!="" && isset($_REQUEST["days"]) && trim($_REQUEST["days"])!="")
			{
				
				$class_id=trim($_REQUEST["class_id"]);
				$room_id=trim($_REQUEST["room_id"]);
				$days=trim($_REQUEST["days"]);
				$a=0;
				$arr=array();
				for($i=9;$i<=21;$i++)
				{
					$i1=$i+1;
					$i<10?$i="0".$i:$i;
					
					$state=$this->get_insert_states($rs,$class_id,$room_id,$days,$i,$i.":00",$i1.":00");
					
					if($state)
					{
						$arr[$a]["time"]=$i;
						$a++;	
					}
				}	
				json_array2(10000,"成功",$arr);	
			}
			else
			{
				$a=0;
				for($i=9;$i<=21;$i++)
				{
					$i<10?$i="0".$i:$i;
					$arr[$a]["time"]=$i;
					$a++;	
				}	
				json_array2(10000,"成功",$arr);
			}
		}
		
		//读取课程发布对应的所有信息
		public function choose($rs)
		{
			
			if($rs["act"]!=2)
			{
				json_array2(30000,"抱歉：您的身份读取有误，系统拒绝操作","");
			}
			
			$array=json_decode($rs["money_desc"],true);
			
			$arr=array();
			$a=0;
			$arrs=array();
			$b=0;
			for($i=0;$i<count($array);$i++)
			{
				$query1=$this->db->query("select `id`,`name` from `dg_class` where `id`='".$array[$i]["class"]."'");
				if($query1->num_rows()>0)
				{
					$arrs[$b]=$query1->row_array();
					
					$arr[$a]["class_id"]=$arrs[$b]["id"];
					
					$arr[$a]["room_id"]=$array[$i]["alls"][0]["room_id"];
					$arr[$a]["class_min"]=$array[$i]["alls"][0]["class_min"];
					$arr[$a]["class_max"]=$array[$i]["alls"][0]["class_max"];
					$a++;
					$arr[$a]["class_id"]=$arrs[$b]["id"];
					$arr[$a]["room_id"]=$array[$i]["alls"][1]["room_id"];
					$arr[$a]["class_min"]=$array[$i]["alls"][1]["class_min"];
					$arr[$a]["class_max"]=$array[$i]["alls"][1]["class_max"];	
					$a++;	
					$b++;			
				}
			}
			
			$query2=$this->db->query("select * from `dg_rooms` order by `id` asc");	
			
			$array=array(
				"pps"=>$arr,
				"class"=>$arrs,
				"rooms"=>$query2->result_array(),
			);
			
			json_array2(10000,"成功",$array);
		}
		
		//发布课程
		public function adds($rs,$class_id,$room_id,$days,$start_time,$end_time)
		{

			$time_x=strtotime($days." 00:00:00");//最后发布时间集合
			$time_tamp=time()+3600*24;
			$time_y=strtotime(date("Y-m-d",$time_tamp)." 00:00:00")+3600*24*14;
			if($time_y<$time_x)
			{
				json_array2(30000,"抱歉：操课仅允许添加14日内课程，请重新选择课程时间","");	
			}				


			$date_1=$days;
			
			$time=strtotime($date_1);
			
			$date_2=date("Y-m-d",$time);	
			
			if(trim($date_1)!=trim($date_2))
			{
				json_array2(30000,"抱歉：您发布的年月日格式有误，系统拒绝操作","");		
			}		

			if($rs["act"]==1)
			{
				json_array2(30000,"抱歉：您的身份读取有误，系统拒绝操作","");	
			}
			
			
			$mins=0;
			$maxs=0;
			
			$array=json_decode($rs["money_desc"],true);
			
			$arr=array();
			$a=0;
			//$arr=json_decode($rs["money_desc"],true);
			//print_r($arr);die();
			for($i=0;$i<count($array);$i++)
			{
				if($array[$i]["class"]==$class_id && $array[$i]["alls"][0]["room_id"]==$room_id)
				{
					$mins=$array[$i]["alls"][0]["class_min"];
					$maxs=$array[$i]["alls"][0]["class_max"];
				}
				if($array[$i]["class"]==$class_id && $array[$i]["alls"][1]["room_id"]==$room_id)
				{
					$mins=$array[$i]["alls"][1]["class_min"];
					$maxs=$array[$i]["alls"][1]["class_max"];
				}

			}
			
			if($mins==0 && $maxs==0)
			{
				json_array2(30000,"抱歉：数据读取失败，系统拒绝操作","");	
			}		
			
			$arr_a=explode(":",$start_time);
			$arr_b=explode(":",$end_time);	
			if(intval($arr_a[0])+1==intval($arr_b[0]) && $arr_b[1]==0 && $arr_a[1]==0)
			{
				$start_time1=$days." ".$start_time.":00";
				$end_time1=$days." ".$end_time.":00";
				$nodes=$start_time."-".$end_time;
				$start_time100=$start_time;
				if($arr_a[0]<10)
				{
					$start_time100="0".intval($arr_a[0]).":00";	
				}
				$nodes1=$start_time100."-".$end_time;
				
				//echo $nodes1;die();
				$nodes=$nodes1;
				
				$query_a=$this->db->query("select `id`,`name`,`counts` from `dg_class` where `id`='$class_id'");
				$query_b=$this->db->query("select `id`,`name`,`count` from `dg_rooms` where `id`='$room_id'");
				
				if(strtotime($start_time1)<time())
				{
					json_array2(30000,"抱歉：当前选择时间点小于当前时间，无法发布","");	
				}
				
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
						json_array2(30000,"抱歉：您已经在当前时间点发布过课程了，请勿继续发布","");	
					}
					
					//判断当前节点课程发布条数
					$sql="select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' limit 0,4";
					$query=$this->db->query($sql);
					
					if($query->num_rows()>=3)
					{
						$this->db->trans_rollback();
						json_array2(30000,"抱歉：当前时间节点课程已经发布3条，无法继续发布","");
					}
					else
					{
						//判断当前时间是否在其他大厅发布过这个课程
						$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`!='$room_id' and `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' limit 1");
						
						if($query->num_rows()>0)
						{
							$this->db->trans_rollback();
							json_array2(30000,"抱歉：您发布的时间在其他大厅同时有课，无法继续发布","");		
						}	
						else
						{
							//判断当前时间段是否有发布过同一class_id的课程
							$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `node`='".$nodes."' and `room_id`='$room_id' and `class_id`='$class_id' limit 1");
							
							if($query->num_rows()>0)
							{
								$this->db->trans_rollback();
								json_array2(30000,"抱歉：当前时间段已经发布有您选择的课程类型，请更换其他课程类型进行发布","");	
							}
							else
							{
								
								require FCPATH."config/sys.inc.php";
								
								//判断每日最多发布数量
								$query=$this->db->query("select `id` from `dg_tearch_plans` where `date`='$days' and `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' limit 4");
								
								if($query->num_rows()>=$_sys_inc["class_insert_count_day"])
								{
									$this->db->trans_rollback();
									json_array2(30000,"抱歉：您每日最多能发布".$_sys_inc["class_insert_count_day"]."节课程","");		
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
										json_array2(30000,"抱歉：您的授课信息读取失败，系统拒绝您的操作，请您及时联系官方客服进行解决","");
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
										json_array2(30000,"抱歉：系统高低峰时间配置有误，请您稍后再尝试发布","");	
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
										json_array2(30000,"抱歉：您的授课信息读取失败，系统拒绝您的操作，请您及时联系官方客服进行解决","");	
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
									
									$this->db->insert("tearch_plans",$_array);	
									
									if($this->db->trans_status()==true){
										$this->db->trans_commit();
										json_array2("10000","发布成功","");
									}else{
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
					json_array2(30000,"抱歉：没有找到对应操课厅或课程信息","");		
				}
				
			}
			else
			{
				$this->db->trans_rollback();
				json_array2(30000,"抱歉：发布时间节点有误","");	
			}
		}
		
	}
	
	//