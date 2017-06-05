<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Array Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/array_helper.html
 */

// ------------------------------------------------------------------------

if(!function_exists('message_insert'))
{
	function message_insert($title,$content,$uid,$db,$act="")
	{
		$table="dg_message_u";
		if($act!="")
		{
			$table="dg_message_t";
		}
		//开始CURL发送数据
		$sql="insert into `".$table."`(`uid`,`title`,`contents`,`read`,`time`)value('$uid','$title','$content','1','".time()."')";
		$db->query($sql);
	}	
}

if(!function_exists('msn'))
{
	function msn($mobile,$msg)
	{
		$urls="http://222.73.117.156/msg/HttpBatchSendSM?account=fjfj1688&pswd=Tch891210&mobile=".$mobile."&msg=".urlencode($msg)."&needstatus=true";
		//echo $urls;die();
		$ch = curl_init($urls) ;  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
		curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		$output = curl_exec($ch);
		//echo $output;
		curl_close($ch);			
	}	
}

if(!function_exists('c_push'))
{
	function c_push($array)
	{
		$urls=base_url()."push/client_one_push.php?";
		$strs="";
		foreach($array as $k=>$v)
		{
			$strs.="&".$k."=".urlencode($v);	
		}	
		$strs=trim($strs,"&");
		$urls=$urls.($strs);
		$ch = curl_init($urls) ;  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
		curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		$output = curl_exec($ch);
		//echo $output;
		curl_close($ch);
	}
}

if(!function_exists('c_all_push'))
{
	function c_all_push($array)
	{
		$urls=base_url()."push/client_group_push.php?";
		$strs="";
		foreach($array as $k=>$v)
		{
			$strs.="&".$k."=".urlencode($v);	
		}	
		$strs=trim($strs,"&");
		$urls=$urls.($strs);
		$ch = curl_init($urls) ;  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
		curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		$output = curl_exec($ch);
		//echo $output;
		curl_close($ch);
	}
}

if(!function_exists('a_push'))
{
	function a_push($array)
	{
		$urls=base_url()."push/master_one_push.php?";
		$strs="";
		foreach($array as $k=>$v)
		{
			$strs.="&".$k."=".urlencode($v);	
		}	
		$strs=trim($strs,"&");
		$urls=$urls.($strs);
		$ch = curl_init($urls) ;  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
		curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		$output = curl_exec($ch);
		//echo $output;
		curl_close($ch);
	}
}

if(!function_exists('a_all_push'))
{
	function a_all_push($array)
	{
		$urls=base_url()."push/master_group_push.php?";
		$strs="";
		foreach($array as $k=>$v)
		{
			$strs.="&".$k."=".urlencode($v);	
		}	
		$strs=trim($strs,"&");
		$urls=$urls.($strs);
		$ch = curl_init($urls) ;  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
		curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		$output = curl_exec($ch);
		echo $output;
		curl_close($ch);
	}
}

if(!function_exists('send_captcha'))
{
	function send_captcha($mobile,$captcha,$time,$tmp_id)
	{
		$time=intval($time/60);
		$urls=base_url()."SendTemplateSMS.php?tokens=shenxiaosucsuisomnab%saiuasd*JJGFD&mobile=".$mobile."&captcha=".$captcha."&time=".$time."&tmp_id=".$tmp_id;
		$ch = curl_init($urls) ;  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
		curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		$output = curl_exec($ch);
		curl_close($ch);
	}	
}

if(!function_exists('shows'))
{
	function shows($text)
	{
		echo $text;die();	
	}	
}

if(!function_exists('iframes'))
{
	//frame输出
	function iframes($number,$desc,$funcs=null)
	{
		if($funcs=="")
		{
			$funcs="stopUpload";	
		}
		echo '<script language="javascript" type="text/javascript">window.parent.window.'.$funcs.'("'.$number.'|'.$desc.'");</script>';exit();	
	}	
}

if(!function_exists('ajaxs'))
{
	function ajaxs($a,$b)
	{
		echo $a."|".$b;exit();	
	}	
}

if(!function_exists("error_show"))
{
	function error_show()
	{
		//数据传输不完整的报错程序
		//json_array2("30000","您忒厉害了，程序都被您跑趴下来了，请您歇会，下次来我们智能健身房好好跑跑！","");
		json_array2("30000","抱歉，系统累了，请让系统歇会吧！","");
	}
}

if(!function_exists("error_token_show"))
{
	function error_token_show()
	{
		//身份核对失败的报错程序
		json_array2("20000","系统没有读取到您的身份状态哦，请您稍后再试！","login");
	}
}


if(!function_exists('removers')){
	//删除已上传的图片信息
	function removers($arr){
		//删除图片及	
		for($i=0;$i<count($arr);$i++){
			$v=$arr[$i]["files"];
			@unlink(FCPATH."/".trim($v,"/"));
		}
	}
}

