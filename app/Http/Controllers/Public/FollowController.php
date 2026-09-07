<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, Product $product)
    {
        abort_unless($product->is_public, 404);

        $user = $request->user();

        if ($product->followers()->where('user_id', $user->id)->exists()) {
            $product->followers()->detach($user->id);
        } else {
            $product->followers()->attach($user->id);
        }

        return back();
    }
}
