<?php
	
	//发布课程控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/master/Alls.php";
	
	class Insert extends Alls
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("master/class/Insert_model","dos");
		}
		
		//获取课程的发布状态
		public function gets()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->gets($rs);
			}	
			else
			{
				error_show();	
			}				
		}
		
		//读取课程发布对应的所有信息
		public function choose()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->choose($rs);
			}	
			else
			{
				error_show();	
			}				
		}
		
		/**
		* 按照日期，提供可选的时间段
		* @date: 2017年2月24日 下午3:39:13
		* @author: 孙子浩   (Steven Sun)
		* @param: variable
		* @return:
		*/
		public function choose_time(){
		    if(is_fulls("token") && is_fulls("class_id",1) && is_fulls("room_id",1) && is_fulls("day"))
		    {
		        $rs=$this->check_token($_REQUEST["token"]);
		        $class_id=trim($_REQUEST["class_id"]);
		        $room_id=trim($_REQUEST["room_id"]);
		        $day=trim($_REQUEST["day"]);
		        $this->dos->choose_time($rs,$class_id,$room_id,$day);
		    }
		    else
		    {
		        error_show();
		    }		    
		}
		
		//发布课程信息
		public function adds()
		{
			if(is_fulls("token") && is_fulls("class_id",1) && is_fulls("room_id",1) && is_fulls("days") && is_fulls("start_time")  && is_fulls("end_time") )
			{
				$rs=$this->check_token($_REQUEST["token"]);
				$class_id=trim($_REQUEST["class_id"]);
				$room_id=trim($_REQUEST["room_id"]);
				$days=trim($_REQUEST["days"]);
				$start_time=trim($_REQUEST["start_time"]);
				$end_time=trim($_REQUEST["end_time"]);
				$this->dos->adds($rs,$class_id,$room_id,$days,$start_time,$end_time);
			}	
			else
			{
				error_show();	
			}				
		}
		
	}