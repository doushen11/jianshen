<?php
	
	//支付宝Model处理层

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Alipay_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		public function pay($money,$rs)
		{
			require(FCPATH."/alipay/alipay.config.php");
			require(FCPATH."/alipay/lib/alipay_core.function.php");
			require(FCPATH."/alipay/lib/alipay_rsa.function.php");
			require(FCPATH."/alipay/lib/alipay_server.1.php");				
			$payInfo = $this->get_alipay_sign_str($alipay_config['partner'],$alipay_config['seller'],$alipay_config['sign_type'],FCPATH."/alipay/".$alipay_config['private_key_path'],date("YmdHis").substr(microtime(),2,8).mt_rand(1000,9999),$money,"智能健身会员账户充值",$rs["id"]);
			json_array(10000,"成功",stripslashes($payInfo));					
		}
		
	}