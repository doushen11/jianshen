<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
 
if($uid<=0){exit;}
 
require_once(FCPATH."/alipay/alipay.config.php");
require_once(FCPATH."/alipay/lib/alipay_notify.class.php");


//$str="";
//foreach($_REQUEST as $k=>$v){
	//$str.=$k.":::::::::::::::".$v;
	//$str.="<hr></hr>";	
//}

//$file=fopen($_SERVER["DOCUMENT_ROOT"]."/housekeep/guozheng.php","a");
//fwrite($file,$str);



//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号

	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号

	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];

    //金额
    $total_fee = $_POST['total_fee'];


    if($_POST['trade_status'] == 'TRADE_FINISHED') {
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序

       /* $pay_record = $db->getOneRow("select * from pine_payrecord  where  payId =  '".$out_trade_no."' and status =0");
        if($pay_record ){
			//$honey = $db->getOneField("select honey from pine_honey where money =".$total_fee);
            $db->query("update pine_payrecord set status = 1 where payId= '".$out_trade_no."'");
            $db->query("update pine_user set honey = honey + ".$pay_record["honey"]." where id = '".$pay_record["uid"]."'");
			
        }*/
		$this->db->trans_strict(false);
		$this->db->trans_begin();			
		$sql="select `id` from `dg_pay_order` where `order_id`='$out_trade_no' and `uid_index`='".right_index($uid)."' and `uid`='$uid' and `pay_act`='1' limit 1";
		$query=$this->db->query($sql);
		if($query->num_rows()<=0)
		{
			//开启手动回滚		
			$_array=array(
				"uid"=>$uid,
				"uid_index"=>right_index($uid),
				"money"=>$total_fee,
				"money_remaining"=>$total_fee,
				"order_id"=>$out_trade_no,
				"trade_index"=>$trade_no,
				"pay_act"=>1,
				"time"=>time(),
			);	
			$this->db->insert("pay_order",$_array);
			
			$this->db->query("update `dg_user` set `balance`=`balance`+'$total_fee' where `id`='$uid'");
			if($this->db->trans_status()==true){
				$this->db->trans_commit();

				//开始推送
				$m_query=$this->db->query("select `push_key`,`balance`,`nickname`,`mobile` from `dg_user` where `id`='$uid'");

				
				
				if($m_query->num_rows()>0)
				{
					
					//$f=fopen(FCPATH.'aaa.php',"w");
				
					//fwrite($f,"select `push_key`,`balance`,`nickname`,`mobile` from `dg_user` where `id`='$uid'".date("Y-m-d H:i:s"));	
					
					$m_result=$m_query->row_array();
					
					if($m_result["push_key"]!="")
					{
														
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
						
						//$m_result["push_key"]="df67d3ab0d676ba08a4333ceeacefeea";
						
						
						
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
					
						//print_r($_arrays);
						
						c_push($_arrays);
					
					}
				
				}
				//结束推送
				
			}else{
				$this->db->trans_rollback();
			}			
		}

        write_log_pay("pay_success");
        //注意：
		//该种交易状态只在两种情况下出现
		//1、开通了普通即时到账，买家付款成功后。
		//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		$sql="select `id` from `dg_pay_order` where `order_id`='$out_trade_no' and `uid_index`='".right_index($uid)."' and `uid`='$uid' limit 1";
		$query=$this->db->query($sql);
		if($query->num_rows()<=0)
		{
			//开启手动回滚
			$this->db->trans_strict(false);
			$this->db->trans_begin();			
			$_array=array(
				"uid"=>$uid,
				"uid_index"=>right_index($uid),
				"money"=>$total_fee,
				"money_remaining"=>$total_fee,
				"order_id"=>$out_trade_no,
				"trade_index"=>$trade_no,
				"pay_act"=>1,
				"time"=>time(),
			);	
			$this->db->insert("pay_order",$_array);
			$this->db->query("update `dg_user` set `balance`=`balance`+'$total_fee' where `id`='$uid'");
			if($this->db->trans_status()==true){
				$this->db->trans_commit();
				
				$m_query=$this->db->query("select `push_key`,`balance`,`nickname`,`mobile` from `dg_user` where `id`='$uid'");

				//$f=fopen(FCPATH.'aaa.php',"w");
				
				//fwrite($f,"select `push_key`,`balance`,`nickname`,`mobile` from `dg_user` where `id`='$uid'");		
						
				if($m_query->num_rows()>0)
				{
					
					$m_result=$m_query->row_array();	
					
					if($m_result["push_key"]!="")
					{												
														
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
						
						//$m_result["push_key"]="df67d3ab0d676ba08a4333ceeacefeea";
						
						
						
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
						
						//print_r($_arrays);
						
						//$f=fopen(FCPATH.'bbb.php',"w");
						
						//fwrite($f,json_encode($_arrays));					
						
						c_push($_arrays);
					
					}
				
				}				
				
			}else{
				$this->db->trans_rollback();
			}			
		}
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	
	/*	$pay_record = $db->getOneRow("select * from bx_order  where  orderid =  '".$out_trade_no."' and states = 5 and paystate = 1 and isqx = 0");
        if($pay_record){
			$db->query("update bx_order set states = 6,coment = 0,stateshz = '待评论',paystate = 2 where orderid= '".$out_trade_no."'");
	
	//$total_fee			
        }*/
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	
	
	
	echo "success"; //请不要修改或删除
    write_log_pay("success");
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";
    write_log_pay("check_fail");
    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>