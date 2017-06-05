<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Tongjis extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
		}
		
		//充值记录-按照年份统计
		public function pays()
		{
			if(isset($_GET["y"]))
			{
				$year=trim($_GET["y"]);	
			}	
			else
			{
				$year=date("Y");	
			}
			$array=$this->get_days_by_year($year);
			for($i=0;$i<count($array);$i++)
			{
				$query_1=$this->db->query("select sum(`money`) as `money` from `dg_pay_order` where `time`>='".$array[$i]["start_time"]."' and `time`<='".$array[$i]["end_time"]."' and `pay_act`='1'");
				$result_1=$query_1->row_array();
				$query_2=$this->db->query("select sum(`money`) as `money` from `dg_pay_order` where `time`>='".$array[$i]["start_time"]."' and `time`<='".$array[$i]["end_time"]."' and `pay_act`='2'");
				$result_2=$query_2->row_array();
				$query_3=$this->db->query("select sum(`money`) as `money` from `dg_pay_order` where `time`>='".$array[$i]["start_time"]."' and `time`<='".$array[$i]["end_time"]."' and `pay_act`='3'");
				$result_3=$query_3->row_array();
				$query_4=$this->db->query("select sum(`money`) as `money` from `dg_pay_order` where `time`>='".$array[$i]["start_time"]."' and `time`<='".$array[$i]["end_time"]."'");
				$result_4=$query_4->row_array();
				$array[$i]["money1"]=sprintf("%.2f",$result_1["money"]);
				$array[$i]["money2"]=sprintf("%.2f",$result_2["money"]);
				$array[$i]["money3"]=sprintf("%.2f",$result_3["money"]);
				$array[$i]["money4"]=sprintf("%.2f",$result_4["money"]);
			}
			//print_r($array);
			$data["array"]=$array;
			$data["year"]=$year;
			$this->load->view("admin/tongjis/pay.php",$data);
		}
		
		function get_days_by_year($year){
			$array=array();
			//首先判断闰年
			if($year%400 == 0  || ($year%4 == 0 && $year%100 !== 0)){
				$rday = 29;
			}else{
				$rday = 28;
			}
			for ($i=1; $i<=12;$i++){
				if($i==2){
					$days = $rday;
				}else{
					//判断是大月（31），还是小月（30）
					$days = (($i - 1)%7%2) ? 30 : 31;
				}
				//echo $year."年".$i."月有：".$days."天";
				$array[$i-1]["start_time"]=$year."-".$i."-01 00:00:00";
				$array[$i-1]["end_time"]=$year."-".$i."-".$days." 23:59:59";
				$array[$i-1]["start_time"]=strtotime($array[$i-1]["start_time"]);
				$array[$i-1]["end_time"]=strtotime($array[$i-1]["end_time"]);
			}
			 return $array;
		}	
	}