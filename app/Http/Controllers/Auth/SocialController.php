<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class SocialController extends Controller
{
    private const ALLOWED_PROVIDERS = ['google', 'github'];

    public function redirectToProvider(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, self::ALLOWED_PROVIDERS, true), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, self::ALLOWED_PROVIDERS, true), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable) {
            return redirect()
                ->route('login')
                ->with('error', 'Не удалось войти через '.Str::headline($provider).'.');
        }

        $providerId = (string) $socialUser->getId();
        $email = $socialUser->getEmail() ?: sprintf('%s_%s@oauth.local', $provider, $providerId);

        $user = User::query()
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        if (! $user) {
            $user = User::query()->where('email', $email)->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? ucfirst($provider).' User',
                'email' => $email,
                'password' => bcrypt(Str::random(32)),
                'provider' => $provider,
                'provider_id' => $providerId,
            ]);
        } else {
            $user->update([
                'name' => $user->name ?: ($socialUser->getName() ?? $socialUser->getNickname() ?? ucfirst($provider).' User'),
                'email' => $user->email ?: $email,
                'provider' => $provider,
                'provider_id' => $providerId,
            ]);
        }

        if (! $user->hasAnyRole(['admin', 'owner', 'user'])) {
            Role::findOrCreate('user');
            $user->assignRole('user');
        }

        Auth::login($user, true);
        $user->normalizePanelRole();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
