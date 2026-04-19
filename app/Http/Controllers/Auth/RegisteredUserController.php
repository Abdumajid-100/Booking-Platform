<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'account_type' => ['required', 'in:user,owner,business'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = $validated['account_type'] === 'business' ? 'owner' : $validated['account_type'];

        Role::findOrCreate('user');
        Role::findOrCreate('owner');

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->assignRole($role);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->to(route('dashboard', absolute: false));
    }
}
