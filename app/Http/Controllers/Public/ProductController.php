<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_public', true)->with('team')->orderBy('name')->get();

        return view('public.products.index', ['products' => $products]);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_public, 404);

        $isFollowing = auth()->check() && $product->followers()->where('user_id', auth()->id())->exists();

        $stats = [
            'requests' => $product->submissions()->count(),
            'planned' => $product->submissions()->where('status', 'approved')->count(),
            'shipped' => $product->submissions()->where('status', 'resolved')->count(),
        ];

        return view('public.products.show', [
            'product' => $product,
            'isFollowing' => $isFollowing,
            'followerCount' => $product->followers()->count(),
            'stats' => $stats,
        ]);
    }
}
