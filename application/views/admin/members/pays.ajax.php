<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="<?php echo http_url();?>admin/tongjis/pays" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e1;</i> 曲线统计</a> </span> <span class="r">&nbsp;&nbsp;&nbsp; 共有数据：<strong><?php echo $pageall;?></strong> 条</span> &nbsp;&nbsp;&nbsp; <span class="r">&nbsp;&nbsp;&nbsp;合计充值金额：<strong style="color:#F00;"><?php echo $result_100["money_1"];?></strong> 元</span> &nbsp;&nbsp;&nbsp;<span class="r">合计充值剩余金额：<strong style="color:#1859a5;"><?php echo $result_100["money_2"];?></strong> 元</span> &nbsp;&nbsp;&nbsp;  </div>
	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="147">三方交易号</th>
				<th width="70">方式</th>
				<th width="120">会员手机号</th>
				<th width="78">会员昵称</th>
				
				<th width="74">充值金额</th>
				<th width="73">剩余金额</th>
				<th width="120">充值时间</th>
            </tr>
		</thead>
		<tbody>
        	<?php
            	foreach($query->result_array() as $array)
				{
			?>
			<tr id="zo_<?php echo $array["id"];?>" class="text-c" >
				<td><?php echo $array["trade_index"];?></td>
				<td>
                <?php
                	if($array["pay_act"]==1)
					{
				?>
                <strong style="color:#ff6600;">支付宝</strong>
                <?php
					}
					elseif($array["pay_act"]==2)
					{
				?>
                <strong style="color:#1859a5;">微信</strong>
                <?php
					}
					elseif($array["pay_act"]==3)
					{
				?>
                <strong style="color:green;">系统</strong>
                <?php
					}
				?>
                </td>
				<td><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><?php echo $array["mobile"];?></a></td>
				<td><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><?php echo $array["nickname"];?></a>
                
 				<div style="display:none; padding:10px 10px 10px 10px; padding-left:35px;text-align:left; font-weight:normal;" id="abc_<?php echo $array["id"];?>">
                	<p>头像：&nbsp;&nbsp;<img src="/<?php echo $array["avatar"]==""?$avatar:$array["avatar"];?>" style="width:80px;height:80px;border-radius:50%;"></p>
                    <p>昵称：&nbsp;&nbsp; <strong><?php echo $array["nickname"];?></strong></p>
                    <p>手机号：&nbsp;&nbsp; <strong><?php echo $array["mobile"];?></strong></p>
                    <p style="line-height:26px;">充值金额：&nbsp;&nbsp; <strong><?php echo $array["money"];?></strong></p>
                    <p style="line-height:26px;">剩余金额：&nbsp;&nbsp; <strong style="color:#F00;"><?php echo $array["money_remaining"];?></strong></p>
                    <p>时间：&nbsp;&nbsp; <strong><?php echo date("Y-m-d H:i:s",$array["time"]);?></strong></p>
                </div>               
                </td>
				
				<td><strong><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><?php echo $array["money"];?></a></strong></td>
				<td><strong style="color:#F00;"><?php echo $array["money_remaining"];?></strong></td>
				<td><?php echo date("Y-m-d H:i:s",$array["time"]);?></td>
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