if (!function_exists('is_fulls'))
{
	//对对应的信息进行检测
	function is_fulls($str,$model=null)
	{
		if(isset($_REQUEST[$str]) && trim($_REQUEST[$str])!=""){
			if($model!=""){
				if(intval(trim($_REQUEST[$str]))>0){
					return true;
				}else{
					//echo $str;die();
					return false;	
				}	
			}
			return true;	
		}
		//echo $str;die();
		return false;
	}
}

if(! function_exists('http_url')){
	//获取对应的路由地址
	function http_url(){
		return base_url()."index.php/";
	}
}

if(!function_exists('user_agent')){
	//获取发布者的浏览器信息
	function user_agent(){
		return isset($_SERVER["HTTP_USER_AGENT"]) && $_SERVER["HTTP_USER_AGENT"]!="" ?$_SERVER["HTTP_USER_AGENT"]:"未知";
	}
}

if ( ! function_exists('create_token'))
{
	//创建token
	function create_token($keys=null){
		return $keys==""?sha1(date("YmdHis").md5("recsons")).md5(date("Y-m-d H:i:s"."xixixiaoyu")):sha1(date("YmdHis").md5("recsons").$keys).md5(date("Y-m-d H:i:s"."xixixiaoyu").$keys);
	}
}

if(! function_exists('get_ip')){
	//获取ip地址
	function get_ip(){
		if(getenv('HTTP_CLIENT_IP')){ 
			$ip=getenv('HTTP_CLIENT_IP'); 
		}elseif(getenv('HTTP_X_FORWARDED_FOR')){ 
			$ip=getenv('HTTP_X_FORWARDED_FOR'); 
		}elseif(getenv('HTTP_X_FORWARDED')){
			$ip=getenv('HTTP_X_FORWARDED'); 
		}elseif(getenv('HTTP_FORWARDED_FOR')){ 
			$ip=getenv('HTTP_FORWARDED_FOR');
		}elseif(getenv('HTTP_FORWARDED')){ 
			$ip=getenv('HTTP_FORWARDED'); 
		}else{ 
			$ip=$_SERVER['REMOTE_ADDR']; 
		} 
		return $ip; 
	}
}

if ( ! function_exists('right_index'))
{
	//获取索引查询键值
	function right_index($str)
	{
		if($str==""){
			return 0;	
		}else{
			if(strlen($str)==1){
				return $str;	
			}else{
				return substr($str,strlen($str)-1,1);
			}	
		}			
	}
}

if ( ! function_exists('unhtml'))
{
	//格式化html文件
	function unhtml($str){								//定义自定义函数的名称
		$str = trim($str); //清除字符串两边的空格
		$str = strip_tags($str,""); //利用php自带的函数清除html格式
		$str = preg_replace("/\t/","",$str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
		$str = preg_replace("/\r\n/","",$str); 
		$str = preg_replace("/\r/","",$str); 
		$str = preg_replace("/\n/","",$str); 
		$str = preg_replace("/ /","",$str);
		$str = preg_replace("/  /","",$str);  //匹配html中的空格
		$str = str_replace("<p>","",$str); //利用php自带的函数清除html格式
		$str = str_replace("</p>","",$str); //利用php自带的函数清除html格式
		$str = str_replace("　","",$str);
		return trim($str); //返回字符串
	}
}

if(!function_exists('encode_json'))
{
	function encode_json($str) {
		return json_encode($str);
	}
}

if(!function_exists('url_encode'))
{
	function url_encode($str) {
		if(is_array($str)) {
			foreach($str as $key=>$value) {
				$str[urlencode($key)] = url_encode($value);
			}
		} else {
			$str = urlencode($str);
		}
	
		return $str;
	}
}

if(!function_exists('json_array'))
{
	function json_array($code,$message,$_array,$type = 0){
		$result_array = array("code"=>$code,"message"=>$message,"resultCode"=>$_array);
		$obj = encode_json($result_array);
		print_json($obj);
	}
}

if(!function_exists('json_array2'))
{
	function json_array2($code,$message,$_array,$type = 0){
		$result_array = array("code"=>$code,"message"=>$message,"resultCode"=>$_array);
		$obj = encode_json($result_array);
		print_json($obj);
		die;
	   
	}
}

if(!function_exists('print_json'))
{
	function print_json($obj){
		echo trim($obj);
	}
}

if(!function_exists('getDistance')){	
	//计算两个经度纬度之间的距离
	function getDistance($lng1,$lat1,$lng2,$lat2) 
	{ 
		$earthRadius = 6367000; 
		$lat1 = ($lat1 * pi() ) / 180; 
		$lng1 = ($lng1 * pi() ) / 180; 
		$lat2 = ($lat2 * pi() ) / 180; 
		$lng2 = ($lng2 * pi() ) / 180; 
		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;
		$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2); 
		$stepTwo = 2 * asin(min(1, sqrt($stepOne))); 
		$calculatedDistance = $earthRadius * $stepTwo; 
		$result=round($calculatedDistance);
		if($result<1000){
			return $result."m";	
		}else{
			return round($result/1000,2)."km";	
		}
	} 
}

