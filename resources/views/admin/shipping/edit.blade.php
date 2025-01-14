@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Shipping</h1>
    <form action="{{ route('admin.shipping.update', $shipping->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Shipping Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $shipping->name }}" required>
        </div>

        <div class="form-group">
            <label for="cost">Shipping Cost</label>
            <input type="number" class="form-control" id="cost" name="cost" value="{{ $shipping->cost }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Shipping</button>
    </form>
</div>
@endsection