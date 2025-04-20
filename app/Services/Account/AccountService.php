<?php

namespace App\Services\Account;

use App\Models\Account;

class AccountService
{
  public function get($accountId)
  {
    $account = Account::find($accountId);

    if (!$account) {
      throw new \Exception('Account not found');
    }

    return $account->balance;
  }

  public function update($accountId, $change)
  {
    $account = Account::find($accountId);

    if (!$account) {
      throw new \Exception('Account not found');
    }

    $account->balance += $change;
    $account->save();

    return $account->balance;
  }

  public function create($userId, $initialBalance)
  {
    $account = new Account();
    $account->user_id = $userId;
    $account->balance = $initialBalance;
    $account->save();

    return $account;
  }
}
