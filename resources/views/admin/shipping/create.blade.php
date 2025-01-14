@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
	<div class="container-fluid my-2">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Shipping Management</h1>
			</div>
			<div class="col-sm-6 text-right">
				<a href="{{ route('shipping.create') }}" class="btn btn-primary">Back</a>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		@include('admin.message')

		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form action="{{ route('shipping.store') }}" method="POST" id="shippingForm" name="shippingForm">
			@csrf
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
								<select name="country" id="country" class="form-control @error('country') is-invalid @enderror">
									<option value="">Select a Country</option>
									@if ($countries->isNotEmpty())
										@foreach ($countries as $country)
											<option value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected' : '' }}>
												{{ $country->name }}
											</option>
										@endforeach
										<option value="rest_of_world" {{ old('country') == 'rest_of_world' ? 'selected' : '' }}>
											Rest of the world
										</option>
									@endif
								</select>
								@error('country')
									<p class="invalid-feedback">{{ $message }}</p>
								@enderror
							</div>
						</div>

						<div class="col-md-4">
							<input type="text" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Amount" value="{{ old('amount') }}">
							@error('amount')
								<p class="invalid-feedback">{{ $message }}</p>
							@enderror
						</div>
                        <div class="col-md-4">
						 <div class="col-md-2 mt-2">
							<button type="submit" class="btn btn-primary">Create</button>
						</div>
                        </div>
					</div>
				</div>							
			</div>
		</form>
        <div class="card">
            <div class="card-body">
                <dic class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                               <tr>
                                  <th>ID</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                               </tr>
                                     @if ($shippingCharge->isNotEmpty()) <!-- Use $shippingCharge, as defined in the controller -->
                                    @foreach ($shippingCharge as $ShippingCharge) <!-- Iterate over $shippingCharge -->
                                        <tr>
                                            <td>{{ $ShippingCharge->id }}</td>
                                            <td>{{ ($ShippingCharge->country_id === 'rest_of_world') ? 'Rest of the world' : $ShippingCharge->name }}</td>
                                            <td>${{ $ShippingCharge->amount }}</td>
                                            <td>
                                            <a href="{{route('shipping.edit',$ShippingCharge->id)}}" class=" btn btn-primary">Edit</a>
                                            <a href="javascript:void(0);" onclick="deleteRecord({{ $ShippingCharge->id }})" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif


                        </table>
                    </div>
                </dic>
            </div>
        </div>
	</div>
</section>
@endsection

@section('customJs')
<script>
    // Submit handler for #shippingForm
    $("#shippingForm").submit(function (event) {
        event.preventDefault(); // Prevent default form submission
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: "{{ route('shipping.store') }}",
            type: 'POST',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);

                if (response.status) {
                    window.location.href = "{{ route('shipping.create') }}";
                } else {
                    var errors = response.errors;

                    // Handle validation errors
                    if (errors.country) {
                        $("#country").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.country);
                    } else {
                        $("#country").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }

                    if (errors.amount) {
                        $("#amount").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.amount);
                    } else {
                        $("#amount").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                }
            },
            error: function() {
                $("button[type=submit]").prop('disabled', false);
                console.log("Something went wrong");
            }
        });
    });

    // Delete handler
    function deleteRecord(id) {
        var url = '{{ route("shipping.delete", ":id") }}'.replace(':id', id); // Replace :id with the actual ID

        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // Ensure CSRF token is included
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.status) {
                        alert("Record deleted successfully!");
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert("Failed to delete the record!");
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText); // Log the error for debugging
                    alert("An error occurred while deleting the record.");
                }
            });
        }
    }
</script>
@endsection
