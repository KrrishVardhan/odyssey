<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ImageKitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function updateUsername(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $request->user()->update($validated);

        return back()->with('status', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        abort_if($request->user()->google_id, 403, 'Password login is disabled for Google accounts.');

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update(['password' => Hash::make($validated['password'])]);

        return back()->with('status', 'Password updated.');
    }

    public function updateAvatar(Request $request, ImageKitService $imageKit)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);

        $url = $imageKit->upload($request->file('avatar'), '/avatars');

        $request->user()->update(['avatar' => $url]);

        return back()->with('status', 'Avatar updated.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
