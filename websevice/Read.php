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
 
	$client = new SoapClient("http://101.201.37.67:10090/websevice/doors.wsdl");
	 
	try {
		$result = $client->return_success("1_68f885489191b4e2e998b96475898b097d3d2bb9_2_1472787695_2");
		echo $result;
		//var_dump($result);
	}
	catch (SoapFault $f){
		echo "Error Message: {$f->getMessage()}";
	}
?>