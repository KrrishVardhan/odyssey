<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserStatusController extends Controller
{
    public function update(Request $request)
    {
        $request->user()->update([
            'is_active' => !$request->user()->is_active,]);
        return redirect()->back();
    }
}
