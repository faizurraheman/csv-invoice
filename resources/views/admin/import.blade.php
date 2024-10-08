@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Import Products</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.import.csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="csv_file">Upload CSV File</label>
                    <input type="file" name="csv_file" class="form-control mt-2" id="csv_file" required>
                    @error('csv_file')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-2">Import</button>
            </form>
        </div>
    </div>

    <h2 class="mb-4">Product List</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered" id="product-table">
                <thead class="thead-dark">
                    <tr>
                        <th>Part Number</th>
                        <th>Part Description</th>
                        <th>Color</th>
                        <th>Quantity</th>
                        <th>Single Price</th>
                        <th>Bulk Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->part_number }}</td>
                            <td>{{ $product->part_description }}</td>
                            <td>{{ $product->color }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>${{ number_format($product->single_price, 2) }}</td>
                            <td>${{ number_format($product->bulk_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#product-table').DataTable();
    });
</script>
@endsection
