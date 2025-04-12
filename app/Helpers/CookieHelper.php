<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as HttpFoundationCookie;

class CookieHelper
{
  public static function makeAuthCookie(string $value): HttpFoundationCookie
  {
    return Cookie::make(
      config('auth.cookie.name', 'auth_status'),
      $value,
      config('auth.cookie.ttl', 60),
      '/',
      config('auth.cookie.domain', null),
      config('auth.cookie.secure', false),
      true,
      false,
      config('auth.cookie.samesite', 'Lax')
    );
  }

  public static function forgetAuthCookie(): HttpFoundationCookie
  {
    return Cookie::forget(
      config('auth.cookie.name', 'auth_status'),
      '/',
      config('auth.cookie.domain', null)
    );
  }
}
