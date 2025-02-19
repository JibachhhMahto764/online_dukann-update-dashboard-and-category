@extends('admin.layouts.app')

     @section('content')
    
     <section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Discount Coupon </h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('coupons.index')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
                       <form action= "{{ route('categories.store') }}" method="post" id="discountForm" name="discountForm">
						@csrf
						<div class="card">
							<div class="card-body">								
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3">
											<label for="code">Code</label>
											<input type="text" name="code" id="code" class="form-control" placeholder="Coupon Code">
	                                        <p></p>
										</div>
									</div> 
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Name</label>
											<input type="text"  name="name" id="name" class="form-control" placeholder="Coupon Code Name">	
											<p></p>
										</div>
									</div>	
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="max_uses">Max Uses</label>
											<input type="number"  name="max_uses" id="max_user" class="form-control" placeholder="Max_Uses">	
											<p></p>
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="max_uses_user">Max_Uses_User</label>
											<input type="text"  name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max_Uses_User">	
											<p></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="type">Type</label>
											<select name="type" id="type" class="form-control">
                                             <option value="percentage">Percent</option>
                                             <option value="fixed">Fixed</option>
											</select>	
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="discount_amount">Discount Amount</label>
											<input type="number"  name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount">	
											<p></p>
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="min_amount">Minimun Amount</label>
											<input type="text"  name="min_amount" id="min_amount" class="form-control" placeholder="Minimun Amount">	
											<p></p>
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="status">Status</label>
											<select name="status" id="status" class="form-control">
                                             <option value="1">Active</option>
                                             <option value="0">Block</option>
											</select>	
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="starts_at">Starts At</label>
											<input autocomplete="off" type="text"  name="starts_at" id="starts_at" class="form-control" placeholder="Starts At">	
											<p></p>
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
											<label for="ends_at">Ends At</label>
											<input  autocomplete="off" type="text"  name="ends_at" id="ends_at" class="form-control" placeholder="Ends At">	
											<p></p>
										</div>
									</div>
                                    <div class="col-md-6">
										<div class="mb-3">
                                        <label for="description">Description</label>
											<textarea  name="description" id="description" class="form-control" cols="30" rows="5" placeholder="Description"></textarea>
											<p></p>
										</div>
									</div>

								</div>
							</div>							
						</div>
						<div class="pb-5 pt-3">
							<button type = "submit" class="btn btn-primary">Create</button>
							<a href="{{route('coupons.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
						</div>
						</form>
					</div>
					<!-- /.card -->
				</section>
           @endsection
     @section('customJs')

     <script>
         $(document).ready(function(){
            $('#starts_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
            $('#ends_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
        });
        
   $("#discountForm").submit(function (event) {
      event.preventDefault();
      var element = $(this);
	  $("button[type = submit]").prop('disabled',true);
      $.ajax({
         url: "{{ route('coupons.store') }}", // 
         type: 'post',
         data: element.serializeArray(),
         dataType: 'json',
         success: function(response){
			$("button[type = submit]").prop('disabled',false);
                  if (response["status"]==true){
					
					window.location.href="{{route('coupons.index')}}";
					$("#code").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");
                
				$("#discount_amount").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");

                $("#starts_at").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");

                $("#ends_at").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");

			}else{
			var errors =response ['errors']  || {};
			if (errors['code']){
				$("#code").addClass('is-invalid')
				.siblings('p')
				.addClass('is-invalid-feedback').html(errors['code']);
			}else{
				$("#code").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");
				
			}

			if (errors['discount_amount']){
				$("#discount_amount").addClass('is-invalid')
				.siblings('p')
				.addClass('is-invalid-feedback').html(errors['discount_amount']);
			}else{
				$("#discount_amount").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");
			}


            if (errors['starts_at']){
				$("#starts_at").addClass('is-invalid')
				.siblings('p')
				.addClass('is-invalid-feedback').html(errors['starts_at']);
			}else{
				$("#starts_at").removeClass('is-invalid')
				.siblings('p')
				.removeClass('invalid-feedback').html("");
			}
            if (errors['ends_at']){
				$("#ends_at").addClass('is-invalid')
				.siblings('p')
				.addClass('is-invalid-feedback').html(errors['ends_at']);
			}else{
				$("ends_at").removeClass('is-invalid')
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
	