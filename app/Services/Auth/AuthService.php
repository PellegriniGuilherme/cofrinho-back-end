<?php

namespace App\Services\Auth;

use App\Helpers\CookieHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthService
{
  public function register(Request $request): User
  {
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    return $user;
  }

  public function login(Request $request): array | null
  {
    if (Auth::attempt($request->only('email', 'password'))) {
      $user = Auth::user();

      return [
        'user' => $user,
        'cookie' => CookieHelper::makeAuthCookie(1)
      ];
    }

    return null;
  }

  public function logout(): array
  {
    Auth::logout();

    return [
      'cookie' => CookieHelper::forgetAuthCookie()
    ];
  }

  public function user(): User | null
  {
    return Auth::user();
  }
}
