<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
{
    return view('edit');
}
public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'bio' => 'nullable|string',
    ]);

    // Handle file upload
    if ($request->hasFile('avatar_image')) {
        // Delete old image if exists
        if ($user->avatar_image && Storage::exists('public/' . $user->avatar_image)) {
            Storage::delete('public/' . $user->avatar_image);
        }

        $path = $request->file('avatar_image')->store('avatars', 'public');
        $user->avatar_image = $path;
    }

    $user->name = $request->name;
    $user->email = $request->email;
    $user->bio = $request->bio;
    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully.');
}
}
