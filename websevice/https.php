<?php
	/**
	 +------------------------------------------------------------------------------
	 * wsdl客户端  
	 +------------------------------------------------------------------------------
	 * @wsdl服务端接收
	 * @Author Recson<admin@zjtd100.com>
	 * @Copyright (c) www.zjtd100.com
	 +------------------------------------------------------------------------------
	 */
 	$str="";
 	if(isset($_GET["str"]) && trim($_GET["str"])!="")
	{
		$str=trim($_GET["str"]);	
	}

	//$fs=fopen(FCPATH."/z100.php","a");
	
	//fwrite($fs,$str."________".date("Y-m-d H:i:s")."<hr></hr>");
 
	$client = new SoapClient("http://101.201.37.67:10090/websevice/doors.wsdl");
	 
	try {
		$result = $client->return_success($str);
		echo $result;
	}
	catch (SoapFault $f){
		echo "Error Message: {$f->getMessage()}";
	}
?>