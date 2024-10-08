<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('invoiceItems.product')->get();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $products = Product::all();
        return view('invoices.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $invoice = Invoice::create([
            'customer_name' => $request->customer_name,
            'invoice_date' => $request->invoice_date,
            'total_amount' => 0,
        ]);

        $totalAmount = 0;

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $price = $item['price'] == 'bulk' ? $product->bulk_price : $product->single_price;
            $totalAmount += $price * $item['quantity'];

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $price,
            ]);
        }

        // Update total amount on the invoice
        $invoice->update(['total_amount' => $totalAmount]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');
    }

    public function edit(Invoice $invoice)
    {
        $products = Product::all();
        return view('invoices.edit', compact('invoice', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $invoice->update([
            'customer_name' => $request->customer_name,
            'invoice_date' => $request->invoice_date,
        ]);

        $invoice->invoiceItems()->delete();

        $totalAmount = 0;

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $price = $item['price'] == 'bulk' ? $product->bulk_price : $product->single_price;
            $totalAmount += $price * $item['quantity'];

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $price,
            ]);
        }

        // Update total amount on the invoice
        $invoice->update(['total_amount' => $totalAmount]);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $invoices = Invoice::where('customer_name', 'LIKE', "%{$search}%")
            ->orWhere('invoice_date', 'LIKE', "%{$search}%")
            ->orWhere('total_amount', 'LIKE', "%{$search}%")
            ->get();

        $output = '';
        foreach ($invoices as $invoice) {
            $output .= '<tr>
                        <td>' . $invoice->customer_name . '</td>
                        <td>' . $invoice->invoice_date . '</td>
                        <td>$' . number_format($invoice->total_amount, 2) . '</td>
                        <td>
                            <a href="' . route('invoices.edit', $invoice) . '" class="btn btn-warning btn-sm">Edit</a>
                            <form action="' . route('invoices.destroy', $invoice) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <a href="' . route('invoices.show', $invoice) . '" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>';
        }

        return response()->json($output);
    }
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }
}
