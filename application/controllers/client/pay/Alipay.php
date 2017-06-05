<?php

	//支付宝相关控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Alipay extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("pay/Alipay_model","dos");
		}
		
		//充值提交后返回签名
		public function pay()
		{
			if(is_fulls("money") && is_fulls("token") && is_numeric($_REQUEST["money"]) && $_REQUEST["money"]>0){
				$money=htmlspecialchars(trim($_REQUEST["money"]));
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				echo $this->dos->pay($money,$rs);
			}else{
				error_show();	
			}
		}
		
		//付款成功回执信息
		public function returns()
		{
			$data["uid"]=intval($this->uri->segment(5));
			$this->load->view("pay/alipay_return.php",$data);	
		}
		
	}