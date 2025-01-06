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

    
}
