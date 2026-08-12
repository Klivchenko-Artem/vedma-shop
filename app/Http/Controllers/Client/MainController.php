<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Setting;
use Inertia\Inertia;

class MainController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'popularProducts' => Product::where('is_popular', true)
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
            'reviews' => Review::latest()->get(),
            'featureDescs' => Setting::getValue('home_feature_descs'),
        ]);
    }
}
