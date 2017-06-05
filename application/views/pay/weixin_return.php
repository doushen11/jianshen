<?php
	//xml转数组
	function xml_to_array($xml)
	{
		$reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
		if(preg_match_all($reg, $xml, $matches))
		{
			$count = count($matches[0]);
			$arr = array();
			for($i = 0; $i < $count; $i++)
			{
				$key=$matches[1][$i];
				$val=xml_to_array($matches[2][$i]);  // 递归
				if(array_key_exists($key,$arr))
				{
					if(is_array($arr[$key]))
					{
						if(!array_key_exists(0,$arr[$key]))
						{
							$arr[$key] = array($arr[$key]);
						}
					}else{
						$arr[$key] = array($arr[$key]);
					}
					$arr[$key][] = $val;
				}else{
					$arr[$key] = $val;
				}
			}
			return $arr;
		}else{
			return $xml;
		}
	}
	
	//Xml 转 数组, 不包括根键
	function xmltoarray($xml)
	{
		$arr=xml_to_array($xml);
		$key=array_keys($arr);
		return $arr[$key[0]];
	}
	
	$xmls=@$GLOBALS["HTTP_RAW_POST_DATA"];
	$arrs=xml_to_array($xmls);	
	$str=json_encode($arrs);
	$array=json_decode($str,true);
	$array=$array["xml"];
	
	$uid=intval($this->uri->segment(5));
	if($uid>0)
	{
		/*$str="";
		foreach($array as $k=>$v)
		{
			$str.=$k."______________".$v."___________".date("Y-m-d H:i:s")."<hr></hr>";	
		}
		
		@unlink(FCPATH."/wx_debug.php");
		
		$files=fopen(FCPATH."/wx_debug.php","a");
		
		fwrite($files,"<pre>".$str."<br><br>".date("Y-m-d H:i:s")."</pre>");*/
			
		$out_trade_no=str_replace("<![CDATA[","",$array["out_trade_no"]); //订单号
		$out_trade_no=str_replace("]]>","",$out_trade_no); //订单号
		$total_fee=$array["total_fee"]/100; //金额
		
		$trade_no=str_replace("<![CDATA[","",$array["transaction_id"]);//商户号
		$trade_no=str_replace("]]>","",$trade_no);//商户号
		$this->db->trans_strict(false);
		$this->db->trans_begin();			
		$sql="select `id` from `dg_pay_order` where `order_id`='$out_trade_no' and `uid_index`='".right_index($uid)."' and `uid`='$uid' and `pay_act`='2' limit 1";
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
				"pay_act"=>2,
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
						
						//="df67d3ab0d676ba08a4333ceeacefeea";
						
						
						
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
	
	}
	
	//$str=htmlspecialchars($xmls);
	
	//$files=fopen(FCPATH."/wx_debug.php","a");
	
	//fwrite($files,"<pre>".$str."<br><br>".date("Y-m-d H:i:s")."</pre>");