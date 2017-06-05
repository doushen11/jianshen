<?php 
	if (!defined('BASEPATH')) exit('No direct script access allowed'); 

	class Recsonclass{

				
		public function convertip($ip) {
			error_reporting(0);
			//IP数据文件路径
			$dat_path = FCPATH.'public/plugins/ip/qqwry.dat';
		
			//检查IP地址
			if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)) {
				return 'IP Address Error';
			}
			//打开IP数据文件
			if(!$fd = @fopen($dat_path, 'rb')){
				return 'IP date file not exists or access denied';
			}
		
			//分解IP进行运算，得出整形数
			$ip = explode('.', $ip);
			$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
		
			//获取IP数据索引开始和结束位置
			$DataBegin = fread($fd, 4);
			$DataEnd = fread($fd, 4);
			$ipbegin = implode('', unpack('L', $DataBegin));
			if($ipbegin < 0) $ipbegin += pow(2, 32);
			$ipend = implode('', unpack('L', $DataEnd));
			if($ipend < 0) $ipend += pow(2, 32);
			$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
		  
			$BeginNum = 0;
			$EndNum = $ipAllNum;
		
			//使用二分查找法从索引记录中搜索匹配的IP记录
			while($ip1num>$ipNum || $ip2num<$ipNum) {
				$Middle= intval(($EndNum + $BeginNum) / 2);
		
				//偏移指针到索引位置读取4个字节
				fseek($fd, $ipbegin + 7 * $Middle);
				$ipData1 = fread($fd, 4);
				if(strlen($ipData1) < 4) {
					fclose($fd);
					return 'System Error';
				}
				//提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂
				$ip1num = implode('', unpack('L', $ipData1));
				if($ip1num < 0) $ip1num += pow(2, 32);
			  
				//提取的长整型数大于我们IP地址则修改结束位置进行下一次循环
				if($ip1num > $ipNum) {
					$EndNum = $Middle;
					continue;
				}
			  
				//取完上一个索引后取下一个索引
				$DataSeek = fread($fd, 3);
				if(strlen($DataSeek) < 3) {
					fclose($fd);
					return 'System Error';
				}
				$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
				fseek($fd, $DataSeek);
				$ipData2 = fread($fd, 4);
				if(strlen($ipData2) < 4) {
					fclose($fd);
					return 'System Error';
				}
				$ip2num = implode('', unpack('L', $ipData2));
				if($ip2num < 0) $ip2num += pow(2, 32);
		
				//没找到提示未知
				if($ip2num < $ipNum) {
					if($Middle == $BeginNum) {
						fclose($fd);
						return 'Unknown';
					}
					$BeginNum = $Middle;
				}
			}
		
			//下面的代码读晕了，没读明白，有兴趣的慢慢读
			$ipFlag = fread($fd, 1);
			if($ipFlag == chr(1)) {
				$ipSeek = fread($fd, 3);
				if(strlen($ipSeek) < 3) {
					fclose($fd);
					return 'System Error';
				}
				$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
				fseek($fd, $ipSeek);
				$ipFlag = fread($fd, 1);
			}
		
			if($ipFlag == chr(2)) {
				$AddrSeek = fread($fd, 3);
				if(strlen($AddrSeek) < 3) {
					fclose($fd);
					return 'System Error';
				}
				$ipFlag = fread($fd, 1);
				if($ipFlag == chr(2)) {
					$AddrSeek2 = fread($fd, 3);
					if(strlen($AddrSeek2) < 3) {
						fclose($fd);
						return 'System Error';
					}
					$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
					fseek($fd, $AddrSeek2);
				} else {
					fseek($fd, -1, SEEK_CUR);
				}
		
				while(($char = fread($fd, 1)) != chr(0))
					$ipAddr2 .= $char;
		
				$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
				fseek($fd, $AddrSeek);
		
				while(($char = fread($fd, 1)) != chr(0))
					$ipAddr1 .= $char;
			} else {
				fseek($fd, -1, SEEK_CUR);
				while(($char = fread($fd, 1)) != chr(0))
					$ipAddr1 .= $char;
		
				$ipFlag = fread($fd, 1);
				if($ipFlag == chr(2)) {
					$AddrSeek2 = fread($fd, 3);
					if(strlen($AddrSeek2) < 3) {
						fclose($fd);
						return 'System Error';
					}
					$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
					fseek($fd, $AddrSeek2);
				} else {
					fseek($fd, -1, SEEK_CUR);
				}
				while(($char = fread($fd, 1)) != chr(0)){
					$ipAddr2 .= $char;
				}
			}
			fclose($fd);
		
			//最后做相应的替换操作后返回结果
			if(preg_match('/http/i', $ipAddr2)) {
				$ipAddr2 = '';
			}
			$ipaddr = "$ipAddr1 $ipAddr2";
			$ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);
			$ipaddr = preg_replace('/^s*/is', '', $ipaddr);
			$ipaddr = preg_replace('/s*$/is', '', $ipaddr);
			if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
				$ipaddr = 'Unknown';
			}
		
			return @iconv("gb2312","utf-8",$ipaddr);
		}		
		
		public function mp3_upload($file,&$result,$upload=null,$file1=null,$size=null){
			//自定义上传图片函数
	   		 //	print_r($file);die();
			
			if(substr_count($file1,"upload")<=0){
				$file1="";
			}
			
			//print_r($file);
			
			$root=FCPATH;
			$size==""?$size=8048:$size=$size;
			$p_name  = $file["name"];
			$p_type  = $file["type"];
			$p_tmp   = $file["tmp_name"];
			$p_error = $file["error"];
			$p_size  = $file["size"];
            //dump($file);
			$extname=strtolower(substr($p_name,strrpos($p_name,".")+1,1000));
          
			if($upload=="" && $extname==""){
				if($file1==""){
					$result="ok";
					return "";				
				}else{
					$result="ok";
					return $file1;
				}
			}else{

				if($extname==""){
					$result="error";
					return "请选择上传音频文件";				
				}elseif($p_error>0){
					$result="error";
					return "系统链接超时，请重试";
				}elseif(substr_count("mp3_mid_wma",$extname)==0){
					$result="error";
					return "请上传音频文件";
				}elseif(substr_count($p_type,"application")==0 && substr_count($p_type,"audio")==0){
					$result="error";
					return "请上传音频文件!";
				}elseif($p_size>($size*1024)){
					$result="error";
					return "音频大小不能超过".$size."KB";
				}else{
					if(!is_uploaded_file($p_tmp)){
						$result="error";
						return "系统链接超时，请重试";			
					}else{
						if(!is_dir($root."public/upload")){
							mkdir($root."public/upload");
						}
						if(!is_dir($root."public/upload/images")){
							mkdir($root."public/upload/images");
						}
						if(!is_dir($root."public/upload/images/".date("Ymd"))){
							mkdir($root."public/upload/images/".date("Ymd"));
						}
						$newname="recson_".date("YmdHis").substr(microtime(),2,8).rand(0,99999).".".$extname;
						$newpic=$root."public/upload/images/".date("Ymd")."/".$newname;
						$newurl="public/upload/images/".date("Ymd")."/".$newname;
						if($file1==""){
							$newpic=$root."public/upload/images/".date("Ymd")."/".$newname;
							$newurl="public/upload/images/".date("Ymd")."/".$newname;						
						}else{
							$newpic=$root."public/upload/images/".date("Ymd")."/".$newname;
							$newurl="public/upload/images/".date("Ymd")."/".$newname;					
						}

						if(move_uploaded_file($p_tmp,$newpic)){
							$result="ok";
							return $newurl;					
						}else{
							$result="error";
							return "系统链接超时，请重试";					
						}
					}
				}
			}
		}		
		
		public function upload($file,&$result,$upload=null,$file1=null,$size=null,$resize=null){
			//自定义上传图片函数
	    //	print_r($file);die();
			
			if(substr_count($file1,"upload")<=0){
				$file1="";
			}
			$root=FCPATH;
			$size==""?$size=8048:$size=$size;
			$p_name  = $file["name"];
			$p_type  = $file["type"];
			$p_tmp   = $file["tmp_name"];
			$p_error = $file["error"];
			$p_size  = $file["size"];
            //dump($file);
			$extname=strtolower(substr($p_name,strrpos($p_name,".")+1,1000));
          
			if($upload=="" && $extname==""){
				if($file1==""){
					$result="ok";
					return "";				
				}else{
					$result="ok";
					return $file1;
				}
			}else{

				if($extname==""){
					$result="error";
					return "请选择上传文件";				
				}elseif($p_error>0){
					$result="error";
					return "系统链接超时，请重试";
				}elseif(substr_count("jpg_png_jpeg_gif",$extname)==0){
					$result="error";
					return "请上传图片文件";
				}elseif(substr_count($p_type,"image")==0 && substr_count($p_type,"application")==0){
					$result="error";
					return "请上传图片文件!";
				}elseif($p_size>($size*1024)){
					$result="error";
					return "图片大小不能超过".$size."KB";
				}else{
					if(!is_uploaded_file($p_tmp)){
						$result="error";
						return "系统链接超时，请重试";			
					}else{
						if(!is_dir($root."public/upload")){
							mkdir($root."public/upload");
						}
						if(!is_dir($root."public/upload/images")){
							mkdir($root."public/upload/images");
						}
						if(!is_dir($root."public/upload/images/".date("Ymd"))){
							mkdir($root."public/upload/images/".date("Ymd"));
						}
						$newname="recson_".date("YmdHis").substr(microtime(),2,8).rand(0,99999).".".$extname;
						$newpic=$root."public/upload/images/".date("Ymd")."/".$newname;
						$newurl="public/upload/images/".date("Ymd")."/".$newname;
						if($file1==""){
							$newpic=$root."public/upload/images/".date("Ymd")."/".$newname;
							$newurl="public/upload/images/".date("Ymd")."/".$newname;						
						}else{
							$newpic=$root.$file1;
							$newurl=$file1;						
						}
						//die($newpic);
					//	die($p_tmp);
						if(move_uploaded_file($p_tmp,$newpic)){
							$result="ok";
							if($resize==""){
								$this->caijian($newurl);
							}
							return $newurl;					
						}else{
							$result="error";
							return "系统链接超时，请重试";					
						}
					}
				}
			}
		}
        

		
		public function json_upload($file,&$result,$upload=null,$file1=null,$size=null){
			//自定义上传图片函数
			//print_r($file);die();
			$root=FCPATH;
			$size==""?$size=8048:$size=$size;
			$p_name=$file["name"];
			$p_type=$file["type"];
			$p_tmp=$file["tmp_name"];
			$p_error=$file["error"];
			$p_size=$file["size"];
			$extname=strtolower(substr($p_name,strrpos($p_name,".")+1,1000));
			if($upload=="" && $extname==""){
				if($file1==""){
					$result="ok";
					return "";				
				}else{
					$result="ok";
					return $file1;
				}
			}else{
				if($extname==""){
					$result="error";
					return "请选择上传文件";				
				}elseif($p_error>0){
					$result="error";
					return "系统故障,请重试";
				}elseif(substr_count("jpg_png_jpeg_gif",$extname)==0){
					$result="error";
					return "请上传图片文件";
				}elseif(substr_count($p_type,"image")==0){
					$result="error";
					return "请上传图片文件";
				}elseif($p_size>($size*1024)){
					$result="error";
					return "图片超过".$size."KB";
				}else{
					if(!is_uploaded_file($p_tmp)){
						$result="error";
						return "系统故障，请重试";			
					}else{
						if(!is_dir($root."public/upload")){
							mkdir($root."public/upload");
						}
						if(!is_dir($root."public/upload/images")){
							mkdir($root."public/upload/images");
						}
						if(!is_dir($root."public/upload/images/".date("Ymd"))){
							mkdir($root."public/upload/images/".date("Ymd"));
						}
						$newname="recson_".date("YmdHis").substr(microtime(),2,8).rand(0,99999).".".$extname;
						$newpic=$root."public/upload/images/".date("Ymd")."/".$newname;
						$newurl="public/upload/images/".date("Ymd")."/".$newname;
						if($file1==""){
							$newpic=$root."public/upload/images/".date("Ymd")."/".$newname;
							$newurl="public/upload/images/".date("Ymd")."/".$newname;						
						}else{
							$newpic=$root.$file1;
							$newurl=$file1;						
						}
						if(move_uploaded_file($p_tmp,$newpic)){
							$result="ok";
							$this->caijian($newurl);
							return $newurl;
						}else{
							$result="error";
							return "系统故障，请重试";			
						}
					}
				}
			}
		}
		
		public function caijian($images){
			error_reporting(0);
			$images=ltrim($images,"/");			
			$files=strtolower(substr($images,strrpos($images,"/")+1,1000));			
			$extname=strtolower(substr($files,strrpos($files,".")+1,1000));
			$maxwidth="800";//设置图片的最大宽度
			$maxheight="800";//设置图片的最大高度
			$name=strtolower(substr($images,0,strrpos($images,".")));//图片的名称，随便取吧
			$filetype=".".$extname;//图片类型
			if($extname=="jpg" || $extname=="jpeg"){
				$im=imagecreatefromjpeg(FCPATH."/".$images);//参数是图片的存方路径
			}elseif($extname=="png"){
				$im=imagecreatefrompng(FCPATH."/".$images);//参数是图片的存方路径
			}elseif($extname=="gif"){
				$im=imagecreatefromgif(FCPATH."/".$images);//参数是图片的存方路径
			}
			$this->resizeImage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数
		}
		
		public function resizeImage($im,$maxwidth,$maxheight,$name,$filetype)
		 {
			 error_reporting(0);
		  $pic_width = imagesx($im);
		  $pic_height = imagesy($im);
		 
		  if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
		  {
		   if($maxwidth && $pic_width>$maxwidth)
		   {
			$widthratio = $maxwidth/$pic_width;
			$resizewidth_tag = true;
		   }
		 
		   if($maxheight && $pic_height>$maxheight)
		   {
			$heightratio = $maxheight/$pic_height;
			$resizeheight_tag = true;
		   }
		 
		   if($resizewidth_tag && $resizeheight_tag)
		   {
			if($widthratio<$heightratio)
			 $ratio = $widthratio;
			else
			 $ratio = $heightratio;
		   }
		 
		   if($resizewidth_tag && !$resizeheight_tag)
			$ratio = $widthratio;
		   if($resizeheight_tag && !$resizewidth_tag)
			$ratio = $heightratio;
		 
		   $newwidth = $pic_width * $ratio;
		   $newheight = $pic_height * $ratio;
		 
		   if(function_exists("imagecopyresampled"))
		   {
			$newim = imagecreatetruecolor($newwidth,$newheight);//PHP系统函数
			  imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);//PHP系统函数
		   }
		   else
		   {
			$newim = imagecreate($newwidth,$newheight);
			  imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
		   }
		 
		   $name = $name.$filetype;
		   imagejpeg($newim,$name);
		   imagedestroy($newim);
		  }
		  else
		  {
		   $name = $name.$filetype;
		   imagejpeg($im,$name);
		  }
		 }		
	}

?>