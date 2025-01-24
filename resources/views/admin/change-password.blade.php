@extends('admin.layouts.app')

     @section('content')
    
     <section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Change Password</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('categories.index')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
                     @include('admin.message')
                       <form action= "{{ route('admin.processChangePassword') }}" method="post" id="ChangePasswordForm" name="ChangePasswordForm">
						@csrf
						<div class="card">
                        <div class="card-body">								
								<div class="row">
									<div class="col-md-12">
										<div class="mb-3">
											<label for="name">Previous Password</label>
											<input type="password" name="previous_password" id="previous_password" class="form-control" placeholder="Previous Password">
	                                        <p></p>
										</div>
									</div>
									<div class="col-md-12">
										<div class="mb-3">
											<label for="name">New Password</label>
											<input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password">
	                                        <p></p>
										</div>
									</div>
                                    <div class="col-md-12">
										<div class="mb-3">
											<label for="name">Confirm Password</label>
											<input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
	                                        <p></p>
										</div>
									</div>
								</div>
							</div>							
						</div>
						<div class="pb-5 pt-3">
							<button type = "submit" class="btn btn-primary">Update</button>
						</div>
						</form>
					</div>
					<!-- /.card -->
				</section>
           @endsection
     @section('customJs')

     <script>
   $("#ChangePasswordForm").submit(function (event) {
      event.preventDefault();
      var element = $(this);
	  $("button[type = submit]").prop('disabled',true);

      $.ajax({
         url: "{{ route('admin.processChangePassword') }}", 
         type: 'post',
         data: element.serializeArray(),
         dataType: 'json',
         success: function(response){
			$("button[type = submit]").prop('disabled',false);
                  if (response["status"]==true){
					
					window.location.href="{{route('admin.showChangePasswordForm')}}";
				// 	$("#name").removeClass('is-invalid')
				// .siblings('p')
				// .removeClass('invalid-feedback').html("");
                
				// $("#slug").removeClass('is-invalid')
				// .siblings('p')
				// .removeClass('invalid-feedback').html("");

				  }else{
				var errors =response ['errors'];
			if (errors['previous_password']){
				$("#previous_password").addClass('is-invalid')
				.siblings('p')
				.addClass('is-invalid-feedback').html(errors['previous_password']);
			}else{
				$("#previous_password").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");
				
			}

			if (errors['new_password']){
				$("#new_password").addClass('is-invalid')
				.siblings('p')
				.addClass('is-invalid-feedback').html(errors['new_password']);
			}else{
				$("#new_password").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");
			}
            if (errors['confirm_password']){
				$("#confirm_password").addClass('is-invalid')
				.siblings('p')
				.addClass('is-invalid-feedback').html(errors['confirm_password']);
			}else{
				$("#confirm_password").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");
			}
	      }
         },
         error: function(jqXHR, exception){
            console.log("something went wrong");
         }
      });
   });

</script>
 @endsection
	 