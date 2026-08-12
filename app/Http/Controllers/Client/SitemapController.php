<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::orderBy('updated_at', 'desc')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $xml .= $this->url('/', '1.0', 'weekly');
        $xml .= $this->url('/catalog', '0.9', 'daily');
        $xml .= $this->url('/gallery', '0.8', 'weekly');

        foreach ($products as $product) {
            $xml .= $this->url(
                "/catalog/{$product->slug}",
                '0.7',
                'weekly',
                $product->updated_at->toW3cString()
            );
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    private function url(string $path, string $priority, string $changefreq, ?string $lastmod = null): string
    {
        $loc = url($path);
        $xml = "<url><loc>{$loc}</loc><priority>{$priority}</priority><changefreq>{$changefreq}</changefreq>";
        if ($lastmod) {
            $xml .= "<lastmod>{$lastmod}</lastmod>";
        }
        $xml .= '</url>';
        return $xml;
    }
}
