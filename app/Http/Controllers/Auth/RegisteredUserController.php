<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            // 'role' => 'in:admin,provider,customer',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            // Name
            'name.required' => 'Please enter your name.',
            'name.max' => 'Name must not exceed 255 characters.',

            // Email
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'This email is already registered.',
            'email.lowercase' => 'Email must be in lowercase.',

            // Password
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',

            // Phone
            'phone.required' => 'Phone number is required.',
            'phone.max' => 'Phone number must not exceed 20 characters.',

            // Address
            'address.required' => 'Address is required.',
            'address.max' => 'Address must not exceed 255 characters.',

            // Avatar
            'avatar.image' => 'The avatar must be an image.',
            'avatar.mimes' => 'The avatar must be a file of type: jpg, jpeg, png.',
            'avatar.max' => 'The avatar must not be larger than 2MB.',
        ]);

        // dd($request->avatar);
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // dd($avatarPath);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'avatar' => $avatarPath,
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($user->isRole('customer')) {
            return redirect()->intended(route('portal.home'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
