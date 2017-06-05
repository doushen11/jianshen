<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l">合计金额：<strong style="color:#F00;"><?php echo sprintf("%.2f",$result1["price"]);?></strong> 元</span> <span class="r">共有数据：<strong><?php echo $pageall;?></strong> 条</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </div>
	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
			  <th width="38">ID</th>
				<th width="82">教练手机号</th>
				<th width="69">教练真实姓名</th>
				
				<th width="84">打款金额</th>
				<th width="116">课程类型</th>
				<th width="203">备注信息</th>
				<th width="110">提现时间</th>
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
				<td><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><?php echo $array["mobile1"];?></a></td>
				<td><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><?php echo $array["realname"];?></a>
                
 				<div style="display:none; padding:10px 10px 10px 10px; padding-left:35px;text-align:left; font-weight:normal;" id="abc_<?php echo $array["id"];?>">
                	<p>头像：&nbsp;&nbsp;<img src="/<?php echo $array["avatar"]==""?$avatar:$array["avatar"];?>" style="width:80px;height:80px;border-radius:50%;"></p>
                    <p>昵称：&nbsp;&nbsp; <strong><?php echo $array["realname"];?></strong></p>
                    <p>手机号：&nbsp;&nbsp; <strong><?php echo $array["mobile1"];?></strong></p>
                    
                </div>               
                </td>
				
				<td><strong style="color:#1859a5;"><?php echo $array["money"];?></strong></td>
				<td>
				  <?php 
				if($array["act"]==1)
				{
				?>
				  <strong style="color:#1859a5;">私课教练</strong>
				  <?php		
				}else{
				?>
				  <strong style="color:#096;">操课教练</strong>
				  <?php
				}
				?>
				 </td>
				<td><?php $arr=json_decode($array["text"],true);echo $arr["desc"];?></td>
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

