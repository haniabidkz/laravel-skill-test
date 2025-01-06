<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $jsonData = public_path('products.json');
        $products = [];
        if (file_exists($jsonData)) {
            $products = json_decode(file_get_contents($jsonData), true);
        }

        return view('products.index', compact('products'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        // Add datetime and calculate total value
        $newEntry = [
            'name' => $data['name'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'datetime_submitted' => now()->toDateTimeString(),
            'total_value' => $data['quantity'] * $data['price'],
        ];

        // Save to JSON file
        $jsonData = public_path('products.json');
        $existingData = [];
        if (file_exists($jsonData)) {
            $existingData = json_decode(file_get_contents($jsonData), true);
        }
        $existingData[] = $newEntry;
        file_put_contents($jsonData, json_encode($existingData, JSON_PRETTY_PRINT));

    
        return response()->json(['success' => true, 'data' => $existingData]);
    }

    public function edit(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $jsonData = public_path('products.json');
        $existingData = [];

        if (file_exists($jsonData)) {
            $existingData = json_decode(file_get_contents($jsonData), true);
        }

        // Update the product data
        if (isset($existingData[$data['id']])) {
            $existingData[$data['id']] = [
                'name' => $data['name'],
                'quantity' => $data['quantity'],
                'price' => $data['price'],
                'datetime_submitted' => now()->toDateTimeString(),
                'total_value' => $data['quantity'] * $data['price'],
            ];

            file_put_contents($jsonData, json_encode($existingData, JSON_PRETTY_PRINT));
        }

        return response()->json(['success' => true, 'data' => $existingData]);
    }


    
}
