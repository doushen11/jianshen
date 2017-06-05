<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Home_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//获取对应的教练墙教练列表
		public function index()
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			$model=1;
			if(isset($_REQUEST["model"]) && in_array(trim($_REQUEST["model"]),array("1","2")))
			{
				$model=trim($_REQUEST["model"]);	
			}

			$sql="select `id`,`realname`,`avatar`,`desc`,`birthday`,`score`,`level`,`money_desc` from `dg_teacher` where `act`='$model' order by `score` desc";	

			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);	
			$array=array();$i=0;
			foreach($query->result_array() as $arrays)
			{
				$array[$i]=$arrays;
				$bs=explode("-",$array[$i]["birthday"]);
				$array[$i]["birthday"]=date("Y")-$bs[0];
				$array[$i]["alts"]="";
				
				if($model==2){
					$arrs=json_decode($array[$i]["money_desc"],true);
					for($a=0;$a<count($arrs);$a++)
					{
						$querys=$this->db->query("select `name` from `dg_class` where `id`='".$arrs[$a]["class"]."'");
						if($querys->num_rows()>0)
						{
							$results=$querys->row_array();
							$array[$i]["alts"].=" ".$results["name"];	
						}	
					}
					$array[$i]["alts"]=trim($array[$i]["alts"]);
				}
				$i++;
			}
			json_array2("10000","成功",$array);				
		}		
	}