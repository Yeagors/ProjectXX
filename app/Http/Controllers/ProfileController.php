<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
    public function uploadAvatar(Request $request)
    {
        // Временное сохранение файла (Dropzone требует ответ)
        if ($request->hasFile('avatar')) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function saveAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048'
        ]);

        $user = Auth::user();

        // Удаляем старый аватар, если он есть
        if ($user->profile_photo_path) {
            Storage::delete($user->profile_photo_path);
        }

        // Сохраняем новый аватар
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->profile_photo_path = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'avatar_url' => asset('storage/' . $path)
        ]);
    }
}
