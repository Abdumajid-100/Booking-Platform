<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
class SocialController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
// обработка ошибок (например, редирект на страницу входа с ошибкой)
return redirect('/login')->with('error', 'Ошибка
входа через ' . $provider);
}
// ищем пользователя по provider_id или email
        $user = User::where('provider', $provider) ->where('provider_id', $socialUser->getId()) ->first();
        if (!$user) {
// можно попробовать найти по email и привязать
            $user = User::where('email', $socialUser->getEmail())->first();
        }
        if (!$user) {
// создаём нового пользователя
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser ->getNickname() ?? 'User',
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)), // пароль не обязателен
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                // при необходимости сохраняйте токены:
                //'access_token' => $socialUser->token,
]);
} else {
// если пользователь найден по email, привяжем провайдера
if (!$user->provider || !$user->provider_id) {
    $user->update([
        'provider' => $provider,
        'provider_id' => $socialUser->getId(),
    ]);
}
}
        Auth::login($user, true); // логин пользователя
        return redirect()->intended('/home');
    }
}
