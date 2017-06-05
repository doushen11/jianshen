<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Index_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//读取操课教练个人资料
		public function item($id)
		{
			//echo 100;die();
			set_time_limit(0);
			$query=$this->db->query("select `id`,`realname`,`avatar`,`bg`,`score`,`level`,`focus_text`,`money_desc`,`birthday`,`desc` from `dg_teacher` where `id`='$id' and `act`='2' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				//开始加载关注信息
				$result["focus_state"]=0;
				if($result["focus_text"]!="" && is_fulls("token"))
				{
					$rs=$this->get_users(trim($_REQUEST["token"]),"id");
					$arr=json_decode($result["focus_text"],true);
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
				//计算对应的课程高峰期和低峰期信息
				if(trim($result["money_desc"])=="")
				{
					json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
				}
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
				//高峰、低峰时间计算结束
				$arr=json_decode($result["money_desc"],true);
				//$ids="";
				$class_name="";
				for($a=0;$a<count($arr);$a++)
				{
					//$ids.=",".$arr[$a]["class"];	
					$querys=$this->db->query("select `id`,`name` from `dg_class` where `id`='".$arr[$a]["class"]."'");
					$c_name="";
					if($querys->num_rows()>0)
					{
						$arrays=$querys->row_array();
						$class_name.=",".$arrays["name"];
						$c_name=$arrays["name"];
					}
					$arr[$a]["class_name"]=trim($c_name,",");
					$arr[$a]["time_peak"]=$h_time;
					$arr[$a]["time_slack"]=$l_time;
					unset($arr[$a]["alls"]);
				}
				$result["class_name"]=trim($class_name,",");
				unset($result["focus_text"]);
				unset($result["money_desc"]);
				//print_r($arr);
				//$ids=trim($ids,",");
				//echo $ids;
				//$querys=$this->db->query("select `id`,`name` from `dg_class` where `id` in ($ids)");
				//$class_name="";
				//foreach($querys->result_array() as $arrays)
				//{
				//	$class_name.=",".$arrays["name"];
				//}
				//$class_name=trim($class_name,",");
				//$result["class_name"]=$class_name;		
				//echo $class_name;
				//print_r($arr);
				$result["classs"]=$arr;
				json_array2("10000","成功",$result);
			}
			else
			{
				error_show();	
			}
		}
		
		//组合操课计划
		/*private function create_plan_list($querys,$id)
		{
			$array=array();$i=0;
			foreach($querys->result_array() as $arrays)
			{
				$array[$i]=$arrays;
				if($array[$i]["tid"]!=$id)
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
		
		//组合操课计划新
		public function create_plan_list($id,$data)
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
				$array[$a]["id"]=$id;
				
				//查询数据库是否有当前的数据
				$sql="select `id`,`class_id`,`class_name`,`state`,`tid`,`tid_name`,`room_name`,`room_id` from `dg_tearch_plans` where `tid_index`='".right_index($id)."' and `tid`='$id' and `node`='".$array[$a]["node"]."' and `date`='".$data."' limit 1";
				$query=$this->db->query($sql);
				$array[$a]["class_id"]="";
				$array[$a]["class_name"]="";
				$array[$a]["state"]="";
				$array[$a]["tid"]="";
				$array[$a]["tid_name"]="";
				$array[$a]["room_id"]="";
				$array[$a]["room_name"]="";
				$array[$a]["id"]="";
				if($query->num_rows()>0)
				{
					$result=$query->row_array();
					$array[$a]=array_merge($array[$a],$result);
				}
				
				$a++;	
			}	
			return $array;
		}
		
		//读取操课教练的计划详情
		public function plan($id)
		{
			$query=$this->db->query("select `id` from `dg_teacher` where `id`='$id' and `act`='2' limit 1");
			if($query->num_rows()>0)
			{		
				$week[0]["time"]=date("Y-m-d");
				for($i=0;$i<=6;$i++)
				{
					$time=time()+(3600*24)*$i;
					$week[$i]["time"]=date("Y-m-d",$time);
					//print_r($week[$i]["time"]);
					//$querys=$this->db->query("select `node`,`id`,`class_id`,`class_name`,`state`,`tid` from `dg_tearch_plans` where `date`='".$week[$i]["time"]."' order by `id` asc");
					$week[$i]["lists"]=$this->create_plan_list($id,$week[$i]["time"]);
				}	
				json_array2("10000","成功",$week);
			}
			else
			{
				error_show();	
			}
		}
		
		//获取操课预约详情
		public function subscribe($id)
		{   
		    //log_message('级别','消');  追踪操课和操课所有者不匹配的问题;
		    log_message('1',' ddd'.$id." 本id");
		    
			$query=$this->db->query("select * from `dg_tearch_plans` where `id`='{$id}'  limit 1");
			if($query->num_rows()>0)
			{
			    $result=$query->row_array();
			    
			    $start_time=$result["start_time"] - 3600*4;
			    if($start_time<=time())
			    {
			       
			       //json_array2("30000","抱歉：开课前四小时内不允许预约，请您稍后再试!","");
			       //开课前四个小时内，如果约课人数未达到最低约课数，就解散本课；已约学生的金额全部返还；
			       if ($result['loads'] < $result['min']) {
			          
			           $order_list   =   $this->db->query("select id,act,money,uid,uid_index,tid,class_id,time,state,returns from dg_orders where pid = {$result['id']}")->result_array();
			           //如果有人购买
			           if ($order_list) {
			               foreach ($order_list as &$value) {
			                   $this->db->query("update `dg_user` set `balance`=`balance`+'".$value["money"]."' where `id`='".$value["uid"]."'");
			                   $tmp_returns= $value['returns2'] = json_decode($value['returns'],true);
			                   foreach ($tmp_returns as $v) {
			                       $this->db->query("update `dg_pay_order` set `money_remaining`=`money_remaining`+'{$v['money']}' where `id`='{$v['id']}'");
			                   }
			                   //减少本课报名人数
			                   $this->db->query("update `dg_tearch_plans` set  `loads`=`loads`-'1' where `id`='{$id}'");
			                   //设置关联订单状态为"取消订单"
			                   $this->db->query("update dg_orders set state = '4' where id = {$value['id']} limit 1");
			               }			               
			           }
			           
			           $this->db->query("update `dg_tearch_plans` set state = '4' where `id`='{$id}'");
			           
			         json_array2("30000","抱歉：本课程已失效！","");
			       }
			       
			    } 

				if($result["start_time"]<=time())
				{
					json_array2("30000","抱歉：当前课程开始时间已经超时，无法预约报名！","");
				}
				
				if($result["state"]!=1 && $result["state"]!=2)
				{
					if($result["state"]==0)
					{
						json_array2("30000","抱歉：当前课程尚未开启，无法预约报名！","");
					}
					elseif($result["state"]==3)
					{
						json_array2("30000","抱歉：当前课程已满，无法预约报名！","");	
					}
					json_array2("30000","抱歉：当前课程已失效，无法预约报名！");
				}
				
				$querys=$this->db->query("select `bg_file` from `dg_class` where `id`='".$result["class_id"]."'");
				
				if($querys->num_rows()<=0)
				{
					json_array2("30000","抱歉：当前课程信息已失效，无法预约报名！");
				}
				$results=$querys->row_array();
				$result["img"]=$results["bg_file"];
				$result["date"]=date("m月d日",$result["start_time"]);
				unset($result["times"]);
				unset($result["start_time"]);
				unset($result["end_time"]);
				json_array2("10000","成功",$result);
			}
			else
			{
				error_show();		
			}
		}
		
	}