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
                        
						 <div class="col-md-2 mt-2">
							<button type="submit" class="btn btn-primary">Create</button>
						</div>
                        
					</div>
				</div>							
			</div>
		</form>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Country</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($shippings->isNotEmpty())
                            @foreach ($shippings as $shipping)
                                <tr>
                                    <td>{{ $shipping->country->name }}</td>
                                    <td>{{ $shipping->amount }}</td>
                                    <td>
                                        <a href="{{ route('shipping.edit', $shipping->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('shipping.destroy', $shipping->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">No data found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
	</div>
</section>
@endsection

@section('customJs')
<script>
	$("#shippingForm").submit(function (event) {
		event.preventDefault();
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

					if (errors.country) {
						$("#country").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.country);
					} else {
						$("#country").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
					}

					if (errors.amount) {
						$("#amount").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.amount);
					} else {
						$("#amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
					}
				}
			},
			error: function() {
				$("button[type=submit]").prop('disabled', false);
				console.log("Something went wrong");
			}
		});
	});
</script>
@endsection
