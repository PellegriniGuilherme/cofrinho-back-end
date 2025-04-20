<?php

namespace App\Services\Auth;

use App\Helpers\CookieHelper;
use App\Models\User;
use App\Services\Account\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{

  public function __construct(private AccountService $accountService) {}
  public function register(Request $request): User
  {
    DB::beginTransaction();
    try {
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);

      $this->accountService->create($user->id, 0);

      DB::commit();
      return $user;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }

  public function forgotPassword(Request $request): array
  {
    $status = Password::sendResetLink(
      $request->only('email')
    );

    if (Password::RESET_LINK_SENT === $status) {
      return [
        'status' => 'success',
        'message' => 'Password reset link sent to your email.'
      ];
    } else {
      return [
        'status' => 'error',
        'message' => 'Failed to send password reset link.'
      ];
    }
  }

  public function resetPassword(Request $request): array
  {
    $status = Password::reset(
      $request->only('email', 'password', 'password_confirmation', 'token'),
      function ($user) use ($request) {
        $user->forceFill([
          'password' => Hash::make($request->password),
          'remember_token' => Str::random(60),
        ])->save();
      }
    );

    if (Password::PASSWORD_RESET === $status) {
      return [
        'status' => 'success',
        'message' => 'Password reset successfully.'
      ];
    } else {
      return [
        'status' => 'error',
        'message' => 'Failed to reset password.'
      ];
    }
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
