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
                                            <option value="{{ $country->id }}" {{ $shipping->country_id == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                        <option value="rest_of_world" {{ $shipping->country_id == 'rest_of_world' ? 'selected' : '' }}>
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
							<input value="{{ $shipping->amount }}" type="text" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Amount" value="{{ old('amount') }}">
							@error('amount')
								<p class="invalid-feedback">{{ $message }}</p>
							@enderror
						</div>
                        <div class="col-md-4">
						 <div class="col-md-2 mt-2">
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
                        </div>
					</div>
				</div>							
			</div>
		</form>
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
			url: "{{ route('shipping.update', $shipping->id) }}",
			type: 'put',
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
