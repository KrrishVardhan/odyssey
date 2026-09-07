<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function store(Request $request, Team $team)
    {
        $this->authorize('updateSettings', $team);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $team->products()->create([
            ...$validated,
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
        ]);

        return back()->with('status', 'Product created.');
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('manage', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'about' => 'nullable|string|max:5000',
            'is_public' => 'boolean',
        ]);

        $product->update([
            ...$validated,
            'is_public' => $request->boolean('is_public'),
        ]);

        return back()->with('status', 'Product updated.');
    }
}
