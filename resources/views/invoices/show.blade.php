
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Invoice Details</h1>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Customer Name: {{ $invoice->customer_name }}</h2>
            <p class="card-text"><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('F j, Y') }}</p>
            <p class="card-text"><strong>Total Amount:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>

            <h3 class="mt-4">Items:</h3>
            <ul class="list-group">
                @foreach($invoice->invoiceItems as $item)
                    <li class="list-group-item">
                        {{ $item->product->part_number }} - Quantity: {{ $item->quantity }} - Price: ${{ number_format($item->price, 2) }}
                    </li>
                @endforeach
            </ul>

            <div class="mt-4">
                <a href="{{ route('invoices.index') }}" class="btn btn-primary">Back to Invoices</a>
                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">Edit Invoice</a>
                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Invoice</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
