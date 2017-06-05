<?php
	
	//微信Model处理层

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Weixin_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		public function pay($money,$rs)
		{
			require FCPATH."weixin/class.php";
						
			$weixin = new wxPayment('wxc4e98b98f54a29ea','1376623002','80d7a0141e1e3b413058a8d8595877fd',$money*100,'8.8.8.8','APP',http_url().'/client/pay/weixin/returns/'.$rs["id"],date("YmdHis").substr(microtime(),2,8),"智能健身会员账户充值",'');
			
			$wei = $weixin->unifyPlace();
			
			json_array(10000,"成功",$wei);					
		}
		
	}