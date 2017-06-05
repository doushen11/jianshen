<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:del_all();" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 删除所选私课</a> </span> <span class="r">共有数据：<strong><?php echo $pageall;?></strong> 条</span> 

<?php if($time!="" && $time<time()+3600*24*7){?>
<span class="r">最后一次课程结束时间为：<strong style="color:#F00;"><?php echo date("Y-m-d H:i:s",$time);?>&nbsp;&nbsp;&nbsp;&nbsp;<?php if($time<time()+3600*24*7){ echo "请尽快发布课程吧！";}?>&nbsp;&nbsp;&nbsp;&nbsp;</strong></span>
<?php }elseif($time!=""){?>
<span class="r">最后一次课程结束时间为：<strong style="color:green;"><?php echo date("Y-m-d H:i:s",$time);?>&nbsp;&nbsp;&nbsp;&nbsp;</strong></span>

<?php }?>
</div>
	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
			  <th width="25"><input type="checkbox" name="sx" id="sx" value="100" onClick="choose_all();" ></th>
			  <th width="60">课程ID</th>
				<th width="73">课程状态</th>
				<th width="95">教练手机号</th>
				<th width="79">真实姓名</th>
				
				<th width="57">费用</th>
				<th width="112">对应日期</th>
				<th width="117">开始---结束</th>
				<th width="103">当日可预约时间</th>
				<th width="99">操作</th>
            </tr>
		</thead>
		<tbody>
        	<?php
            	foreach($query->result_array() as $array)
				{
			?>
			<tr id="zo_<?php echo $array["id"];?>" class="text-c" >
			  <td><input type="checkbox" name="cid" id="cid" value="<?php echo $array["id"];?>"></td>
			  <td><?php echo $array["id"];?></td>
				<td>
                <a href="<?php echo http_url();?>admin/members/sikes?keywords=<?php echo $array["id"];?>" id="state_<?php echo $array["id"];?>">
				<?php
                	if($array["state"]==2)
					{
				?>
                <strong style="color:green;">正常预约中</strong>
                <?php
					}
					elseif($array["state"]==3)
					{
				?>
                <strong style="color:#cc0000;">已被预约了</strong>
                <?php
					}
					elseif($array["state"]==4)
					{
				?>
                <strong style="color:#1859a5;">系统超时取消</strong>
                <?php
					}
					elseif($array["state"]==6)
					{
				?>
                <strong style="color:#333;">已经完成</strong>
                <?php
					}
				?>
                </a>
                </td>
				<td><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><?php echo $array["mobile"];?></a></td>
				<td><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><?php echo $array["realname"];?></a>
                
 				<div style="display:none; padding:10px 10px 10px 10px; padding-left:35px;text-align:left; font-weight:normal;" id="abc_<?php echo $array["id"];?>">
                	<p>头像：&nbsp;&nbsp;<img src="/<?php echo $array["avatar"]==""?$avatar:$array["avatar"];?>" style="width:80px;height:80px;border-radius:50%;"></p>
                    <p>姓名：&nbsp;&nbsp; <strong><?php echo $array["realname"];?></strong></p>
                    <p>手机号：&nbsp;&nbsp; <strong><?php echo $array["mobile"];?></strong></p>
                    <p style="line-height:26px;">性别：&nbsp;&nbsp; <strong><?php echo ($array["gender"]);?></strong></p>
                    <p style="line-height:26px;">等级：&nbsp;&nbsp; <strong><?php echo ($array["level"]);?></strong></p>
                    <p style="line-height:26px;">评分：&nbsp;&nbsp; <strong><?php echo ($array["score"]);?></strong></p>
                    <p style="line-height:26px;">简介：&nbsp;&nbsp; <strong><?php echo ($array["desc"]);?></strong></p>
                   
                                        
                    <p style="line-height:26px;">账户余额：&nbsp;&nbsp; <strong><?php echo $array["balance"];?></strong></p>
                    <p style="line-height:26px;">注册时间：&nbsp;&nbsp; <strong><?php echo date("Y-m-d H:i:s",$array["reg_time"]);?></strong></p>
 
                    <p style="line-height:26px;">登录时间：&nbsp;&nbsp; <strong><?php echo date("Y-m-d H:i:s",$array["login_time"]);?></strong></p>
					<p style="line-height:26px;">登录浏览器信息：&nbsp;&nbsp; <strong><?php echo trim($array["user_agent"]);?></strong></p>
                    
                     <p style="line-height:26px;">图文：&nbsp;&nbsp; <strong><?php echo ($array["contents"]);?></strong></p>
                </div>               
                </td>
				
				<td><strong><?php echo $array["money"];?></strong></td>
				<td><?php echo $array["date"];?></td>
				<td>
                
                <?php echo $array["node"];?>

                </td>
				<td><?php echo $array["frees"];?></td>
				<td class="f-14 td-manage"><a style="text-decoration:none" class="ml-5" onClick="members_edit('<?php echo $array["id"];?>')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> </td>
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

