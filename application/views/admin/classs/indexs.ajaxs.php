<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="<?php echo http_url();?>admin/classs/adds" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加一种课程</a> </span> <span class="r"><strong style="color:#F00;">警告：如果当前课类下安排有课程或者绑定过教练教授信息中，则无法删除</strong></span><span class="r">共有数据：<strong><?php echo $query->num_rows();?></strong> 条&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> </div>
	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="32">ID</th>
				<th width="98">课程名称</th>
				<th width="100">上月参与人数</th>
				<th width="100">总参与人数</th>
				
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
        	<?php
            	foreach($query->result_array() as $array)
				{
			?>
			<tr id="zo_<?php echo $array["id"];?>" class="text-c">
				<td><?php echo $array["id"];?></td>
				<td><?php echo $array["name"];?></td>
				<td class="td-manage"><?php echo $array["join_m"];?></td>
				<td class="td-manage"><a href="<?php echo http_url();?>admin/classs/index_tj/<?php echo $array["id"];?>"><strong><?php echo $array["join"];?></strong></a></td>
				
				<td class="td-manage"><a title="编辑" href="<?php echo http_url();?>admin/classs/edits/<?php echo $array["id"];?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>  <a title="删除" href="javascript:del_it('<?php echo $array["id"];?>');" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i> </a>                </td>
			</tr>
            <?php
            	}
			?>
		</tbody>
	</table>
    
    
    
	</div>
    

