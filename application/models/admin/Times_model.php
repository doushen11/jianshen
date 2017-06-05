<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Times_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		public function opens_subs($s1,$s2,$e1,$e2)
		{
			$this->db->query("update `dg_time_model` set `min`='$s1',`max`='$e1' where `id`='1'");
			$this->db->query("update `dg_time_model` set `min`='$s2',`max`='$e2' where `id`='2'");	
			ajaxs(10000,"更新成功");
		}
		
		public function class_a_subs($text,$id)
		{
			$arr=explode("|_|",$text);
			foreach($arr as $k=>$v)
			{
				$arrs=explode("{syx}",$v);
				$_array=array(
					"min"=>trim($arrs[1]),
					"max"=>trim($arrs[2]),
					"model"=>trim($arrs[3]),
					"act"=>2,
				);
				if($arrs[0]=="recsons")
				{
					$this->db->insert("time_model",$_array);
				}
				elseif(is_numeric($arrs[0]))
				{
					$this->db->update("time_model",$_array,array("id"=>trim($arrs[0])));
				}
			}
			if(trim($id)!="")
			{
				$id=trim($id,",");
				$this->db->query("delete from `dg_time_model` where `id` in (".$id.")");
			}
			ajaxs(10000,"更新成功");				
		}
		
		public function class_b_subs($text,$id)
		{
			$arr=explode("|_|",$text);
			foreach($arr as $k=>$v)
			{
				$arrs=explode("{syx}",$v);
				$_array=array(
					"min"=>trim($arrs[1]),
					"max"=>trim($arrs[2]),
					"model"=>trim($arrs[3]),
					"act"=>3,
				);
				if($arrs[0]=="recsons")
				{
					$this->db->insert("time_model",$_array);
				}
				elseif(is_numeric($arrs[0]))
				{
					$this->db->update("time_model",$_array,array("id"=>trim($arrs[0])));
				}
			}
			if(trim($id)!="")
			{
				$id=trim($id,",");
				$this->db->query("delete from `dg_time_model` where `id` in (".$id.")");
			}
			ajaxs(10000,"更新成功");				
		}
		
	}