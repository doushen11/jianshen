<?php
$total_fee=0.01;

//开始推送
$m_query=$this->db->query("select `push_key`,`balance`,`nickname`,`mobile` from `dg_user` where `id`='4'");

//$f=fopen(FCPATH.'aaa.php',"w");

//fwrite($f,"select `push_key`,`balance`,`nickname`,`mobile` from `dg_user` where `id`='$uid'");	

if($m_query->num_rows()>0)
{
	
	$m_result=$m_query->row_array();
										
	//计算高低峰时间
	$query_a=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='1' and `model`='1'");
	
	$query_b=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='1' and `model`='2'");
	
	$result_a=$query_a->row_array();
	
	$result_b=$query_b->row_array();
	
	$height_time=$result_a["min"].":00-".$result_a["max"].":00";
	
	$low_time=$result_b["min"].":00-".$result_b["max"].":00";
	//计算高低峰时间
	
	$money=$total_fee;
	
	//计算高低峰价格
	
	$query_model=$this->db->query("select `money_peak`,`money_slack` from `dg_pay_model` where (`min`<='$money' and `max`>'$money') or (`min`<='$money' and `max`='0')");
	
	if($query_model->num_rows()>0)
	{
		$result_model=$query_model->row_array();
		$height_money=$result_model["money_peak"];
		$low_money=$result_model["money_slack"];
	}
	else
	{
		$height_money="未知";
		$low_money="未知";	
	}
	
	
	$nickname="";
	if($m_result["nickname"]!="")
	{
		$nickname=$m_result["nickname"];	
	}
	else
	{
		$nickname=substr($m_result["mobile"],0,3)."****".substr($m_result["mobile"],7,4);	
	}
	
	$m_result["push_key"]="df67d3ab0d676ba08a4333ceeacefeea";
	
	
	
	require FCPATH."config/push.inc.php";
	
	
	$msg=str_replace("{name}",$nickname,$push_inc["pay_message"]);
	
	$msg=str_replace("{money}",$money,$msg);
	
	$msg=str_replace("{height}",$height_time,$msg);
	
	$msg=str_replace("{low}",$low_time,$msg);
	
	$msg=str_replace("{height_money}",$height_money,$msg);
	
	$msg=str_replace("{low_money}",$low_money,$msg);
	
	//echo $msg;
	
	//$m_result["push_key"]="df67d3ab0d676ba08a4333ceeacefeea";
	
	$_arrays=array("message"=>$msg,"type"=>2,"id"=>"0","push_id"=>$m_result["push_key"],"title"=>$push_inc["pay_title"]);

	//print_r($_arrays);die();
	
	c_push($_arrays);	

}