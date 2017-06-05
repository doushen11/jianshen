<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:del_all();" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a href="<?php echo http_url();?>admin/machines/adds?id=<?php echo $id;?>&keywords=<?php echo $keywords;?>&pageindex=<?php echo $pageindex;?>" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加器材</a></span> <span class="r">共有数据：<strong><?php echo $pageall;?></strong> 条</span> </div>
	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="25"><input type="checkbox" name="sx" id="sx" value="100" onClick="choose_all();" ></th>
				<th width="80">ID</th>
				<th width="100">器材名称</th>
				<th width="120">描述短语</th>
				
				<th width="116">简介显示方式</th>
				<th width="94">更新时间</th>
                <th width="80">封面图</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
        	<?php
            	foreach($query->result_array() as $array)
				{
			?>
			<tr class="text-c">
				<td><input type="checkbox" name="cid" id="cid" value="<?php echo $array["id"];?>"></td>
				<td><?php echo $array["id"];?></td>
				<td><?php echo $array["name"];?></td>
				<td><?php echo $array["alt"];?></td>
				
				<td><?php echo $array["act"]==1?"视频":"图文";?></td>
				<td><?php echo date("Y-m-d H:i:s",$array["times"]);?></td>
                <td><a href="javascript:ups('<?php echo $array["id"];?>','<?php echo $array["type"];?>');">上移</a> &nbsp; <a href="javascript:downs('<?php echo $array["id"];?>','<?php echo $array["type"];?>');">下移</a></td>
				<td class="td-manage"> <a title="编辑" href="javascript:edits('<?php echo $array["id"];?>');"  class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>  <a title="删除" href="javascript:del_it('<?php echo $array["id"];?>');" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
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

