<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:del_all();" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 删除所选交易记录</a> </span> <span class="r">共有数据：<strong><?php echo $pageall;?></strong> 条</span> <span class="r">合计金额：<strong style="color:#F00;"><?php echo sprintf("%.2f",$result1["price"]);?></strong> 元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </div>
	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
			  <th width="24"><input type="checkbox" name="sx" id="sx" value="100" onClick="choose_all();" ></th>
			  <th width="52">ID</th>
				<th width="68">当前状态</th>
				<th width="86">会员手机号</th>
				<th width="92">会员昵称</th>
				
				<th width="136">上课时间</th>
				<th width="110">购买时间</th>
				<th width="61">购买价格</th>
				<th width="57">教练</th>
				<th width="87">教练手机号</th>
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
			  <td><input type="checkbox" name="cid" id="cid" value="<?php echo $array["id"];?>"></td>
			  <td><?php echo $array["id"];?></td>
				<td>

				<?php
                	if($array["state"]==1)
					{
				?>
                <strong style="color:#09F;">待成立</strong>
				<?php
					}
					elseif($array["state"]==2)
					{
				?>
                <strong style="color:#F0F;">已成立</strong>
				<?php
					}
					elseif($array["state"]==3)
					{
				?>
                <strong style="color:green;">已完成</strong>
				<?php
					}
					elseif($array["state"]==4)
					{
				?>
                <strong style="color:#999999;">用户取消</strong>
                <?php
					}
					else
					{
				?>
                <strong style="color:#999999;">系统取消</strong>
                <?php
					}
				?>

                </td>
				<td><a href="javascript:shows_boxs('<?php echo $array["uid"];?>')"><?php echo $array["mobile1"];?></a></td>
				<td><a href="javascript:shows_boxs('<?php echo $array["uid"];?>')"><?php echo $array["nickname"];?></a>
                
 				<div style="display:none; padding:10px 10px 10px 10px; padding-left:35px;text-align:left; font-weight:normal;" id="abc_<?php echo $array["uid"];?>">
                	<p>头像：&nbsp;&nbsp;<img src="/<?php echo $array["avatar1"]==""?$avatar:$array["avatar1"];?>" style="width:80px;height:80px;border-radius:50%;"></p>
                    <p>昵称：&nbsp;&nbsp; <strong><?php echo $array["nickname"];?></strong></p>
                    <p>手机号：&nbsp;&nbsp; <strong><?php echo $array["mobile1"];?></strong></p>
                    
                </div>               
                </td>
				
				<td><strong style="color:#1859a5;"><?php echo $array["date"]." ".$array["node"];?></strong></td>
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
				<td>

                <strong style="color:#cc0000;"><?php echo $array["money"];?></strong>

                </td>
				<td><a href="javascript:shows_boxss('<?php echo $array["tid"];?>')"><?php echo ($array["realname"]);?></a>
                
 				<div style="display:none; padding:10px 10px 10px 10px; padding-left:35px;text-align:left; font-weight:normal;" id="abcd_<?php echo $array["tid"];?>">
                	<p>头像：&nbsp;&nbsp;<img src="/<?php echo $array["avatar2"]==""?$avatar:$array["avatar2"];?>" style="width:80px;height:80px;border-radius:50%;"></p>
                    <p>姓名：&nbsp;&nbsp; <strong><?php echo $array["realname"];?></strong></p>
                    <p>手机号：&nbsp;&nbsp; <strong><?php echo $array["mobile2"];?></strong></p>
                    
                </div>                 
                </td>
				<td class="f-14 td-manage"><a href="javascript:shows_boxss('<?php echo $array["tid"];?>')"><?php echo ($array["mobile2"]);?></a></td>
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

