<?php
/**
*微信支付类
*此类使用需要php版本大于4 
**/
class wxPayment {
	
	private $appid ;                 //微信分配的公众账号ID
	private $mch_id ;							//微信支付分配的商户号
	private $keys;		//生成签名所用，在 微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置 查看
	private $total_fee;										//金钱
	private $spbill_create_ip;								//APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
	private $trade_type;  									//取值如下：JSAPI，NATIVE，APP，WAP,详细说明见
	private $notify_url;									//通知地址
	private $out_trade_no;									//商户订单号码
	private $body;
	
	public function __construct($appid,$mch_id,$key,$total_fee,$spbill_create_ip,$trade_type,$notify_url,$out_trade_no,$body){
		$this->appid = $appid;
		$this->mch_id = $mch_id;
		$this->keys = $key;
		$this->total_fee = $total_fee;
		$this->spbill_create_ip = $spbill_create_ip;
		$this->trade_type = $trade_type;
		$this->notify_url = $notify_url;
		$this->out_trade_no = $out_trade_no;
		$this->body = $body;

	}
	
		/*生成签名算法(此签名生成方式只适合统一下单支付签名)*/
		
		public function getSign(){
			
			$appid = $this->appid;
			$mch_id = $this->mch_id;
			$device_info = 1000;
			$body = $this->body;				//此处写说明
			$nonce_str = '58d42a2f6ee3e4feb6669bac86f3fceb';
			
			
			$out_trade_no = $this->out_trade_no;		//商户订单号码
			$total_fee = $this->total_fee;    //总金额
			$spbill_create_ip = $this->spbill_create_ip	;	//APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
			$notify_url = $this->notify_url ;    //通知地址
			$trade_type = $this->trade_type;  //取值如下：JSAPI，NATIVE，APP，WAP,详细说明见
			
			$string = "appid=".$appid."&body=".$body."&device_info=".$device_info."&mch_id=".$mch_id."&nonce_str=".$nonce_str."&notify_url=".$notify_url."&out_trade_no=".$out_trade_no."&spbill_create_ip=".$spbill_create_ip."&total_fee=".$total_fee."&trade_type=".$trade_type."";
			
			//拼接API密钥
			$strtemp = $string."&key=".$this->keys;
			$sign = mb_strtoupper(md5($strtemp));
			return $sign;
			
		}
		

		
		//统一下单(用于APP)
		public function unifyPlace(){
			$appid = $this->appid;
			$mch_id = $this->mch_id;
			$nonce_str = '58d42a2f6ee3e4feb6669bac86f3fceb';
			$body = $this->body;
			$device_info = 1000;
			$sign = $this->getSign();
			
			
			$out_trade_no = $this->out_trade_no;		//商户订单号码
			$total_fee = $this->total_fee;    //总金额
			$spbill_create_ip = $this->spbill_create_ip;	//APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
			$notify_url = $this->notify_url ;    //通知地址
			$trade_type = $this->trade_type;  //取值如下：JSAPI，NATIVE，APP，WAP,详细说明见
			
			$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
			
			$str ="
<xml>
   <appid><![CDATA[".$appid."]]></appid>
   <body><![CDATA[".$body."]]></body>
   <mch_id><![CDATA[".$mch_id."]]></mch_id>
   <device_info><![CDATA[".$device_info."]]></device_info>
   <nonce_str><![CDATA[".$nonce_str."]]></nonce_str>
   <sign><![CDATA[".$sign."]]></sign>
   <notify_url><![CDATA[".$notify_url."]]></notify_url>
   <out_trade_no><![CDATA[".$out_trade_no."]]></out_trade_no>
   <spbill_create_ip><![CDATA[".$spbill_create_ip."]]></spbill_create_ip>
   <total_fee><![CDATA[".$total_fee."]]></total_fee>
   <trade_type><![CDATA[".$trade_type."]]></trade_type>
</xml>";



	
	
		$info = $this->https_request($url,$str);

			$obj = simplexml_load_string($info,'SimpleXMLElement',LIBXML_NOCDATA);
		
		
			
			//转成json
			$info = json_encode($obj);
			
			$_array = json_decode($info);
			
			//print_r($_array);exit();
			$newArray = array(
			    'appid'=>$_array->appid,
			    'mch_id'=>$_array->mch_id,
			    'sign'=>$_array->sign,
			    'prepay_id'=>$_array->prepay_id,
			    'nonce_str'=>$_array->nonce_str,
			    'device_info'=>$_array->device_info,
			    'times'=>time(),
			);
			return $newArray;
			
			
		}
		
		
	/**
	 * https发送协议
	 */
	public function https_request($url,$data=null){
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			//如果type有参数，不是空的话 那么就加参数
			if(!empty($data)){
				curl_setopt($ch,CURLOPT_POST,1);
				curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
			}
			//把请求后的curl返回
			$opt = curl_exec($ch);
			//关闭curl
			curl_close($ch);
			return $opt;
	}
	
	
		
}	



?>