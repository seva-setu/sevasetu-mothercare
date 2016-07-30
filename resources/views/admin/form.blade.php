@extends('app')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Product Detail</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif	
					<form class="form-horizontal" role="form" method="POST" action="<?php echo '{{ url() }}/admin/'.$action; ?>">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="form-group">
							<label class="col-md-4 control-label">Product Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="txtProductname"  name="txtProductname" value="<?php echo $result->v_product_name; ?>" required >
								<input type="hidden" id="hdnId" name="hdnId" value="<?php echo $result->b_id; ?>" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Model Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="txtModelname" value="<?php echo $result->v_category_name; ?>" required >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Product Description</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="txtDescription" required value="<?php echo $result->t_description; ?>"  >
							</div>
						</div>
						
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Submit
						<div class="form-group">
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
