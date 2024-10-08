@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">List Invoices</h1>

        <div class="mb-4">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary float-right">Add New Invoice</a>
            <input type="text" id="search" class="form-control mt-2" placeholder="Search Invoices..."
                aria-label="Search Invoices">
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Customer Name</th>
                        <th>Invoice Date</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="invoice-list">
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->customer_name }}</td>
                            <td>{{ $invoice->invoice_date }}</td>
                            <td>${{ number_format($invoice->total_amount, 2) }}</td>
                            <td>
                                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-info btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            $('#search').on('keyup', function() {
                let query = $(this).val();

                $.ajax({
                    url: "{{ route('invoices.search') }}",
                    method: "POST",
                    data: {
                        search: query
                    },
                    headers: {
                    'X-CSRF-TOKEN': csrfToken // Set the CSRF token in the headers
                },
                    success: function(data) {
                        $('#invoice-list').html(data);
                    }
                });
            });
        });
    </script>
@endsection
