@extends('app') @section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">
				<?php echo $title;?>
				</div>
				<div class="panel-body">
					<div>
						<a class="btn btn-info" href="<?php echo Config::get('app.url').'admin/prod_edit/' ?>">Add Record</a>
					</div>
					@if (count($errors) > 0)
					<div class="alert alert-danger">
						<strong>Whoops!</strong> There were some problems with your input.<br>
						<br>
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li> @endforeach
						</ul>
					</div>
					@endif
					<div>
						<div>
							<table class="table">
								<tr>
									<th>Product Name</th>
									<th>Category Name</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
								<?php foreach ($result as $value){?>
								<tr>
									<td>{{{ $value->v_product_name }}}</td>
									<td>{{{ $value->v_category_name }}}</td>
									<td>{{{ $value->v_status }}}</td>
									<td><a class="btn btn-info"
										href="<?php echo Config::get('app.url').'admin/prod_edit/'.$value->b_id ?>">Edit</a>
										<a class="btn btn-info"
										href="<?php echo Config::get('app.url').'admin/prod_delete/'.$value->b_id ?>">Delete</a>
									</td>
								</tr>
								<?php }?>
								<tr><td colspan="4"><?php echo $result->render(); ?></td></tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script>
	var siteurl="<?php echo Config::get('app.url')?>";
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getPosts(page);
            }
        }
    });

    $(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            getPosts($(this).attr('href').split('page=')[1]);
            e.preventDefault();
        });
    });

    function getPosts(page) {
        $.ajax({
            url : siteurl+'admin/productlists/?page=' + page,
            dataType: 'html',
        }).done(function (data) {
            $('table.table').html(data);
            location.hash = page;
        }).fail(function () {
        	alert('Posts could not be loaded.');
        });
    }

    </script>
	
</div>
@endsection
