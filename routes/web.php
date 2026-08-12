<?php

use App\Http\Controllers\Client\MainController;
use App\Http\Controllers\Client\CatalogController;
use App\Http\Controllers\Client\GalleryController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\SitemapController;
use Illuminate\Support\Facades\Route;

// Клиентские маршруты
Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('product.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// API заказа
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
