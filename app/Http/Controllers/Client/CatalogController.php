<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'default');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('sort_order'),
        };

        return Inertia::render('Catalog', [
            'products' => $query->get(),
            'filters' => $request->only(['sort', 'min_price', 'max_price']),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return Inertia::render('ProductDetail', [
            'product' => $product,
        ]);
    }
}
