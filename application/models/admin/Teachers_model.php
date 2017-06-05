<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Teachers_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		//私教打款操作
		public function indexs_draw_sub()
		{
			$this->db->trans_strict(false);
			$this->db->trans_begin();	
			$id=intval($this->uri->segment(4));	
$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='1'");
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
					$this->db->query("update `dg_teacher` set `balance`=`balance`-'$moneys',`balance_time`='".time()."' where `id`='$id'");//更新账户信息
					$_array=array(
						"tid_index"=>right_index($id),
						"tid"=>$id,
						"money"=>$moneys,
						"money_in"=>$moneys,
						"models"=>2,
						"act"=>1,
						"text"=>json_encode(array("class_node_id"=>"","class_id"=>"","class_img"=>"","class_name"=>"","class_date"=>"","class_node"=>"","desc"=>$desc)),
						"time"=>time(),
					);	
					$this->db->insert("teacher_money",$_array);
					//添加一条提现纪录
					
					//减去对应的节点记录
					if($this->db->trans_status()==true){
						//$this->db->trans_commit();
						$this->db->trans_commit();
						ajaxs(10000,"打款操作成功");
					}else{
						$this->db->trans_rollback();
						ajaxs(30000,"网络连接失败！");
					}
					
				}
			}	
			else
			{
				ajaxs(30000,"抱歉：教练信息读取失败！");
			}			
		}
		
		//操课打款处理
		public function homes_draw_sub()
		{
			$this->db->trans_strict(false);
			$this->db->trans_begin();	
			$id=intval($this->uri->segment(4));	
			$count=intval($this->uri->segment(5));	
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='2'");
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
					$this->db->query("update `dg_teacher` set `balance`=`balance`-'$moneys',`balance_time`='".time()."',`balance_count`=`balance_count`-'$count' where `id`='$id'");//更新账户信息
					$_array=array(
						"tid_index"=>right_index($id),
						"tid"=>$id,
						"money"=>$moneys,
						"money_in"=>$moneys,
						"models"=>2,
						"act"=>2,
						"text"=>json_encode(array("class_node_id"=>"","class_id"=>"","class_img"=>"","class_name"=>"","class_date"=>"","class_node"=>"","desc"=>$desc)),
						"time"=>time(),
					);	
					$this->db->insert("teacher_money",$_array);
					//添加一条提现纪录
					
					//减去对应的节点记录
					if($this->db->trans_status()==true){
						//$this->db->trans_commit();
						$this->db->trans_commit();
						ajaxs(10000,"打款操作成功");
					}else{
						$this->db->trans_rollback();
						ajaxs(30000,"网络连接失败！");
					}
					
				}
			}	
			else
			{
				ajaxs(30000,"抱歉：教练信息读取失败！");
			}				
		}
		
		//删除私教教练
		public function index_dels($id)
		{
			$query=$this->db->query("select `id` from `dg_tearch_plan_list` where `tid` in (".$id.") limit 1");
			if($query->num_rows()>0)
			{
				ajaxs("30000","抱歉:您选择的私课教练里面含有已发布过课程的教练，无法删除");	
			}	
			else
			{
				$this->db->query("delete from `dg_teacher` where `id` in (".$id.")");
				$this->db->query("delete from `dg_doors` where `uid` in (".$id.") and `act`='2'");
				ajaxs("10000","删除成功");	
			}
		}
		
		//删除操课教练
		public function home_dels($id)
		{
			$query=$this->db->query("select `id` from `dg_tearch_plans` where `tid` in (".$id.") limit 1");
			if($query->num_rows()>0)
			{
				ajaxs("30000","抱歉:您选择的操课教练里面含有已发布过课程的教练，无法删除");	
			}	
			else
			{
				$this->db->query("delete from `dg_teacher` where `id` in (".$id.")");
				$this->db->query("delete from `dg_doors` where `uid` in (".$id.") and `act`='2'");
				ajaxs("10000","删除成功");	
			}
		}
		
		//设置教练状态
		public function changes($id)
		{
			$query=$this->db->query("select `state` from `dg_teacher` where `id`='$id' limit 1");
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
				$this->db->query("update `dg_teacher` set `state`='$st' where `id`='$id'");
				ajaxs(10000,$st);die();
			}
			else
			{
				ajaxs(30000,"没有找到对应用户信息");die();	
			}	
		}
		
		//添加私课教练
		public function index_inserts()
		{
			$mobile=$this->input->post("mobile");
			$query=$this->db->query("select `id` from `dg_teacher` where `mobile`='$mobile' limit 1");	
			if($query->num_rows()>0)
			{
				ajaxs(30000,"当前手机号已经被注册过");die();
			}
			else
			{
				$money_desc=array(
					"money_peak"=>$this->input->post("money_peak"),
					"money_slack"=>$this->input->post("money_slack"),
					"money_peak_sys"=>$this->input->post("money_peak_sys"),
					"money_slack_sys"=>$this->input->post("money_slack_sys"),
				);
				$money_desc=json_encode($money_desc);
				$_array=array(
					"mobile"=>$mobile,
					"passwd"=>sha1($this->input->post("passwd")),
					"realname"=>$this->input->post("realname"),
					"gender"=>$this->input->post("gender"),
					"avatar"=>$this->input->post("avatar"),
					"bg"=>$this->input->post("bg"),
					"birthday"=>$this->input->post("brithday"),
					"level"=>$this->input->post("level"),
					"desc"=>$this->input->post("desc"),
					"contents"=>$this->input->post("contents"),
					"money_desc"=>$money_desc,
					"act"=>1,
					"state"=>1,
					"reg_time"=>time(),
					"login_time"=>time(),
					"login_ip"=>ip2long(get_ip()),
				);
				if($this->db->insert("teacher",$_array))
				{
					ajaxs(10000,"添加成功");	
				}
				else
				{
					ajaxs(30000,"网络连接失败");	
				}
			}
		}
		
		//修改私课教练
		public function index_updates($id)
		{
			$mobile=$this->input->post("mobile");
			$passwd=$this->input->post("passwd");
			$query=$this->db->query("select `id` from `dg_teacher` where `mobile`='$mobile'  and `id`!='$id' limit 1");	
			if($query->num_rows()>0)
			{
				ajaxs(30000,"当前手机号已经被注册过");die();
			}
			else
			{
				$money_desc=array(
					"money_peak"=>$this->input->post("money_peak"),
					"money_slack"=>$this->input->post("money_slack"),
					"money_peak_sys"=>$this->input->post("money_peak_sys"),
					"money_slack_sys"=>$this->input->post("money_slack_sys"),
				);
				$money_desc=json_encode($money_desc);
				$_array=array(
					"mobile"=>$mobile,
					//"passwd"=>sha1($this->input->post("passwd")),
					"realname"=>$this->input->post("realname"),
					"gender"=>$this->input->post("gender"),
					"avatar"=>$this->input->post("avatar"),
					"bg"=>$this->input->post("bg"),
					"birthday"=>$this->input->post("brithday"),
					"level"=>$this->input->post("level"),
					"desc"=>$this->input->post("desc"),
					"contents"=>$this->input->post("contents"),
					"money_desc"=>$money_desc,
				);
				if($passwd!="")
				{
					$_array["passwd"]=sha1($passwd);	
				}
				if($this->db->update("teacher",$_array,array("id"=>$id)))
				{
					ajaxs(10000,"修改成功");	
				}
				else
				{
					ajaxs(30000,"网络连接失败");	
				}
			}
		}
		
		//添加操课教练信息
		public function home_inserts()
		{
			$mobile=$this->input->post("mobile");
			$sphinx=$this->input->post("sphinx");
			$query=$this->db->query("select `id` from `dg_teacher` where `mobile`='$mobile' limit 1");	
			if($query->num_rows()>0)
			{
				ajaxs(30000,"当前手机号已经被注册过");die();
			}
			else
			{
				
				$arrs=explode("|_|",$sphinx);
				$jsons=array();
				$i=0;

				foreach($arrs as $k=>$v)
				{
					if(trim($v)!="")
					{
						$a=explode("{syx}",$v);
						$jsons[$i]["class"]=$a[0];
						$jsons[$i]["money_peak"]=$a[1];
						$jsons[$i]["money_slack"]=$a[3];
						$jsons[$i]["money_peak_sys"]=$a[2];
						$jsons[$i]["money_slack_sys"]=$a[4];
						
						$jsons[$i]["alls"][0]["room_id"]=$a[5];
						$jsons[$i]["alls"][0]["class_min"]=$a[6];
						$jsons[$i]["alls"][0]["class_min"]=$a[7];
						
						$jsons[$i]["alls"][1]["room_id"]=$a[8];
						$jsons[$i]["alls"][1]["class_min"]=$a[9];
						$jsons[$i]["alls"][1]["class_min"]=$a[10];
						//$jsons[$i]["class_min"]=$a[5];
						//$jsons[$i]["class_max"]=$a[6];
						$i++;	
					}	
				}
				$money_desc=json_encode($jsons);
				$_array=array(
					"mobile"=>$mobile,
					"passwd"=>sha1($this->input->post("passwd")),
					"realname"=>$this->input->post("realname"),
					"gender"=>$this->input->post("gender"),
					"avatar"=>$this->input->post("avatar"),
					"bg"=>$this->input->post("bg"),
					"birthday"=>$this->input->post("brithday"),
					"level"=>$this->input->post("level"),
					"desc"=>$this->input->post("desc"),
					"contents"=>$this->input->post("contents"),
					"money_desc"=>$money_desc,
					"act"=>2,
					"state"=>1,
					"reg_time"=>time(),
					"login_time"=>time(),
					"login_ip"=>ip2long(get_ip()),
				);
				if($this->db->insert("teacher",$_array))
				{
					ajaxs(10000,"添加成功");	
				}
				else
				{
					ajaxs(30000,"网络连接失败");	
				}
			}				
		}
		
		//修改操课
		public function home_updates($id)
		{
			$mobile=$this->input->post("mobile");
			$passwd=$this->input->post("passwd");
			$sphinx=$this->input->post("sphinx");
			$query=$this->db->query("select `id` from `dg_teacher` where `mobile`='$mobile'  and `id`!='$id' limit 1");	
			if($query->num_rows()>0)
			{
				ajaxs(30000,"当前手机号已经被注册过");die();
			}
			else
			{
				$arrs=explode("|_|",$sphinx);
				$jsons=array();
				$i=0;
				foreach($arrs as $k=>$v)
				{
					if(trim($v)!="")
					{
						$a=explode("{syx}",$v);
						$jsons[$i]["class"]=$a[0];
						$jsons[$i]["money_peak"]=$a[1];
						$jsons[$i]["money_slack"]=$a[3];
						$jsons[$i]["money_peak_sys"]=$a[2];
						$jsons[$i]["money_slack_sys"]=$a[4];
						$jsons[$i]["alls"][0]["room_id"]=$a[5];
						$jsons[$i]["alls"][0]["class_min"]=$a[6];
						$jsons[$i]["alls"][0]["class_max"]=$a[7];
						
						$jsons[$i]["alls"][1]["room_id"]=$a[8];
						$jsons[$i]["alls"][1]["class_min"]=$a[9];
						$jsons[$i]["alls"][1]["class_max"]=$a[10];
						$i++;	
					}	
				}
				$money_desc=json_encode($jsons);
				$_array=array(
					"mobile"=>$mobile,
					//"passwd"=>sha1($this->input->post("passwd")),
					"realname"=>$this->input->post("realname"),
					"gender"=>$this->input->post("gender"),
					"avatar"=>$this->input->post("avatar"),
					"bg"=>$this->input->post("bg"),
					"birthday"=>$this->input->post("brithday"),
					"level"=>$this->input->post("level"),
					"desc"=>$this->input->post("desc"),
					"contents"=>$this->input->post("contents"),
					"money_desc"=>$money_desc,
				);
				if($passwd!="")
				{
					$_array["passwd"]=sha1($passwd);	
				}
				if($this->db->update("teacher",$_array,array("id"=>$id)))
				{
					ajaxs(10000,"修改成功");	
				}
				else
				{
					ajaxs(30000,"网络连接失败");	
				}
			}				
		}
		
	}