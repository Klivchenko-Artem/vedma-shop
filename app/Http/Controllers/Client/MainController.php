<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Inertia\Inertia;

class MainController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'popularProducts' => Product::where('is_popular', true)
                ->with('category')
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
            'reviews' => Review::latest()->get(),
        ]);
    }
}
