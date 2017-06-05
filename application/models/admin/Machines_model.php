<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Machines_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		public function ups($id)
		{
			$sql="select * from `dg_machine_act` order by `sort` asc";
			$query=$this->db->query($sql);
			if($query->num_rows()>0){
				$result=$query->row_array();
				if($result["id"]==$id){
					//第一个id就是所选排序第一id，无需上移
					return "nothing";
				}else{
					$i=0;
					$nid="";
					foreach($query->result_array() as $array){
						if($array["id"]!=$id){
							$nid=$array["id"];
							$this->db->query("update `dg_machine_act` set `sort`='$i' where `id`='".$array["id"]."'");	
						}else{
							//找到了对应的id信息了，前后的sort进行处理
							$i1=$i-1;
							$this->db->query("update `dg_machine_act` set `sort`='$i1' where `id`='".$array["id"]."'");
							$this->db->query("update `dg_machine_act` set `sort`='$i' where `id`='".$nid."'");	
							$nid=$array["id"];
						}
						$i++;	
					}
					return "success";	
				}
			}		
		}
		
		public function downs($id)
		{
			$sql="select * from `dg_machine_act` order by `sort` desc";
			$query=$this->db->query($sql);
			if($query->num_rows()>0){
				$result=$query->row_array();
				if($result["id"]==$id){
					//第一个id就是所选排序第一id，无需上移
					return "nothing";
				}else{
					$i=$query->num_rows();
					$nid="";
					foreach($query->result_array() as $array){
						if($array["id"]!=$id){
							$nid=$array["id"];
							$this->db->query("update `dg_machine_act` set `sort`='$i' where `id`='".$array["id"]."'");	
						}else{
							//找到了对应的id信息了，前后的sort进行处理
							$i1=$i+1;
							$this->db->query("update `dg_machine_act` set `sort`='$i1' where `id`='".$array["id"]."'");
							$this->db->query("update `dg_machine_act` set `sort`='$i' where `id`='".$nid."'");	
							$nid=$array["id"];
						}
						$i--;	
					}
					return "success";	
				}
			}		
		}
		
		public function edit_subs($id)
		{
			$_array=array("name"=>$this->input->post("name"));
			if($this->db->update("machine_act",$_array,array("id"=>$id)))
			{
				ajaxs(10000,'修改成功');
			}
			else
			{
				ajaxs(30000,"网络连接失败");
			}
		}
		
		public function indexs_uploads()
		{
			$file=img_upload($_FILES["file_alls"],$result,1,"","","1024");
			if($result=="ok")
			{
				iframes("10000",$file,'stopUpload1');	
			}
			else
			{
				iframes("30000",$file,'stopUpload1');		
			}			
		}
		
		public function mv_uploads()
		{
			$file=mv_upload($_FILES["mv_file"],$result,1);
			if($result=="ok")
			{
				iframes("10000",$file,'stopUpload2');	
			}
			else
			{
				iframes("30000",$file,'stopUpload2');		
			}			
		}
		
		public function adds_subs($id)
		{
			$act=$this->input->post("act");
			$_array=array(
				"name"=>$this->input->post("name"),
				"alt"=>$this->input->post("alt"),
				"file"=>$this->input->post("tupian"),
				"type"=>$id,
				"act"=>$this->input->post("act"),
				"video_path"=>$this->input->post("mv"),
				"times"=>time(),
			);
			if($act==2)
			{
				$arr=array();
				$jsons=$this->input->post("jsons");
				$jsons=explode("{recson}",$jsons);
				foreach($jsons as $k=>$v)
				{
					$as=explode("{sx}",$v);
					$arr[$k]["files"]=$as[0];
					$arr[$k]["text"]=$as[1];
					
				}
				$_array["file_path"]=json_encode($arr);
			}
			else
			{
				$_array["file_path"]="";
			}
			if($this->db->insert("machine",$_array))
			{
				$id=$this->db->insert_id();
				$this->db->query("update `dg_machine` set `sort`='$id' where `id`='$id'");
				ajaxs(10000,"添加成功");
			}
			else
			{
				ajaxs(30000,"网络连接失败");
			}
		}
		
		public function edits_subs($id)
		{
			$act=$this->input->post("act");
			$_array=array(
				"name"=>$this->input->post("name"),
				"alt"=>$this->input->post("alt"),
				"file"=>$this->input->post("tupian"),
				"act"=>$this->input->post("act"),
				"video_path"=>$this->input->post("mv"),
				"times"=>time(),
			);
			if($act==2)
			{
				$arr=array();
				$jsons=$this->input->post("jsons");
				$jsons=explode("{recson}",$jsons);
				foreach($jsons as $k=>$v)
				{
					$as=explode("{sx}",$v);
					$arr[$k]["files"]=$as[0];
					$arr[$k]["text"]=$as[1];
					
				}
				$_array["file_path"]=json_encode($arr);
			}
			else
			{
				$_array["file_path"]="";
			}
			if($this->db->update("machine",$_array,array("id"=>$id)))
			{
				$id=$this->db->insert_id();
				$this->db->query("update `dg_machine` set `sort`='$id' where `id`='$id'");
				ajaxs(10000,"修改成功");
			}
			else
			{
				ajaxs(30000,"网络连接失败");
			}			
		}
		
		public function index_ups($id,$type)
		{
			$sql="select * from `dg_machine` where `type`='$type' order by `sort` asc";
			$query=$this->db->query($sql);
			if($query->num_rows()>0){
				$result=$query->row_array();
				if($result["id"]==$id){
					//第一个id就是所选排序第一id，无需上移
					return "nothing";
				}else{
					$i=0;
					$nid="";
					foreach($query->result_array() as $array){
						if($array["id"]!=$id){
							$nid=$array["id"];
							$this->db->query("update `dg_machine` set `sort`='$i' where `id`='".$array["id"]."'");	
						}else{
							//找到了对应的id信息了，前后的sort进行处理
							$i1=$i-1;
							$this->db->query("update `dg_machine` set `sort`='$i1' where `id`='".$array["id"]."'");
							$this->db->query("update `dg_machine` set `sort`='$i' where `id`='".$nid."'");	
							$nid=$array["id"];
						}
						$i++;	
					}
					return "success";	
				}
			}				
		}
		
		public function index_downs($id,$type)
		{
			$sql="select * from `dg_machine` where `type`='$type' order by `sort` desc";
			$query=$this->db->query($sql);
			if($query->num_rows()>0){
				$result=$query->row_array();
				if($result["id"]==$id){
					//第一个id就是所选排序第一id，无需上移
					return "nothing";
				}else{
					$i=$query->num_rows();
					$nid="";
					foreach($query->result_array() as $array){
						if($array["id"]!=$id){
							$nid=$array["id"];
							$this->db->query("update `dg_machine` set `sort`='$i' where `id`='".$array["id"]."'");	
						}else{
							//找到了对应的id信息了，前后的sort进行处理
							$i1=$i+1;
							$this->db->query("update `dg_machine` set `sort`='$i1' where `id`='".$array["id"]."'");
							$this->db->query("update `dg_machine` set `sort`='$i' where `id`='".$nid."'");	
							$nid=$array["id"];
						}
						$i--;	
					}
					return "success";	
				}
			}				
		}
		
		public function all_subs($id)
		{
			if($this->db->query("delete from `dg_machine` where `id` in (".$id.")"))
			{
				ajaxs(10000,"删除成功");
			}
			else
			{
				ajaxs(30000,"网络连接失败");
			}
		}
	}