	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="5">器材分类列表</th>
			</tr>
			<tr class="text-c">
				<th width="40">ID</th>
				<th width="150">标题</th>
                <th width="100">对应器材数量</th>
				<th width="142">常用操作</th>
				
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
        	<?php
            	foreach($query->result_array() as $array)
				{
			?>
			<tr class="text-c">
				<td><?php echo $array["id"];?></td>
				<td><?php echo $array["name"];?></td>
                <td class="td-manage">
                <a href="<?php echo http_url();?>admin/machines/indexs/<?php echo $array["id"];?>">
                <strong>
				<?php
                	$qy=$this->db->query("select `id` from `dg_machine` where `type`='".$array["id"]."'");
					echo $qy->num_rows();
				?>
                </strong>
                </a>
                </td>
				<td><a href="javascript:ups('<?php echo $array["id"];?>');">上移</a>&nbsp;&nbsp;&nbsp;<a href="javascript:downs('<?php echo $array["id"];?>');">下移</a></td>
				
				<td class="td-manage"><a title="编辑" href="<?php echo http_url();?>admin/machines/act_edits/<?php echo $array["id"];?>"  class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a></td>
			</tr>
            <?php
            	}
			?>
		</tbody>
	</table>
