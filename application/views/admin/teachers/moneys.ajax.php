<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l">合计实际收入金额：<strong style="color:#F00;"><?php echo sprintf("%.2f",$result1["price"]);?></strong> 元&nbsp;&nbsp;&nbsp;&nbsp;合计收入金额：<strong style="color:#F00;"><?php echo sprintf("%.2f",$result1["price1"]);?></strong> 元</span> <span class="r">共有数据：<strong><?php echo $pageall;?></strong> 条</span> </div>
	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
			  <th width="47">收入ID</th>
				<th width="77">教练手机号</th>
				<th width="64">真实姓名</th>
				
				<th width="47">实际收入金额</th>
				<th width="116">收入金额</th>
				<th width="116">课程类型</th>
				<th width="110">收入时间</th>
			</tr>
		</thead>
		<tbody>
        	<?php
				$s_time=strtotime(date("Y-m-d")." 00:00:00");
				$e_time=strtotime(date("Y-m-d")." 23:59:59");
            	foreach($query->result_array() as $array)
				{
			?>
			<tr id="zo_<?php echo $array["id"];?>" class="text-c" >
			  <td><?php echo $array["id"];?></td>
				<td><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><?php echo $array["mobile"];?></a></td>
				<td><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><?php echo $array["realname"];?></a>
                
 				<div style="display:none; padding:10px 10px 10px 10px; padding-left:35px;text-align:left; font-weight:normal;" id="abc_<?php echo $array["id"];?>">
                	<p>头像：&nbsp;&nbsp;<img src="/<?php echo $array["avatar"]==""?$avatar:$array["avatar"];?>" style="width:80px;height:80px;border-radius:50%;"></p>
                    <p>姓名：&nbsp;&nbsp; <strong><?php echo $array["realname"];?></strong></p>
                    <p>手机号：&nbsp;&nbsp; <strong><?php echo $array["mobile"];?></strong></p>
                    <p style="line-height:26px;">性别：&nbsp;&nbsp; <strong><?php echo ($array["gender"]);?></strong></p>
                    <p style="line-height:26px;">等级：&nbsp;&nbsp; <strong><?php echo ($array["level"]);?></strong></p>
                    <p style="line-height:26px;">评分：&nbsp;&nbsp; <strong><?php echo ($array["score"]);?></strong></p>
                    
                </div>               
                </td>
				
				<td><strong><?php echo $array["money"];?></strong></td>
				<td><font color="#999999"><?php echo $array["money_in"];?></font></td>
				<td>
                <a href="javascript:show_rs('<?php echo $array["id"];?>');">
				<?php 
				if($array["act"]==1)
				{
				?>
                <strong style="color:#1859a5;">私课</strong>
                <?php		
				}else{
				?>
				<strong style="color:#096;">操课</strong>
                <?php
				}
				?>
                </a>
                <?php
					$arr=json_decode($array["text"],true);
                	if($array["act"]==1)
					{
						$querys=$this->db->query("select `date`,`node` from `dg_tearch_plan_list` where `id`='".$arr["class_node_id"]."'");
						if($querys->num_rows()>0)
						{
							$results=$querys->row_array();
						}
						else
						{
							$results=array("date"=>"","node"=>"");	
						}
				?>
 				<div style="display:none; padding:10px 10px 10px 10px; padding-left:35px;text-align:left; font-weight:normal;" id="abcd_<?php echo $array["id"];?>">
                	<p>日期：&nbsp;&nbsp;<strong><?php echo $results["date"];?></strong></p>
                    <p>时间：&nbsp;&nbsp; <strong><?php echo $results["node"];?></strong></p>
                </div>                 
                <?php
					}
					else
					{
						$querys=$this->db->query("select `date`,`node`,`room_name`,`class_name`,`loads` from `dg_tearch_plans` where `id`='".$arr["class_node_id"]."'");
						if($querys->num_rows()>0)
						{
							$results=$querys->row_array();
						}
						else
						{
							$results=array("date"=>"","node"=>"","room_name"=>"","class_name"=>"","loads"=>"");	
						}
				?>
 				<div style="display:none; padding:10px 10px 10px 10px; padding-left:35px;text-align:left; font-weight:normal;" id="abcd_<?php echo $array["id"];?>">
                	<p>日期：&nbsp;&nbsp;<strong><?php echo $results["date"];?></strong></p>
                    <p>时间：&nbsp;&nbsp; <strong><?php echo $results["node"];?></strong></p>
                    <p>课厅：&nbsp;&nbsp; <strong><?php echo $results["room_name"];?></strong></p>
                    <p>课程：&nbsp;&nbsp; <strong><?php echo $results["class_name"];?></strong></p>
                    <p>人数：&nbsp;&nbsp; <strong><?php echo $results["loads"];?></strong></p>
                </div>
                <?php
					}
				?>
                </td>
				<td>
                <?php
                	if($array["time"]>=$s_time && $array["time"]<=$e_time)
					{
				?>
                <strong style="color:#F00;"><?php echo date("Y-m-d H:i:s",$array["time"]);?></strong>
                <?php
					}
					else
					{
				?>
                <?php echo date("Y-m-d H:i:s",$array["time"]);?>
                <?php
					}
				?>
                </td>
			</tr>
            <?php
            	}
			?>
		</tbody>
	</table>
    
    
    
	</div>
    
    <?php
    	 if($pagecount>1){
	?>
    <div style="width:100%;padding-top:15px;">
    	<div style="width:40%; float:left; height:30px; line-height:30px;">显示 <?php echo $pageindex;?> / <?php echo $pagecount;?> 页 &nbsp;&nbsp;&nbsp;  <input type="text" id="pagenum" name="pagenum" value="<?php echo $pageindex;?>" style="text-align:center; width:40px; height:18px; line-height:18px; border:#999999 1px solid;" > &nbsp; 页 &nbsp; <input type="button" value="跳转" style="text-align:center; width:40px; height:20px; line-height:20px; border:#999999 1px solid;" onClick="page_gos();"> &nbsp;&nbsp;&nbsp; 共 <strong><?php echo $pageall;?></strong> 条数据</div>
        
        <div style="width:60%; float:right; text-align:right;" class="pages">
		<?php
           if($pageindex>1){
        ?>
        <a href="javascript:show_pages(1)">首页</a> <a href="javascript:show_pages('<?php echo $pageindex-1;?>')">上一页</a> 
        <?php
            }
        ?>
        <?php
            for($i=$arrs[0];$i<=$arrs[1];$i++){
        ?>
        <?php
            if($i!=$pageindex){
        ?>
        <a href="javascript:show_pages('<?php echo $i;?>')"><?php echo $i;?></a>
        <?php
            }else{
        ?>
        <span><?php echo $i;?></span>
        <?php
            }
        ?>
        <?php
            }
        ?>      
        <?php
            if($pageindex<$pagecount){
        ?>                                         
        <a href="javascript:show_pages('<?php echo $pageindex+1;?>')">下一页</a> <a href="javascript:show_pages('<?php echo $pagecount;?>')">末页</a>
        <?php
            }
        ?>
        </div>
    </div>
    <?php
    	}
	?>

