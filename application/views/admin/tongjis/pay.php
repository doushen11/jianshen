
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
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>柱状图统计</title>
</head>
<body>
<script>
	function go_it()
	{
		var nian=$("#nian").val();
		location="<?php echo http_url();?>admin/tongjis/pays?y=" + nian;	
	}
</script>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 充值管理 <span class="c-gray en">&gt;</span> 充值记录统计 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="f-14 c-error" style="text-align:center; padding-top:10px; padding-bottom:10px;">
    <select style="width:200px; height:26px; line-height:26px; border:#e4e4e4 1px solid;" id="nian" name="nian" onChange="go_it();">
    	<?php
        	for($i=2016;$i<=date("Y");$i++){
		?>
        <option value="<?php echo $i;?>" <?php if($i==$year){?> selected<?php }?>>查看<?php echo $i;?>年报表</option>
        <?php
			}
		?>
    </select>
    
    &nbsp;&nbsp;&nbsp;
    </div>
	<div id="container" style="min-width:700px;height:400px"></div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/Highcharts/4.1.7/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/Highcharts/4.1.7/js/modules/exporting.js"></script>
<?php
	$str_1="";
	$str_2="";
	$str_3="";
	$str_4="";
	for($i=0;$i<count($array);$i++)
	{
		$str_1.=",".$array[$i]["money1"];
		$str_2.=",".$array[$i]["money2"];
		$str_3.=",".$array[$i]["money3"];
		$str_4.=",".$array[$i]["money4"];	
	}
?>
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '<strong><?php echo $year;?></strong>年每月充值记录'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
                '一月',
                '二月',
                '三月',
                '四月',
                '五月',
                '六月',
                '七月',
                '八月',
                '九月',
                '十月',
                '十一月',
                '十二月'
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: '合计(元)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} 元</b></td></tr>',
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
            name: '支付宝',
            data: [<?php echo trim($str_1,",")?>]

        }, {
            name: '微信',
            data: [<?php echo trim($str_2,",")?>]

        }, {
            name: '系统',
            data: [<?php echo trim($str_3,",")?>]

        }, {
            name: '共计',
            data: [<?php echo trim($str_4,",")?>]

        }]
    });
});				
</script>
</body>
</html>