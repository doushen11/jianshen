<?php
	header("Content-type:text/html;charset=utf8");	
	
	if(isset($_GET["keys"]) && trim($_GET["keys"])=="1282d94a4f461110b676f711b221d86a76b8a8008982064b6bc94d08ec2b58fff9023a3699ba8e03adebe8a12359bb772ee639a5c418c908" && isset($_GET["codes"]) && trim($_GET["codes"])!="")
	{
		
		$codes=trim($_GET["codes"]);
		
		$connection = new MongoClient( "mongodb://doors:test100@101.201.37.67:27017/doors" ); // 连接远程数据库，端口号为27017
		
		$db=$connection->doors; //链接对应的mongodb数据库  
		
		$collection=$db->IOCtrl;//选择doors合集
		
		$collection->remove(array('tagname'=>'DO2'));//先删除一下之前的记录信息
		
		$_array=array(
			"DevAddr"=>"192.168.1.99",
			"tagname"=>"DO2",
			"value"=>0,
			"state"=>0,
			"Timet"=>time(),
			"TimetMS"=>33,
			"CtrlID"=>"AAA",
			"Code2"=>$codes,
		);	
		
		$collection->insert($_array);
		
		$filer=fopen("aaa.php","a");
		fwrite($filer,date("Y-m-d H:i:s")."___________<hr></hr>");
	
	}