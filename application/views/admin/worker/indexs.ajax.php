<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:del_all();" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 删除所选工作人员</a>  <a href="javascript:;" onclick="member_add()" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加工作人员</a></span> <span class="r">共有数据：<strong><?php echo $pageall;?></strong> 条</span> </div>
	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
			  <th width="23"><input type="checkbox" name="sx" id="sx" value="100" onClick="choose_all();" ></th>
			  <th width="47">ID</th>
				<th width="58">登录状态</th>
				<th width="90">进门出门</th>
				<th width="90">工作人员手机号</th>
				<th width="60">备注</th>
				
				<th width="109">注册时间</th>
				<th width="116">最后登录时间</th>
				<th width="76">最后登录IP</th>
				<th width="52">sos操作</th>
				<th width="81">操作</th>
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
                <a href="javascript:change_state('<?php echo $array["id"];?>');" id="state_<?php echo $array["id"];?>">
				<?php
                	if($array["state"]==1)
					{
				?>
                <strong style="color:green;">允许</strong>
                <?php
					}
					else
					{
				?>
                <strong style="color:#cc0000;">禁止</strong>
                <?php
					}
				?>
                </a>
                </td>
				<td>
 				<?php
                	if($array["doors"]==1)
					{
				?>
                <strong style="color:#039;">室内</strong>
                <?php
					}
					else
					{
				?>
                <strong style="color:#999999;">室外</strong>
                <?php
					}
				?>               
                </td>
				<td><?php echo $array["mobile"];?></td>
				<td><?php echo $array["desc"];?></td>
				
				<td><?php echo date("Y-m-d H:i:s",$array["reg_time"]);?></td>
				<td>
                <?php
                	if($array["login_time"]>=$s_time && $array["login_time"]<=$e_time)
					{
				?>
                <strong style="color:#F00;"><?php echo date("Y-m-d H:i:s",$array["login_time"]);?></strong>
                <?php
					}
					else
					{
				?>
                <?php echo date("Y-m-d H:i:s",$array["login_time"]);?>
                <?php
					}
				?>
                </td>
				<td><?php echo trim($array["login_ip"]);?></td>
				<td class="f-14 td-manage"><a href="<?php echo http_url();?>admin/worker/soss?keywords=<?php echo $array["mobile"];?>"><strong>查看</strong></a></td>
				<td class="f-14 td-manage"><a style="text-decoration:none" class="ml-5" onClick="members_edit('<?php echo $array["id"];?>')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>  </td>
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