if(!function_exists('mv_upload'))
{
	function mv_upload($file,&$result,$upload=null,$file1=null,$size=null)
	{
		//自定义上传图片函数
		//print_r($file);die();
		$root=FCPATH;
		$size==""?$size=8000:$size=$size;
		$p_name  = $file["name"];
		$p_type  = $file["type"];
		$p_tmp   = $file["tmp_name"];
		$p_error = $file["error"];
		$p_size  = $file["size"];
		//dump($file);
		$extname=strtolower(substr($p_name,strrpos($p_name,".")+1,1000));
	  
		if($upload=="" && $extname=="")
		{
			$result="ok";
			return $file1;
		}else{
			//return $p_type;
			if($extname==""){
				$result="error";
				return "请选择上传文件";				
			}elseif($p_error>0){
				$result="error";
				return "上传失败，未知错误";
			}elseif(substr_count("mp4",$extname)==0){
				$result="error";
				return "请上传mp4文件";
			}elseif(substr_count($p_type,"image")==0 && substr_count($p_type,"video")==0){
				$result="error";
				return "请上传视频文件!";
			}elseif($p_size>($size*1024)){
				$result="error";
				return "视频大小不能超过".$size."KB";
			}else{
				if(!is_uploaded_file($p_tmp)){
					$result="error";
					return "上传失败，未知错误";		
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
						return $newurl;					
					}else{
						$result="error";
						return "系统链接超时，请重试";					
					}
				}
			}
		}
	}
}

if(!function_exists('img_upload'))
{
	function img_upload($file,&$result,$upload=null,$file1=null,$tailoring=null,$size=null,$maxwidth=null,$maxheight=null)
	{
		//自定义上传图片函数
		//print_r($file);die();
		$root=FCPATH;
		$size==""?$size=4000:$size=$size;
		$p_name  = $file["name"];
		$p_type  = $file["type"];
		$p_tmp   = $file["tmp_name"];
		$p_error = $file["error"];
		$p_size  = $file["size"];
		//dump($file);
		$extname=strtolower(substr($p_name,strrpos($p_name,".")+1,1000));
	  
		if($upload=="" && $extname=="")
		{
			$result="ok";
			return $file1;
		}else{

			if($extname==""){
				$result="error";
				return "请选择上传文件";				
			}elseif($p_error>0){
				$result="error";
				return "上传失败，未知错误";
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
					return "上传失败，未知错误";		
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
						$tailoring!=""?img_tailoring($newurl,$maxwidth,$maxheight):"";
						return $newurl;					
					}else{
						$result="error";
						return "系统链接超时，请重试";					
					}
				}
			}
		}
	}
}

if(!function_exists('img_tailoring'))
{
	function img_tailoring($images,$maxwidth=null,$maxheight=null)
	{
		error_reporting(0);
		$images=ltrim($images,"/");
		$files=strtolower(substr($images,strrpos($images,"/")+1,1200));
		$extname=strtolower(substr($files,strrpos($files,".")+1,1200));
		if($maxwidth==""){
			$maxwidth="600";//设置图片的最大宽度
		}
		if($maxheight==""){
			$maxheight="600";//设置图片的最大高度
		}
		
		$name=strtolower(substr($images,0,strrpos($images,".")));//图片的名称，随便取吧
		$filetype=".".$extname;//图片类型
		if($extname=="jpg" || $extname=="jpeg")
		{
			$im=imagecreatefromjpeg(FCPATH."/".$images);//参数是图片的存方路径
		}
		elseif($extname=="png")
		{
			$im=imagecreatefrompng(FCPATH."/".$images);//参数是图片的存方路径
		}
		elseif($extname=="gif")
		{
			$im=imagecreatefromgif(FCPATH."/".$images);//参数是图片的存方路径
		}
		img_resizeimage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数	
	}
}

if(!function_exists('img_resizeimage'))
{
	function img_resizeimage($im,$maxwidth,$maxheight,$name,$filetype)
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
	
	if(! function_exists('I'))
	{
		function I($str)
		{
			return htmlspecialchars(trim($_REQUEST[$str]));
		}
	}	
	
	if(! function_exists('success'))
	{
		function success($text,$url=null,$time=null)
		{
			require FCPATH."public/views/success.php";
		}
	}
	
	if(! function_exists('error'))
	{
		function error($text,$url=null,$time=null)
		{
			require FCPATH."public/views/error.php";
		}
	}
}