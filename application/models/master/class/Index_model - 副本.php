<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Index_model extends CI_model
	{
	
		function __construct()
		{
			parent::__construct();
			
		}
		
		//我的私刻-日期
		public function my_class_aa_date()
		{
			
			if(isset($_REQUEST["days"]))
			{
				$days=trim($_REQUEST["days"]);
				$day_arr=$this->create_week_normal($days);
			}	
			else
			{
				$day_arr=$this->create_week_normal();	
			}
			json_array2(10000,"成功",$day_arr);
			
		}
		
		//读取对应的一周时间
		private function create_week_normal($days=null)
		{
			if($days=="")
			{
				$times=time();	
			}
			else
			{
				$times=strtotime($days." 10:00:00");
			}
			$time=time()-$times;
			$t=date("w",$times);
			
			$arr=array();
			$_array=array("周一","周二","周三","周四","周五","周六","周日");
			$a=0;
			if($t==0)
			{
				$t=7;	
			}
			for($i=0-$t;$i<7-$t;$i++)
			{
				$times=$i*3600*24-$time+3600*24;
				$arr[$a]["day"]=date("Y-m-d",time()+$times);
				$arr[$a]["week"]=$_array[$a];
				$arr[$a]["next_day"]=date("Y-m-d",time()+$times+3600*24);
				$arr[$a]["last_day"]=date("Y-m-d",time()+$times-3600*24);
				$a++;
			}	
			return $arr;				
		}
		
		//我的私刻详情-新
		public function my_class_aa_item($rs,$id)
		{
			$query=$this->db->query("select `id`,`date`,`node`,`money`,`start_time`,`end_time`,`state` from `dg_tearch_plan_list` where `tid`='".$rs["id"]."' and `id`='$id'");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$querys=$this->db->query("select `u`.`avatar`,`u`.`id` as `uid`,`u`.`mobile`,`u`.`nickname`,`u`.`brithday` from `dg_orders` as `o` inner join `dg_user` as `u` on `o`.`uid`=`u`.`id` where `o`.`state` in (1,2,3,6) limit 1");
				$result["buy"]=1;
				$result["avatar"]="";
				$result["uid"]="";
				$result["mobile"]="";
				$result["nickname"]="";
				$result["brithday"]="";
				if($querys->num_rows()>0)
				{
					$results=$querys->row_array();
					$result["buy"]=2;
					$result=array_merge($result,$results);//合并数据
					if($result["avatar"]=="")
					{
						require FCPATH."config/img.inc.php";
						$result["avatar"]=$img_inc["avatar"];	
					}
					
					if($result["brithday"]!="" && $result["brithday"]!="0000-00-00")
					{
						$y=date("Y");
						$arr=explode("-",$result["brithday"]);
						$result["brithday"]=$y-$arr[0];	
					}
					else
					{
						$result["brithday"]="";	
					}
				}
				json_array2(10000,"成功",$result);
			}
			else
			{
				json_array2(30000,"抱歉：没有找到对应的课程信息","");	
			}
		}
		
		//我的私课-新
		public function my_class_aa($rs)
		{
			if(isset($_REQUEST["days"]))
			{
				$days=trim($_REQUEST["days"]);
				$day_arr=explode(",",$days);	
			}	
			else
			{
				$day_arr=$this->create_week();	
			}
			$_array=array();
			$i=0;
			foreach($day_arr as $k=>$v)
			{
				$_array[$i]["day"]=$v;
				$_array[$i]["class"]=$this->get_class_aa($v,$rs["id"]);
				$i++;	
			}
			//print_r($_array);
			json_array2(10000,"成功",$_array);
		}
		
		//根据日期获取对应时间点的课程信息
		private function get_class_aa($id,$uid)
		{
			$_array=array();
			$i=0;
			for($a=7;$a<=21;$a++)
			{
				$b=$a+1;
				$c=$a;
				if($c<10)
				{
					$c="0".$c.":00";	
				}
				else
				{
					$c=$c.":00";	
				}
				if($b<10)
				{
					$b="0".$b.":00";	
				}
				else
				{
					$b=$b.":00";	
				}
				$_array[$i]["node1"]=$c."-".$b;
				
				$w=$a+1;
				
				$_array[$i]["node"]=$a."-".$w."时";

				
				$_array[$i]["message"]=$this->get_class_aa_user($id,$_array[$i]["node1"],$uid);	
				
				$i++;
			}
			
			return $_array;
		}
		
		//查询对应某个点的信息
		private function get_class_aa_user($id,$node,$uid)
		{
			$query=$this->db->query("select `id`,`state`,`end_time` from `dg_tearch_plan_list` where `date`='$id' and `node`='$node' and `tid_index`='".right_index($uid)."' and `tid`='$uid' limit 1");
			$_array=array(
				"class_id"=>"",
				"user"=>"",
				"mobile"=>"",
				"over"=>"",
			);
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$_array["class_id"]=$result["id"];
				$_array["user"]=$this->read_class_aa_user($result["id"],$mobile);
				$_array["mobile"]=$mobile;
				$_array["over"]=$result["end_time"];
			}	
			return $_array;
		}
		
		//根据课程查看预约用户信息
		private function read_class_aa_user($id,&$mobile)
		{
			$query=$this->db->query("select `o`.`id`,`u`.`mobile`,`u`.`nickname` from `dg_orders` as `o` inner join `dg_user` as `u` on `o`.`uid`=`u`.`id` where `o`.`class_id`='$id' and `o`.`state` in (1,2,3,6) limit 1");
			if($query->num_rows()<=0)
			{
				$mobile="";
				return "";	
			}
			else
			{
				$result=$query->row_array();
				$mobile=$result["mobile"];
				return $result["nickname"];
			}
		}
		
		//根据当前天数创建一周时间值
		private function create_week()
		{
			$t=date("w");
			if($t==0)
			{
				$t=7;	
			}			
			$arr=array();
			for($i=0-$t;$i<7-$t;$i++)
			{
				$times=$i*3600*24;
				$arr[]=date("Y-m-d",time()+$times);
			}	
			return $arr;
		}
		
		//我的私课
		public function my_class_a($rs)
		{
			if($rs["act"]!=1)
			{
				json_array2(30000,"抱歉：您不是私课教练，无法操作当前信息","");	
			}
			
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			
			if(isset($_REQUEST["models"]) && trim($_REQUEST["models"])==2)
			{
				//当月课程	
				//$date=date("Y-m");
				//$sql="select `id`,`date`,`node`,`money`,`start_time`,`state` from `dg_tearch_plan_list` where `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' and `date` like '%".$date."%' order by `start_time` desc";
				$start_time=strtotime(date("Y-m-d")." 00:00:00");
				$end_time=strtotime(date("Y-m-d")." 23:59:59")+3600*24*14;
				$sql="select `id`,`date`,`node`,`money`,`start_time`,`state` from `dg_tearch_plan_list` where `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' and `start_time`>='$start_time' and `start_time`<='$end_time' order by `start_time` desc";
			}
			else
			{
				$sql="select `id`,`date`,`node`,`money`,`start_time`,`state` from `dg_tearch_plan_list` where `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' order by `start_time` desc";		
			}
			
			//echo $sql;
			
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);	
			
			$arrays=array();
			$i=0;
			foreach($query->result_array() as $array)
			{
				$arrays[$i]=$array;
				$arrays[$i]["date"]=date("m-d",$arrays[$i]["start_time"]);
				unset($arrays[$i]["start_time"]);
				$i++;
			}
			json_array2(10000,"成功",$arrays);	
		}
		
		//我的私课详情
		public function my_class_a_item($rs,$id)
		{
			$query=$this->db->query("select `id`,`date`,`node`,`money`,`start_time`,`state` from `dg_tearch_plan_list` where `tid_index`='".right_index($rs["id"])."' and `tid`='".$rs["id"]."' and `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["state"]==3 || $result["state"]==6)
				{
					$querys=$this->db->query("select `nickname`,`avatar` from `dg_orders` as `o` left join `dg_user` as `u` on `o`.`uid`=`u`.`id` where `o`.`class_id`='$id' and (`o`.`state`='1' or `o`.`state`='2' or `o`.`state`='3')");
					if($querys->num_rows()>0)
					{
						$results=$querys->row_array();
						$result=array_merge($result,$results);
						if($result["avatar"]=="")
						{
							require FCPATH."config/img.inc.php";
							$result["user_avatar"]=$img_inc["avatar"];		
						}
					}	
					else
					{
						$results["nickname"]="";
						$results["user_avatar"]="";
						$result=array_merge($result,$results);
						if($result["user_avatar"]=="")
						{
							require FCPATH."config/img.inc.php";
							$result["user_avatar"]=$img_inc["avatar"];		
						}
						//json_array2(30000,"抱歉：信息读取失败","");	
					}
				}
				else
				{
					$result["user_nickname"]="";
					$result["user_avatar"]="";	
				}
				$result["date"]=date("m-d",$result["start_time"]);
				unset($result["start_time"]);
				json_array2(10000,"成功",$result);	
			}	
			else
			{
				json_array2(30000,"抱歉：没有找到对应课程信息","");		
			}
		}
		
		//我的操课-新
		public function my_class_bb($rs)
		{
			if($rs["act"]!=2)
			{
				json_array2(30000,"抱歉：您不是操课教练，无法操作当前信息","");	
			}
			isset($_REQUEST["day"]) && trim($_REQUEST["day"])!=""?$day=trim($_REQUEST["day"]):$day=date("Y-m-d");
			$sql="select `p`.`id`,`p`.`date`,`p`.`room_id`,`p`.`room_name`,`p`.`start_time`,`p`.`end_time`,`p`.`sale`,`p`.`min`,`p`.`max`,`p`.`loads`,`p`.`state`,`c`.`bg_file` as `file`,`c`.`name` as `class_name` from `dg_tearch_plans` as `p` left join `dg_class` as `c` on `p`.`class_id`=`c`.`id` where `p`.`tid`='".$rs["id"]."' and `tid_index`='".right_index($rs["id"])."' and `date`='$day' order by `end_time` desc";
			
			$query=$this->db->query($sql);
			
			$array=array();
			
			$i=0;
			
			foreach($query->result_array() as $arr)
			{
				$array[$i]=$arr;
				$array[$i]["realname"]=$rs["realname"];
				$i++;
			}
			
			json_array2(10000,"成功",$array);
			
		}
		
		//我的操课参与人员信息-新
		public function my_class_bb_join_item($rs,$id)
		{
			$query=$this->db->query("select `id` from `dg_tearch_plans` where `tid`='".$rs["id"]."' and `id`='$id'");
			if($query->num_rows()>0)
			{
				$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
				$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;
				$sql="select `u`.`id` as `uid`,`u`.`nickname`,`u`.`avatar`,`u`.`mobile`,`u`.`gender`,`u`.`brithday` from `dg_user` as `u` inner join `dg_orders` as `o` on `u`.`id`=`o`.`id` where `o`.`act`='2' and `o`.`pid`='$id' and `o`.`state` in (1,2,3) order by `o`.`id` desc ";
				$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
				$query=$this->db->query($sql);
				$array=array();
				$i=0;
				require FCPATH."config/img.inc.php";
				foreach($query->result_array() as $arr)
				{
					$array[$i]=$arr;
					if($array[$i]["avatar"]=="")
					{
						$array[$i]["avatar"]=$img_inc["avatar"];	
					}
					
					if($array[$i]["brithday"]!="" && $array[$i]["brithday"]!="0000-00-00")
					{
						$y=date("Y");
						$arrs=explode("-",$array[$i]["brithday"]);
						//print_r($arrs);
						$array[$i]["brithday"]=$y-$arrs[0];	
					}
					else
					{
						$array[$i]["brithday"]="";	
					}
					$i++;
				}			
				json_array2(10000,"成功",$array);
			}	
			else
			{
				json_array2(30000,"抱歉：没有找到对应课程信息","");	
			}
		}
		
		//我的操课
		public function my_class_b($rs)
		{
			if($rs["act"]!=2)
			{
				json_array2(30000,"抱歉：您不是操课教练，无法操作当前信息","");	
			}
			
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			
			if(isset($_REQUEST["state"]) && trim($_REQUEST["state"])==2)
			{
				//已成立课程
				$sql="select `p`.`id`,`p`.`date`,`p`.`node`,`p`.`room_name`,`p`.`state`,`p`.`min`,`p`.`max`,`p`.`loads`,`p`.`class_name`,`c`.`bg_file` as `files`,`p`.`start_time`,`p`.`sale` from `dg_tearch_plans` as `p` left join `dg_class` as `c` on `p`.`class_id`=`c`.`id` where `p`.`tid_index`='".right_index($rs["id"])."' and `p`.`tid`='".$rs["id"]."' and (`p`.`state`='2' or `p`.`state`='3' or `p`.`state`='5') order by `p`.`start_time` desc";	
			}
			elseif(isset($_REQUEST["state"]) && trim($_REQUEST["state"])==3)
			{
				//已取消课程
				$sql="select `p`.`id`,`p`.`date`,`p`.`node`,`p`.`room_name`,`p`.`state`,`p`.`min`,`p`.`max`,`p`.`loads`,`p`.`class_name`,`c`.`bg_file` as `files`,`p`.`start_time`,`p`.`sale` from `dg_tearch_plans` as `p` left join `dg_class` as `c` on `p`.`class_id`=`c`.`id` where `p`.`tid_index`='".right_index($rs["id"])."' and `p`.`tid`='".$rs["id"]."' and (`p`.`state`='4') order by `p`.`start_time` desc";	
			}
			else
			{
				//待成立课程
				$sql="select `p`.`id`,`p`.`date`,`p`.`node`,`p`.`room_name`,`p`.`state`,`p`.`min`,`p`.`max`,`p`.`loads`,`p`.`class_name`,`c`.`bg_file` as `files`,`p`.`start_time`,`p`.`sale` from `dg_tearch_plans` as `p` left join `dg_class` as `c` on `p`.`class_id`=`c`.`id` where `p`.`tid_index`='".right_index($rs["id"])."' and `p`.`tid`='".$rs["id"]."' and `p`.`state`='1' order by `p`.`start_time` desc";	
			}	
			
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			//echo $sql;
			
			$query=$this->db->query($sql);	
			
			$arrays=array();
			$i=0;
			foreach($query->result_array() as $array)
			{
				$arrays[$i]=$array;
				$arrays[$i]["date"]=date("m-d",$arrays[$i]["start_time"]);
				unset($arrays[$i]["start_time"]);
				$i++;
			}
			json_array2(10000,"成功",$arrays);				
					
		}
		
		//我的操课详情
		public function my_class_b_item($rs,$id)
		{
			$sql="select `p`.`id`,`p`.`date`,`p`.`node`,`p`.`room_name`,`p`.`state`,`p`.`min`,`p`.`max`,`p`.`loads`,`p`.`class_name`,`c`.`bg_file` as `files`,`p`.`start_time`,`p`.`sale` from `dg_tearch_plans` as `p` left join `dg_class` as `c` on `p`.`class_id`=`c`.`id` where `p`.`tid_index`='".right_index($rs["id"])."' and `p`.`tid`='".$rs["id"]."' and `p`.`id`='$id' order by `p`.`id` desc";
			$query=$this->db->query($sql);
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$result["date"]=date("m-d",$result["start_time"]);
				unset($result["start_time"]);
				json_array2(10000,"成功",$result);	
			}	
			else
			{
				json_array2(30000,"抱歉：没有找到对应课程信息","");		
			}
		}
		
		//我的操课用户参与详情
		public function my_class_b_joins($rs,$id)
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;
			
			$sql="select `p`.`id` from `dg_tearch_plans` as `p` left join `dg_class` as `c` on `p`.`class_id`=`c`.`id` where `p`.`tid_index`='".right_index($rs["id"])."' and `p`.`tid`='".$rs["id"]."' and `p`.`id`='$id' order by `p`.`id` desc";
			$query=$this->db->query($sql);
			if($query->num_rows()>0)
			{
				$sql="select `u`.`nickname`,`u`.`avatar`,`o`.`state` from `dg_orders` as `o` left join `dg_user` as `u` on `o`.`uid`=`u`.`id` where `o`.`pid`='$id' order by `o`.`id` desc";
				//echo $sql;
				$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
				$query=$this->db->query($sql);
				$arrays=array();
				$i=0;
				require FCPATH."config/img.inc.php";
				foreach($query->result_array() as $array)
				{
					$arrays[$i]=$array;
					if($arrays[$i]["avatar"]=="")
					{
						$arrays[$i]["avatar"]=$img_inc["avatar"];	
					}
					$i++;
				}
				json_array2(10000,"成功",$arrays);
			}
			else
			{
				json_array2(30000,"抱歉：没有找到对应课程信息","");
			}		
		}
		
	}
	
	
	