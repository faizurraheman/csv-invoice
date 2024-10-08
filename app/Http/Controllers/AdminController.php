<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class AdminController extends Controller
{

    public function showImportForm()
    {
        $products = Product::all();
        return view('admin.import', compact('products'));
    }

    public function importCsv(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');

            $filename = time() . '_' . $file->getClientOriginalName();

            $path = $file->move(storage_path('app/csv'), $filename);

            if (!$path) {
                return redirect()->back()->with('error', 'File upload failed! Please try again.');
            }

            $fullPath = $path->getRealPath();
            if (!file_exists($fullPath)) {
                return redirect()->back()->with('error', "File does not exist at path: {$fullPath}");
            }

            $csvFile = fopen($fullPath, 'r');

            fgetcsv($csvFile);

            $currentPartType = '';
            $currentPartDescription = '';

            while (($row = fgetcsv($csvFile)) !== false) {
                if (!empty($row[0])) {
                    $currentPartType = $row[0];
                }

                if (!empty($row[1])) {
                    $currentPartDescription = $row[1];
                }

                $productData = [
                    'part_type' => $currentPartType,
                    'part_description' => $currentPartDescription,
                    'product_info' => $row[2] ?? null,
                    'color' => $row[3] ?? null,
                    'quantity' => (int) str_replace(',', '', $row[4] ?? 0),
                    'part_number' => trim($row[5] ?? ''),
                    'single_price' => (float) str_replace(['$', ' '], '', $row[6] ?? 0),
                    'bulk_price' => (float) str_replace(['$', ' '], '', $row[7] ?? 0),
                ];

                Product::updateOrCreate(
                    ['part_number' => $productData['part_number']], // Check for existing product by part number
                    $productData
                );
            }

            fclose($csvFile);
            return redirect()->back()->with('success', 'Products imported successfully!');
        }



        return redirect()->back()->with('error', 'File upload failed!');
    }
}
