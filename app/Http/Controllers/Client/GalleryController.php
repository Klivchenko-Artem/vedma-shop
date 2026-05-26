<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        return Inertia::render('Gallery', [
            'images' => Gallery::orderBy('sort_order')->get(),
        ]);
    }
}
