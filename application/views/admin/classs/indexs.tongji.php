<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
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
<title>折线图</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 课程管理 <span class="c-gray en">&gt;</span> 课程参与详情统计 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<?php
	$years=date("Y");
	if(isset($_GET["years"]) && trim($_GET["years"])!="")
	{
		$years=trim($_GET["years"]);	
	}
	$months=date("m");
	if(isset($_GET["months"]) && trim($_GET["months"])!="")
	{
		$months=trim($_GET["months"]);	
	}
	$d = strtotime($years.'-'.$months.'-01');
	$maxs=date('t',$d);
	$str_a="";
	$str_b="";
	for($i=1;$i<=$maxs;$i++)
	{
		$s=strtotime($years.'-'.$months.'-'.$i." 00:00:00");
		$e=strtotime($years.'-'.$months.'-'.$i." 23:59:59");
		$str_a.="'".$i."',";
		//开始计算当日课程参与人数
		$qys=$this->db->query("select count(`p`.`id`) as `cs` from `dg_orders` as `o` left join `dg_tearch_plans` as `p` on `o`.`pid`=`p`.`id` where `p`.`class_id`='".$result["id"]."' and `p`.`start_time`>'$s' and `p`.`start_time`<'$e' and `o`.`state`='3'");	
		$rss=$qys->row_array();
		$str_b.=$rss["cs"].",";
	}
	$str_a=trim($str_a,",");
	$str_b=trim($str_b,",");
?>
<script>
	function soso()
	{
		var ke=$("#ke").val();
		//alert(ke);
		var nian=$("#nian").val();
		var yue=$("#yue").val();
		location="<?php echo http_url();?>admin/classs/index_tj/" + ke + "?years=" + nian + "&months=" + yue;
	}
</script>
<div class="page-container">
	<div class="f-14 c-error" align="center" style="padding-bottom:20px;">
    <select id="ke" name="ke">
        <?php
        	foreach($querys->result_array() as $arrays)
			{
		?>
        <option value="<?php echo $arrays["id"];?>" <?php if($arrays["id"]==$result["id"]){?> selected<?php }?>><?php echo $arrays["name"];?></option>
        <?php
			}
		?>
    </select>
    &nbsp;&nbsp;&nbsp;
    <select id="nian" name="nian">
    	<?php
        	for($i=2016;$i<=2016;$i++)
			{
		?>
    	<option value="<?php echo $i;?>" <?php if($years==$i){?> selected<?php }?>><?php echo $i;?>年</option>
        <?php
			}
		?>
    </select>
    &nbsp;&nbsp;&nbsp;
    <select id="yue" name="yue">
    	<?php
        	for($i=1;$i<=12;$i++){
		?>
    	<option value="<?php echo $i;?>" <?php if($months==$i){?> selected<?php }?>><?php echo $i;?>月</option>
        <?php
			}
		?>
    </select>
    &nbsp;&nbsp;&nbsp;
    <input type="submit" value=" 查 询 " onClick="soso();" >
    </div>
	<div id="container" style="min-width:700px;height:400px"></div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/Highcharts/4.1.7/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/Highcharts/4.1.7/js/modules/exporting.js"></script>
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        title: {
            text: '"<?php echo $result["name"]?>"在<?php echo intval($months);?>月份的参与统计图',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [<?php echo $str_a;?>]
        },
        yAxis: {
			min: 0,
            title: {
                text: '统计 (人数)'
            },

            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
			
			,
			
			
			
        },
        tooltip: {
            valueSuffix: '人'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: '<?php echo $result["name"];?>',
            data: [<?php echo $str_b;?>]
        }]
    });
});
</script>
</body>
</html>