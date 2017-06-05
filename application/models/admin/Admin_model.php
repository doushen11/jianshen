<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Admin_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}


		public function get_admins()
		{
			//获取管理员信息
			return $this->db->query("select * from `dg_admin` order by `id` desc");	
		}
		
		public function adds_subs($username,$passwd)
		{
			//添加一条管理员信息
			$query=$this->db->query("select `id` from `dg_admin` where `username`='$username' limit 1");
			if($query->num_rows()>0)
			{
				iframes("30000","抱歉：当前登录用户名已经被占用！");
			}
			else
			{
				$keys=mt_rand(10000000,99999999);
				$_array=array(
					"username"=>$username,
					"passwd"=>sha1(sha1($passwd).$keys),
					"keys"=>$keys,
					"login_time"=>time(),
					"login_ip"=>ip2long(get_ip()),
					"last_time"=>time(),
					"last_ip"=>ip2long(get_ip()),
				);	
				if($this->db->insert("admin",$_array))
				{
					ajaxs("10000","添加成功！");	
				}
				else
				{
					ajaxs("30000","抱歉：当前程序出了点故障，我们已经抓紧处理了，请您稍后再试！");	
				}
			}
		}
		
		public function edits_subs($id,$passwd)
		{
			//修改一条管理员信息
			$query=$this->db->query("select `id`,`keys` from `dg_admin` where `id`='$id' limit 1");
			if($query->num_rows()<0)
			{
				ajaxs("30000","抱歉：当前管理员可能被删除！");
			}
			else
			{
				$result=$query->row_array();
				$_array=array(
					"passwd"=>sha1(sha1($passwd).$result["keys"]),
				);	
				if($this->db->update("admin",$_array,array("id"=>$id)))
				{
					ajaxs("10000","修改成功！");	
				}
				else
				{
					ajaxs("30000","抱歉：当前程序出了点故障，我们已经抓紧处理了，请您稍后再试！");	
				}
			}	
		}
		
		public function dels($id)
		{
			//删除管理员信息
			if($this->db->query("delete from `dg_admin` where `id` in (".$id.")"))
			{
				ajaxs(10000,"删除成功");		
			}	
			ajaxs(30000,"网络连接失败");	
		}
			
		//关于我们上传图片
		public function abouts_uploads()
		{
			$file=img_upload($_FILES["files"],$result,1,"","","1024");
			if($result=="ok")
			{
				iframes("10000",$file);	
			}
			else
			{
				iframes("30000",$file);		
			}			
		}	
		
		//更新关于我们欣喜
		public function abouts_subs()
		{
			$_POST["time"]=time();
			//print_r($_POST);die();
			if($this->db->update("abouts",$_POST,array("id"=>1)))
			{
				ajaxs("10000","更新成功");
			}
			else
			{
				ajaxs("30000","网络连接失败");
			}
		}
		
		//健身房信息上传图片
		public function houses_uploads()
		{
			$act=intval($this->uri->segment(4));
			$file=img_upload($_FILES["files"],$result,1,"","","1024");
			if($result=="ok")
			{
				if($act==2)
				{
					iframes("10000",$file,"stopUpload1");
				}
				elseif($act==3)
				{
					iframes("10000",$file,"stopUpload2");
				}
				else
				{
					iframes("10000",$file);
				}	
			}
			else
			{
				if($act==2)
				{
					iframes("30000",$file,"stopUpload1");
				}
				elseif($act==3)
				{
					iframes("30000",$file,"stopUpload2");
				}
				else
				{
					iframes("30000",$file);
				}	
			}				
		}
		
		//健身房信息更新
		public function houses_subs()
		{
			if($this->db->update("config",$_POST,array("id"=>1)))
			{
				ajaxs("10000","更新成功");
			}
			else
			{
				ajaxs("30000","网络连接失败");
			}		
		}
		
		//更新进门价格表信息
		public function opens_subs($text,$id)
		{
			$arr=explode("|_|",$text);
			foreach($arr as $k=>$v)
			{
				$arrs=explode("{syx}",$v);
				$_array=array(
					"min"=>trim($arrs[1]),
					"max"=>trim($arrs[2]),
					"money_peak"=>trim($arrs[3]),
					"money_slack"=>trim($arrs[4]),
					"time"=>time(),
				);
				if($arrs[0]=="recsons")
				{
					$this->db->insert("pay_model",$_array);
				}
				elseif(is_numeric($arrs[0]))
				{
					$this->db->update("pay_model",$_array,array("id"=>trim($arrs[0])));
				}
			}
			if(trim($id)!="")
			{
				$id=trim($id,",");
				$this->db->query("delete from `dg_pay_model` where `id` in (".$id.")");
			}
			ajaxs(10000,"更新成功");
		}
		
		//删除空格
		function trimall($str)
		{
		    $qian=array(" ","　","\t","\n","\r");
		    $hou=array("","","","","");
		    return str_replace($qian,$hou,$str);
		}
		
		//添加轮播图
		public function shufs_inserts($file,$alt,$act,$content)
		{
		    $str = '';
		    $url = '';
		    $txt = $this->trimall($content);
		    if(!empty($txt)) {
    		    $str = $content;
		    }
		    
			$_array=array(
				"alt"=>$alt,
				"file"=>$file,
				"sort"=>"0",
				"act"=>$act,
				"times"=>time(),
				"content"=>$str,
			);	
			if($this->db->insert("shuf",$_array))
			{
				$id=$this->db->insert_id();
				if(!empty($txt)) {
				    $url = base_url().'index.php/html5/home/show_detail_page?type=shuf&id='.$id;
				}
				$this->db->query('update dg_shuf set sort='.$id .',url="'.$url.'" where id='.$id);
				ajaxs(10000,"添加成功");
			}
			else
			{
				ajaxs(30000,"网络连接失败");	
			}
		}
		
		//修改轮播图
		public function shufs_updates($file,$alt,$act,$content,$id)
		{
		    $str = '';
		    $url = '';
		    $txt = $this->trimall($content);
		    if(!empty($txt)) {
		        $str = $content;
    		    $shuf = $this->db->query('select url from dg_shuf where id='.$id.' limit 1')->row_array();
    		    $url = $shuf['url'];
    		    if(empty($url)) {
    		        $url = base_url().'index.php/html5/home/show_detail_page?type=shuf&id='.$id;
    		    }
		    }
			$_array=array(
				"alt"=>$alt,
				"file"=>$file,
				"act"=>$act,
				"times"=>time(),
				"content"=>$str,
				"url"=>$url,
			);	
			if($this->db->update("shuf",$_array,array("id"=>$id)))
			{
				ajaxs(10000,"修改成功");	
			}
			else
			{
				ajaxs(30000,"网络连接失败");	
			}				
		}
		
		//删除轮播图
		public function shufs_dels($id)
		{
			//echo $id;die();
			if($this->db->query("delete from `dg_shuf` where `id` in (".$id.")"))
			{
				ajaxs(10000,"删除成功");	
			}
			else
			{
				ajaxs(30000,"网络连接失败");	
			}				
		}
		
		//更新配置文件
		public function systems_subs()
		{
			//图片配置
			require FCPATH."config/img.inc.php";
			$str="<?php";$str.="\n";$str.='/*';$str.="\n";$str.=' QQ:Recson';$str.="\n";
			$str.=' recson@qq.com';$str.="\n";$str.='*/';$str.="\n";$str.='	$img_inc=array(';
			$str.="\n";$str.='		"avatar"=>"'.(trim($_POST["tupian"])).'",//系统会员默认头像';$str.="\n";
			$str.="\n";$str.='		"bg"=>"'.(trim($img_inc["bg"])).'",//系统默认教练背景图';$str.="\n";
			$str.="\n";$str.='		"j_notice_bg"=>"'.(trim($_POST["tupian1"])).'",//教练端APP公告背景图';$str.="\n";
			$str.='		"k_notice_bg"=>"'.(trim($_POST["tupian2"])).'",//客户端APP公告背景图';$str.="\n";
			$str.="\n";$str.='	);';$str.="\n";$str.="?>";	
			$file=FCPATH."config/img.inc.php";
			$ff=fopen($file,"w+");	
			
			//系统配置
			$str1="<?php";$str1.="\n";$str1.='/*';$str1.="\n";$str1.=' QQ:Recson';$str1.="\n";
			$str1.=' recson@qq.com';$str1.="\n";$str1.='*/';$str1.="\n";$str1.='	$_sys_inc=array(';
			$str1.="\n";$str1.='		"doors_time"=>"'.intval(trim($_POST["doors_time"])).'",//进门扫码延时秒数';$str1.="\n";
			$str1.="\n";$str1.='		"captcha_time"=>"'.intval(trim($_POST["captcha_time"])).'",//两次短信发送间隔次数';$str1.="\n";
			$str1.="\n";$str1.='		"captcha_lives"=>"'.intval(trim($_POST["captcha_lives"])).'",//验证码的时效性，默认1800秒，即半小时';$str1.="\n";
			$str1.="\n";$str1.='		"class_close_time_a"=>"'.intval(trim($_POST["class_close_time_a"])).'",//开课前多久不允许取消课程时间--私课';$str1.="\n";
			$str1.="\n";$str1.='		"class_close_time_b"=>"'.intval(trim($_POST["class_close_time_b"])).'",//开课前多久没达到人数取消课程时间--操课';$str1.="\n";
			$str1.="\n";$str1.='		"class_insert_count_day"=>"'.intval(trim($_POST["class_insert_count_day"])).'",//每日最多允许发布课程的数量';$str1.="\n";
			$str1.="\n";$str1.='		"a_http"=>"'.(trim($_POST["a_http"])).'",//安卓下载地址';$str1.="\n";
			$str1.="\n";$str1.='		"i_http"=>"'.(trim($_POST["i_http"])).'",//IOS下载地址';$str1.="\n";	
			$str1.="\n";$str1.='		"code_reg"=>"'.(trim($_POST["code_reg"])).'",//注册短信验证码';$str1.="\n";
			$str1.="\n";$str1.='		"code_login"=>"'.(trim($_POST["code_login"])).'",//登录短信验证码';$str1.="\n";
			$str1.="\n";$str1.='		"code_reset"=>"'.(trim($_POST["code_reset"])).'",//找回密码短信验证码';$str1.="\n";			
			$str1.="\n";$str1.='	);';$str1.="\n";$str1.="?>";	
			$file1=FCPATH."config/sys.inc.php";
			$ff1=fopen($file1,"w+");
			
			//奖励配置
			$str2="<?php";$str2.="\n";$str2.='/*';$str2.="\n";$str2.=' QQ:Recson';$str2.="\n";
			$str2.=' recson@qq.com';$str2.="\n";$str2.='*/';$str2.="\n";$str2.='	$money_inc=array(';
			$str2.="\n";$str2.='		"register"=>"'.(trim($_POST["register"])).'",//注册邀请奖励金额';$str2.="\n";
			$str2.="\n";$str2.='		"comment"=>"'.(trim($_POST["comment"])).'",//评价奖励金额';$str2.="\n";
			$str2.="\n";$str2.='		"reg"=>"'.(trim($_POST["reg"])).'",//注册赠送金额';$str2.="\n";
			$str2.="\n";$str2.='	);';$str2.="\n";$str2.="?>";	
			$file2=FCPATH."config/money_inc.php";
			$ff2=fopen($file2,"w+");
			
			
			//升级配置
			$str3="<?php";$str3.="\n";$str3.='/*';$str3.="\n";$str3.=' QQ:Recson';$str3.="\n";
			$str3.=' recson@qq.com';$str3.="\n";$str3.='*/';$str3.="\n";$str3.='	$soft_inc=array(';
			$str3.="\n";$str3.='		"k_verson"=>"'.(trim($_POST["k_verson"])).'",//客户端版本号';$str3.="\n";
			$str3.="\n";$str3.='		"k_downloads"=>"'.(trim($_POST["k_downloads"])).'",//客户端下载地址';$str3.="\n";
			$str3.="\n";$str3.='		"j_verson"=>"'.(trim($_POST["j_verson"])).'",//教练端版本号';$str3.="\n";
			$str3.="\n";$str3.='		"j_downloads"=>"'.(trim($_POST["j_downloads"])).'",//教练端下载地址';$str3.="\n";
			$str3.="\n";$str3.='	);';$str3.="\n";$str3.="?>";	
			$file3=FCPATH."config/soft.inc.php";
			$ff3=fopen($file3,"w+");
			
			
			//地图配置
			$str4="<?php";$str4.="\n";$str4.='/*';$str4.="\n";$str4.=' QQ:Recson';$str4.="\n";
			$str4.=' recson@qq.com';$str4.="\n";$str4.='*/';$str4.="\n";$str4.='	$address_array=array(';
			$str4.="\n";$str4.='		"longitude"=>"'.(trim($_POST["longitude"])).'",//健身房经度';$str4.="\n";
			$str4.="\n";$str4.='		"latitude"=>"'.(trim($_POST["latitude"])).'",//健身房维度';$str4.="\n";
			$str4.="\n";$str4.='	);';$str4.="\n";$str4.="?>";	
			$file4=FCPATH."config/address.inc.php";
			$ff4=fopen($file4,"w+");
			
			
			if(fwrite($ff,$str) && fwrite($ff1,$str1) && fwrite($ff2,$str2) && fwrite($ff3,$str3) && fwrite($ff4,$str4))
			{
				ajaxs(10000,"更新成功！");	
			}
			else
			{
				ajaxs(30000,"更新失败，如果是linux，请检测您的根目录下的config文件夹是否有可写权限");
			}
					
		}
		
		
		public function pushs_subs()
		{
			//更新推送配置信息
			$str="<?php";$str.="\n";$str.='/*';$str.="\n";$str.=' QQ:Recson';$str.="\n";
			$str.=' recson@qq.com';$str.="\n";$str.='*/';$str.="\n";$str.='	$push_inc=array(';
			$str.="\n";$str.='		"door_title"=>"'.(trim($_POST["door_title"])).'",//开门余额不足100元进行提醒标题';$str.="\n";
			$str.="\n";$str.='		"door_message"=>"'.(trim($_POST["door_message"])).'",//开门余额不足100元进行提醒内容';$str.="\n";
			$str.="\n";$str.='		"money_title"=>"'.(trim($_POST["money_title"])).'",//不足100元提醒充值标题';$str.="\n";
			$str.='		"money_message"=>"'.(trim($_POST["money_message"])).'",//不足100元提醒充值内容';$str.="\n";
			$str.="\n";$str.='		"pay_title"=>"'.(trim($_POST["pay_title"])).'",//充值成功后推送信息标题';$str.="\n";
			$str.='		"pay_message"=>"'.(trim($_POST["pay_message"])).'",//充值成功后推送信息内容';$str.="\n";
			$str.="\n";$str.='		"register_title"=>"'.(trim($_POST["register_title"])).'",//邀请好友奖励信息标题';$str.="\n";
			$str.='		"register_message"=>"'.(trim($_POST["register_message"])).'",//邀请好友奖励信息内容';$str.="\n";
			$str.="\n";$str.='		"comment_title"=>"'.(trim($_POST["comment_title"])).'",//课程结束点评获取奖励通知标题';$str.="\n";
			$str.='		"comment_message"=>"'.(trim($_POST["comment_message"])).'",//课程结束点评获取奖励通知内容';$str.="\n";
			$str.="\n";$str.='		"class_title"=>"'.(trim($_POST["class_title"])).'",//私课成立学员通知';$str.="\n";
			$str.='		"class_message"=>"'.(trim($_POST["class_message"])).'",//私课成立学员通知内容';$str.="\n";
			$str.="\n";$str.='		"class_cl_title"=>"'.(trim($_POST["class_cl_title"])).'",//私课成立教练通知';$str.="\n";
			$str.='		"class_cl_message"=>"'.(trim($_POST["class_cl_message"])).'",//私课成立教练通知内容';$str.="\n";
			$str.="\n";$str.='		"class_clear_title"=>"'.(trim($_POST["class_clear_title"])).'",//私课取消学员通知';$str.="\n";
			$str.='		"class_clear_message"=>"'.(trim($_POST["class_clear_message"])).'",//私课取消学员通知内容';$str.="\n";
			$str.="\n";$str.='		"class_clear_teacher_title"=>"'.(trim($_POST["class_clear_teacher_title"])).'",//私课取消教练通知';$str.="\n";
			$str.='		"class_clear_teacher_mssage"=>"'.(trim($_POST["class_clear_teacher_mssage"])).'",//私课取消教练通知内容';$str.="\n";
			$str.="\n";$str.='		"ck_class_title"=>"'.(trim($_POST["ck_class_title"])).'",//操课成立学员提醒';$str.="\n";
			$str.='		"ck_class_message"=>"'.(trim($_POST["ck_class_message"])).'",//操课成立学员提醒';$str.="\n";
			$str.="\n";$str.='		"ck_class_cl_title"=>"'.(trim($_POST["ck_class_cl_title"])).'",//操课成立教练提醒';$str.="\n";
			$str.='		"ck_class_cl_message"=>"'.(trim($_POST["ck_class_cl_message"])).'",//操课成立教练提醒';$str.="\n";
			$str.="\n";$str.='		"ck_class_qx_title_member"=>"'.(trim($_POST["ck_class_qx_title_member"])).'",//操课取消学员提醒';$str.="\n";
			$str.='		"ck_class_qx_message_member"=>"'.(trim($_POST["ck_class_qx_message_member"])).'",//操课取消学员提醒';$str.="\n";
			$str.="\n";$str.='		"ck_class_qx_title"=>"'.(trim($_POST["ck_class_qx_title"])).'",//操课取消教练提醒';$str.="\n";
			$str.='		"ck_class_qx_message"=>"'.(trim($_POST["ck_class_qx_message"])).'",//操课取消教练提醒';$str.="\n";
			
			$str.="\n";$str.='		"ck_no_class_title"=>"'.(trim($_POST["ck_no_class_title"])).'",//上传课程提醒通知';$str.="\n";
			$str.='		"ck_no_class_message"=>"'.(trim($_POST["ck_no_class_message"])).'",//上传课程提醒通知';$str.="\n";
			
			$str.="\n";$str.='		"class_wancheng_title"=>"'.(trim($_POST["class_wancheng_title"])).'",//课程完成给教练通知';$str.="\n";
			$str.='		"class_wancheng_message"=>"'.(trim($_POST["class_wancheng_message"])).'",//课程完成给教练通知';$str.="\n";
			
			$str.="\n";$str.='		"class_wancheng_ctitle"=>"'.(trim($_POST["class_wancheng_ctitle"])).'",//课程完成给学员通知';$str.="\n";
			$str.='		"class_wancheng_cmessage"=>"'.(trim($_POST["class_wancheng_cmessage"])).'",//课程完成给学员通知';$str.="\n";
			
			
			
			$str.="\n";$str.='	);';$str.="\n";$str.="?>";	
			$file=FCPATH."config/push.inc.php";
			$ff=fopen($file,"w+");

			if(fwrite($ff,$str))
			{
				ajaxs(10000,"更新成功！");	
			}
			else
			{
				ajaxs(30000,"更新失败，如果是linux，请检测您的根目录下的config文件夹是否有可写权限");
			}
		}
	}
	
	