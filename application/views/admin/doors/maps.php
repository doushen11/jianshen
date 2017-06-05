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
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/My97DatePicker/WdatePicker.js"></script> 
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>柱状图统计</title>
</head>
<body>
<script>
	function soso()
	{
		var d=$("#datemin").val();
		location="<?php echo http_url();?>admin/doors/maps?days=" + d;	
	}
</script>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 统计管理 <span class="c-gray en">&gt;</span> 房间人数统计 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="f-14 c-error"><p align="center" style="padding-bottom:30px;"><input type="text" onfocus="WdatePicker()" id="datemin" class="input-text Wdate" style="width:120px;" value="<?php echo $date;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value=" 点 击 查 看 " style="width:100px; height:30px; background:#F00;color:#fff; border:none;cursor:pointer; border-radius:10px;" onClick="soso();"></p></div>
	<div id="container" style="min-width:700px;height:400px"></div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/Highcharts/4.1.7/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/Highcharts/4.1.7/js/modules/exporting.js"></script>
<?php
	$str="";
	$str_1="";
	$str_2="";
	$str_3="";
	for($i=0;$i<=23;$i++)
	{
		//if($i<=9)
		//{
		$i1=$i+1;
		$str.=",'".$i.":00-".$i1.":00'";	
		//}		
		$s=strtotime($date." ".$i.":00:00");
		$e=strtotime($date." ".$i.":59:59");
		$query_1=$this->db->query("select `id` from `dg_doors` where `act`='1' and ( (`start_time`<'$s' and `end_time`>='$s' and `end_time`!='') or (`start_time`>='$s' and `start_time`<'$e') ) group by `uid`");
		
		//echo "select count(`id`) as `c_1` from `dg_doors` where `act`='1' and (`start_time`>='$s' and `start_time`<'$e')".$date." ".$i.":00:00".$date." ".$i1.":59:59"."_______<hr></hr>";
		
		$cs=0;
		
		$result_1=$query_1->num_rows();
		$str_1.=",".intval($result_1);
		
		$cs=$cs+intval($result_1);
	
		$query_1=$this->db->query("select `id` from `dg_doors` where `act`='2' and ( (`start_time`<'$s' and `end_time`>='$s' and `end_time`!='') or (`start_time`>='$s' and `start_time`<'$e') ) group by `uid`");
		$result_1=$query_1->num_rows();
		$str_2.=",".intval($result_1);
		
		
		$query_1=$this->db->query("select `id` from `dg_doors` where `act`='3' and ( (`start_time`<'$s' and `end_time`>='$s' and `end_time`!='') or (`start_time`>='$s' and `start_time`<'$e') ) group by `uid`");
		$result_1=$query_1->num_rows();
		$str_3.=",".intval($result_1);
		//$cs=$cs+intval($result_1);

		//$str_3.=",".$cs;
		
	}
	$str=trim($str,',');
	$str_1=trim($str_1,',');
	$str_2=trim($str_2,',');
	$str_3=trim($str_3,',');
	
?>
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '对应日期房间人数记录信息'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
               <?php echo $str;?>
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: '合计(人)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} 人</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: '学员',
            data: [<?php echo $str_1;?>]

        }, {
            name: '教练',
            data: [<?php echo $str_2;?>]

        }, {
            name: '工作人员',
            data: [<?php echo $str_3;?>]

        }]
    });
});				
</script>
</body>
</html>