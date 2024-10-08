@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Create Invoice</h1>

    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" name="customer_name" id="customer_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="invoice_date" class="form-label">Invoice Date</label>
            <input type="date" name="invoice_date" id="invoice_date" class="form-control" required>
        </div>

        <h4 class="mb-3">Invoice Items</h4>
        <div id="items-container">
            <div class="invoice-item mb-4 border p-3 rounded">
                <div class="mb-3">
                    <label for="items[0][product_id]" class="form-label">Product</label>
                    <select name="items[0][product_id]" class="form-select product-select" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-bulk-price="{{ $product->bulk_price }}" data-single-price="{{ $product->single_price }}">{{ $product->part_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="items[0][quantity]" class="form-label">Quantity</label>
                    <input type="number" name="items[0][quantity]" class="form-control quantity" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="items[0][price]" class="form-label">Price Type</label>
                    <select name="items[0][price]" class="form-select price-type">
                        <option value="single">Single Price</option>
                        <option value="bulk">Bulk Price</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" id="add-item">Add Another Item</button>

        <button type="submit" class="btn btn-primary">Create Invoice</button>
    </form>
</div>

<script>
    let itemCount = 1;

    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newItem = `
            <div class="invoice-item mb-4 border p-3 rounded">
                <div class="mb-3">
                    <label for="items[${itemCount}][product_id]" class="form-label">Product</label>
                    <select name="items[${itemCount}][product_id]" class="form-select product-select" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-bulk-price="{{ $product->bulk_price }}" data-single-price="{{ $product->single_price }}">{{ $product->part_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="items[${itemCount}][quantity]" class="form-label">Quantity</label>
                    <input type="number" name="items[${itemCount}][quantity]" class="form-control quantity" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="items[${itemCount}][price]" class="form-label">Price Type</label>
                    <select name="items[${itemCount}][price]" class="form-select price-type">
                        <option value="single">Single Price</option>
                        <option value="bulk">Bulk Price</option>
                    </select>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newItem);
        itemCount++;
    });
</script>
@endsection
