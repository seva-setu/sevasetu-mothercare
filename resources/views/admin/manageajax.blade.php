<tr>
	<th>Product Name</th>
	<th>Category Name</th>
	<th>Status</th>
	<th>Action</th>
</tr>
<?php foreach ($result as $value){?>
	<tr>
		<td><?php echo $value->v_product_name;?></td>
		<td><?php echo $value->v_category_name;?></td>
		<td><?php echo $value->v_status;?></td>
		<td><a class="btn btn-info" href="<?php echo '{{ url() }}/product/prod_edit/'.$value->b_id ?>">Edit</a>
		<a class="btn btn-info" href="<?php echo '{{ url() }}/product/prod_delete/'.$value->b_id ?>">Delete</a>
		</td>
	</tr>
<?php }?>
<tr><td colspan="4"><?php echo $result->render(); ?></td></tr>