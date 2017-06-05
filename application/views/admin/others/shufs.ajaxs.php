<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:del_all();" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> &nbsp;&nbsp;&nbsp; <a href="<?php echo http_url();?>admin/others/shufs_adds/<?php echo $acts;?>" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加一条</a></span> <span class="r">共有数据：<strong><?php echo $query->num_rows();?></strong> 条</span> </div>
	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
				<th width="25"><input type="checkbox" name="sx" id="sx" value="100" onClick="choose_all();" ></th>
				<th width="32">ID</th>
				<th width="98">轮播图描述</th>
				<th width="95">图片</th>
				
				<th width="94">更新时间</th>
                <th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
        	<?php
            	foreach($query->result_array() as $array)
				{
			?>
			<tr id="zo_<?php echo $array["id"];?>" class="text-c">
				<td><input type="checkbox" name="cid" id="cid" value="<?php echo $array["id"];?>"></td>
				<td><?php echo $array["id"];?></td>
				<td><?php echo $array["alt"];?></td>
				<td><a href="javascript:shows_boxs('<?php echo $array["id"];?>')"><img src="/<?php echo $array["file"];?>" style="width:150px;height:80px;"></a>
                <span style="display:none;" id="by_<?php echo $array["id"];?>">
                	<p align="center" style="padding:10px 10px 10px 10px;">
                    	<img src="/<?php echo $array["file"];?>" style="max-width:500px;max-height:350px;">
                    </p>
                </span>
 				               
                </td>
				
				<td><?php echo date("Y-m-d H:i:s",$array["times"]);?></td>
                <td class="td-manage">
                <a title="编辑" href="<?php echo http_url();?>admin/others/shufs_edits/<?php echo $array["id"];?>"  class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                
                <a title="删除" href="javascript:del_it('<?php echo $array["id"];?>');" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                
                
                </td>
			</tr>
            <?php
            	}
			?>
		</tbody>
	</table>
    
    
    
	</div>
    
 