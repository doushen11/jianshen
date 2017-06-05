<?php
	
	//对位操作model类
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Cmains_model extends CI_Model
	{

		protected $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		
		//检测手机号被注册状态
		protected function mobile($mobile){
			$querys=$this->db->query("select `id` from `".$this->dbprefix."user` where `mobile`='$mobile' limit 1");	
			if($querys->num_rows()<=0){
				return true;				
			}
			return false;
		}
		
		//检测token信息
		public function check_token($token,$msg=null){
			if(substr_count($token,"_")!=2)
			{
				error_token_show();
			}
			else
			{
				$msg==""?$msg="*":$msg=$msg;
				$query=$this->db->query("select ".$msg." from `dg_user` where `token_app`='".trim($arrs[0])."' and `passwd`='".trim($arrs[1])."' and `salts`='".trim($arrs[2])."' and `state`='1' limit 1");
				if($query->num_rows()>0){
					return $query->row_array();
				}else{
					error_token_show();		
				}
			}
		}
		
		//常规token检测
		public function get_users($token,$msg=null)
		{
			if(substr_count($token,"_")!=2)
			{
				return false;	
			}
			$arrs=explode("_",$token);
			$msg==""?$msg="*":$msg=$msg;
			$query=$this->db->query("select ".$msg." from `dg_user` where `token_app`='".trim($arrs[0])."' and `passwd`='".trim($arrs[1])."' and `salts`='".trim($arrs[2])."' limit 1");
			if($query->num_rows()>0){
				return $query->row_array();
			}else{
				return false;	
			}				
		}
		
		//读取登录返回信息
		protected function read_login($id)
		{
			$query=$this->db->query("select * from `dg_user` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$token_app=$result["token_app"]."_".$result["passwd"]."_".$result["salts"];
				$result["token_app"]=$token_app;
				unset($result["passwd"]);
				unset($result["last_ip"]);
				unset($result["last_time"]);
				unset($result["login_ip"]);
				unset($result["login_time"]);
				unset($result["salts"]);
				unset($result["user_agent"]);
				require FCPATH."config/img.inc.php";
				if($result["avatar"]=="")
				{
					$result["avatar"]=$img_inc["avatar"];
				}
				return $result;
			}	
			else
			{
				error_token_show();	
			}
		}
		
		//支付宝签名生成
		protected function get_alipay_sign_str($partner,$seller,$sign_type,$private_key_path,$orderId,$money,$body,$uid){
			//生成公钥函数
			$order_str =   getOrderInfo($partner,$seller,"智能健身",$body,$orderId,$money,$uid);
			$order_str2 =   getOrderInfo3($partner,$seller,"智能健身",$body,$orderId,$money,$uid);
			// echo $order_str;
			$sign = rsaSign($order_str2,$private_key_path);
			$sign = urlencode(mb_convert_encoding($sign, 'utf-8', 'gb2312'));
			// 完整的符合支付宝参数规范的订单信息
			//   $payInfo = $order_str ."&sign=\"" .$sign . "\"&".$alipay_config['sign_type']."\"";
			$payInfo = $order_str ."&sign=\\\"" .$sign . "\\\"&sign_type=\\\"".$sign_type."\\\"";
			return $payInfo;
		}
		
	}