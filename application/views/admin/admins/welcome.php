<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/html5.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/respond.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>我的桌面</title>
</head>
<body>
<div class="page-container">
	<p class="f-20 text-success">欢迎使用智能健身后台管理系统 <span class="f-14">v1.0</span>！</p>
	<p>登录次数：<?php echo $rs["counts"];?>  &nbsp;&nbsp;&nbsp;&nbsp; 当前健身房里人数： <strong style="color:#F00;"><?php $t=$this->db->query("select `people` from `dg_config` where `id`='1'");$r=$t->row_array();echo $r["people"];?></strong> </p>
	<p>上次登录IP：<?php echo long2ip($rs["last_ip"]);?> &nbsp;&nbsp;&nbsp;&nbsp; 上次登录时间：<?php echo date("Y-m-d H:i:s",$rs["last_time"]);?></p>
    
    <?php
    	$today_s=strtotime(date("Y-m-d")." 00:00:00");
		$today_e=strtotime(date("Y-m-d")." 23:59:59");
		
    	$yestday_s=strtotime(date("Y-m-d")." 00:00:00")-24*3600;
		$yestday_e=strtotime(date("Y-m-d")." 23:59:59")-24*3600;
		
		//echo strtotime('last monday');
		
		//这个星期的星期一
		// @$timestamp ，某个星期的某一个时间戳，默认为当前时间
		// @is_return_timestamp ,是否返回时间戳，否则返回时间格式
		function this_monday($timestamp=0,$is_return_timestamp=true){
			static $cache ;
			$id = $timestamp.$is_return_timestamp;
			if(!isset($cache[$id])){
				if(!$timestamp) $timestamp = time();
				$monday_date = date('Y-m-d', $timestamp-86400*date('w',$timestamp)+(date('w',$timestamp)>0?86400:-/*6*86400*/518400));
				if($is_return_timestamp){
					$cache[$id] = strtotime($monday_date);
				}else{
					$cache[$id] = $monday_date;
				}
			}
			return $cache[$id];
		
		}
		
		//这个星期的星期天
		// @$timestamp ，某个星期的某一个时间戳，默认为当前时间
		// @is_return_timestamp ,是否返回时间戳，否则返回时间格式
		function this_sunday($timestamp=0,$is_return_timestamp=true){
			static $cache ;
			$id = $timestamp.$is_return_timestamp;
			if(!isset($cache[$id])){
				if(!$timestamp) $timestamp = time();
				$sunday = this_monday($timestamp) + /*6*86400*/518400;
				if($is_return_timestamp){
					$cache[$id] = $sunday;
				}else{
					$cache[$id] = date('Y-m-d',$sunday);
				}
			}
			return $cache[$id];
		}
		
		$w_s=this_monday();
		$w_e=this_sunday();
		
		$m_s=strtotime(date("Y-m-"."01")." 00:00:00");
		$m_e=strtotime(date("Y-m-".date("t"))." 23:59:59");
		
	?>
  <table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th colspan="7" scope="col">信息统计</th>
			</tr>
			<tr class="text-c">
				<th>统计</th>
				<th>会员</th>
				<th>充值(含系统赠送)</th>
				<th>私课</th>
				<th>操课</th>
				<th>进门</th>
			</tr>
		</thead>
		<tbody>
			<tr class="text-c">
				<td>总数</td>
				<td>
                <?php
                	//今日会员
					$query=$this->db->query("select count(id) as `c` from `dg_user`");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>                
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select sum(money) as `c` from `dg_pay_order`");
					$result=$query->row_array();
					echo sprintf("%.2f",$result["c"]);
				?>                 
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plan_list`");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>                 
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plans`");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_doors`");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
                </td>
			</tr>
			<tr class="text-c">
				<td>今日</td>
				<td>
                <?php
                	//今日会员
					$query=$this->db->query("select count(id) as `c` from `dg_user` where `reg_time`>='$today_s' and `reg_time`<='$today_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
                </td>
				<td>
				<?php
                	//今日充值
					$query=$this->db->query("select sum(money) as `c` from `dg_pay_order` where `time`>='$today_s' and `time`<='$today_e'");
					$result=$query->row_array();
					echo sprintf("%.2f",$result["c"]);
				?>                
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plan_list` where `start_time`>='$today_s' and `start_time`<='$today_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?> 
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plans` where `start_time`>='$today_s' and `start_time`<='$today_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_doors` where (`start_time`>='$today_s' and `start_time`<='$today_e') or (`end_time`>='$today_s' and `end_time`<='$today_e')");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
                </td>
			</tr>
			<tr class="text-c">
				<td>昨日</td>
				<td>
                <?php
                	//昨日会员
					$query=$this->db->query("select count(id) as `c` from `dg_user` where `reg_time`>='$yestday_s' and `reg_time`<='$yestday_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
                </td>
				<td>
				<?php
                	//今日充值
					$query=$this->db->query("select sum(money) as `c` from `dg_pay_order` where `time`>='$yestday_s' and `time`<='$yestday_e'");
					$result=$query->row_array();
					echo sprintf("%.2f",$result["c"]);
				?>                
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plan_list` where `start_time`>='$yestday_s' and `start_time`<='$yestday_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?> 
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plans` where `start_time`>='$yestday_s' and `start_time`<='$yestday_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>                
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_doors` where (`start_time`>='$yestday_s' and `start_time`<='$yestday_e') or (`end_time`>='$yestday_s' and `end_time`<='$yestday_e')");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
                </td>
			</tr>
			<tr class="text-c">
				<td>本周</td>
				<td>
                <?php
                	//本周会员
					$query=$this->db->query("select count(id) as `c` from `dg_user` where `reg_time`>='$w_s' and `reg_time`<='$w_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
                
                </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select sum(money) as `c` from `dg_pay_order` where `time`>='$w_s' and `time`<='$w_e'");
					$result=$query->row_array();
					echo sprintf("%.2f",$result["c"]);
				?>
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plan_list` where `start_time`>='$w_s' and `start_time`<='$w_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
              </td>
				<td>
                 <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plans` where `start_time`>='$w_s' and `start_time`<='$w_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>                
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_doors` where (`start_time`>='$w_s' and `start_time`<='$w_e') or (`end_time`>='$w_s' and `end_time`<='$w_e')");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
                </td>
			</tr>
			<tr class="text-c">
				<td>
				 本月          
              </td>
				<td><?php
                	//本月会员
					$query=$this->db->query("select count(id) as `c` from `dg_user` where `reg_time`>='$m_s' and `reg_time`<='$m_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>  </td>
				<td>
               <?php
                	//今日充值
					$query=$this->db->query("select sum(money) as `c` from `dg_pay_order` where `time`>='$m_s' and `time`<='$m_e'");
					//echo "select sum(money) as `c` from `dg_pay_order` where `time`>='$m_s' and `time`<='$m_e'";
					$result=$query->row_array();
					echo sprintf("%.2f",$result["c"]);
				?> 
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plan_list` where `start_time`>='$m_s' and `start_time`<='$m_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
              </td>
				<td>
                 <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_tearch_plans` where `start_time`>='$m_s' and `start_time`<='$m_e'");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>                 
              </td>
				<td>
                <?php
                	//今日充值
					$query=$this->db->query("select count(id) as `c` from `dg_doors` where (`start_time`>='$m_s' and `start_time`<='$m_e') or (`end_time`>='$m_s' and `end_time`<='$m_e')");
					$result=$query->row_array();
					echo intval($result["c"]);
				?>
                </td>
			</tr>
		</tbody>
	</table>
	<table class="table table-border table-bordered table-bg mt-20">
		<thead>
			<tr>
				<th colspan="2" scope="col">服务器信息</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th width="30%">服务器计算机名</th>
				<td><span id="lbServerName"><?php echo $_SERVER['SERVER_NAME'];?></span></td>
			</tr>
			<tr>
				<td>服务器站点目录</td>
				<td><?php echo $_SERVER['DOCUMENT_ROOT'];?></td>
			</tr>
			<tr>
				<td>服务器站点端口</td>
				<td><?php echo $_SERVER['SERVER_PORT'];?></td>
			</tr>
			
			<tr>
				<td>服务器版本 </td>
				<td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
			</tr>
			<tr>
				<td>允许上传最大文件 </td>
				<td><?php echo ini_get("post_max_size");?></td>
			</tr>
			<tr>
				<td>服务器操作系统 </td>
				<td><?php echo PHP_OS; ?></td>
			</tr>
			<tr>
				<td>最大执行时间 </td>
				<td><?php echo get_cfg_var("max_execution_time")."秒 "; ?></td>
			</tr>
			<tr>
				<td>脚本运行占用最大内存 </td>
				<td><?php echo get_cfg_var ("memory_limit")?get_cfg_var("memory_limit"):"无" ?></td>
			</tr>
		</tbody>
	</table>
</div>
<footer class="footer mt-20">
	<div class="container">
		<p>感谢Recson、Wolfe等技术倾力设计<br>
			Copyright &copy;2012 Zjtd100.com v1.0 All Rights Reserved.<br>
			本后台系统由<a href="http://www.zjtd100.com/" target="_blank" title="">中京通达技术团队</a>提供前端技术支持</p>
	</div>
</footer>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui/js/H-ui.js"></script> 

</body>
</html>