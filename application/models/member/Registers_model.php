<?php

	//注册类Model处理层

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Registers_model extends Cmains_model
	{
		
		public function __construct()
		{
			parent::__construct();
		}
		
		//注册信息提交
		public function subs($mobile,$captcha)
		{
			$mobile=htmlspecialchars(trim($mobile));
			$captcha=htmlspecialchars(trim($captcha));
			$querys=$this->db->query("select `id`,`times` from `".$this->dbprefix."captcha` where `mobile`='$mobile' and `captcha`='$captcha' and `act`='1' limit 1");
			if($querys->num_rows()>0){
				$results=$querys->row_array();
				require FCPATH."config/sys.inc.php";
				if(time()-$results["times"]>$_sys_inc["captcha_lives"]){
					json_array2("30000","验证码已经失效","");
				}else{
					if($this->mobile($mobile)){
						$_token_app=create_token("milk");
						//insert
						$_salts=mt_rand(10000000,99999999);
						$_array=array(
							"mobile"=>$mobile,
							"passwd"=>sha1(microtime()."recsons"),
							"salts"=>$_salts,
							"reg_time"=>time(),
							"reg_ip"=>ip2long(get_ip()),
							"login_time"=>time(),
							"login_ip"=>ip2long(get_ip()),
							"last_time"=>time(),
							"last_ip"=>ip2long(get_ip()),
							"token_app"=>$_token_app,
							"user_agent"=>user_agent(),
						);
						
						if(isset($_REQUEST["push_key"]) && trim($_REQUEST["push_key"])!="")
						{
							$_array["push_key"]=trim($_REQUEST["push_key"]);	
						}
						
						if($this->db->insert("user",$_array)){
							$id=$this->db->insert_id();
							$this->db->query("delete from `".$this->dbprefix."captcha` where `mobile`='$mobile' and `act`='1'");
							unset($_array["passwd"]);unset($_array["salts"]);

							require FCPATH."config/money_inc.php";
							require FCPATH."config/push.inc.php";
									
							//注册查询是否赠送金额
							
							if($money_inc["reg"]>0)
							{
								//开始累加金额和增加对应订单信息
								$this->db->query("update `dg_user` set `balance`=`balance`+'".$money_inc["reg"]."' where `id`='$id'");	
								$oid=date("YmdHis").substr(microtime(),2,8);
								$_arrays=array(
									"uid"=>$id,
									"uid_index"=>right_index($id),
									"money"=>$money_inc["reg"],
									"money_remaining"=>$money_inc["reg"],
									"order_id"=>$oid,
									"trade_index"=>$oid,
									"pay_act"=>3,
									"time"=>time(),
								);
								$this->db->insert("pay_order",$_arrays);										
							}
							
							//开始邀请处理
							if(is_fulls("invitation",1))
							{
													
								$invitation=trim($_REQUEST["invitation"]);
								$query=$this->db->query("select `nickname`,`mobile`,`push_key`,`id` from `dg_user` where `id`='$invitation'");
								if($query->num_rows()>0)
								{
									
									//ok没问题了
									$result=$query->row_array();
									
									$nickname="";
									if($result["nickname"]!="")
									{
										$nickname=$result["nickname"];	
									}
									else
									{
										$nickname=substr($result["mobile"],0,3)."****".substr($result["mobile"],7,4);	
									}									
								
									
									$msg=str_replace("{name}",$nickname,$push_inc["register_message"]);
									
									$msg=str_replace("{money}",$money_inc["register"],$msg);
									
									$msg=str_replace("{time}",time(),$msg);
									
									$this->db->trans_strict(false);
									$this->db->trans_begin();
									
									$this->db->query("update `dg_user` set `balance`=`balance`+'".$money_inc["register"]."' where `id`='$invitation'");	//更新账户余额
									
									$oid=date("YmdHis").substr(microtime(),2,8);
									$_arrays=array(
										"uid"=>$invitation,
										"uid_index"=>right_index($invitation),
										"money"=>$money_inc["register"],
										"money_remaining"=>$money_inc["register"],
										"order_id"=>$oid,
										"trade_index"=>$oid,
										"pay_act"=>3,
										"time"=>time(),
									);
									$this->db->insert("pay_order",$_arrays);
									//更新充值记录							
									if($this->db->trans_status()==true)
									{
										$this->db->trans_commit();
										
										if($result["push_key"]!="")
										{
											//如果用户的push_key不为空就开始推送了
											
											$_arrays=array("message"=>$msg,"type"=>3,"id"=>"0","push_id"=>$result["push_key"],"title"=>$push_inc["register_title"]);
											
											//print_r($_arrays);die();
											
											c_push($_arrays);
										}
									}
									else
									{
										$this->db->trans_rollback();	
									}
								}
							}
							
							json_array2("10000","成功",$this->read_login($id));
						}else{
							error_show();
						}

					}else{
						json_array2("30000","手机号已被注册","");
					}
				}
			}else{
				json_array2("30000","验证码不正确","");
			}			
		}
		
	}