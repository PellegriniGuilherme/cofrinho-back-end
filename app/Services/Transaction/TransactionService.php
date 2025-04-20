<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Account\AccountService;
use Illuminate\Http\Request;

class TransactionService
{

  public function __construct(private AccountService $accountService) {}

  public function index(User $user)
  {
    return Transaction::where('account_id', $user->account->id)
      ->with(['category', 'account'])
      ->orderBy('happened_at', 'desc')
      ->paginate(10);
  }

  public function store(User $user, Request $request): Transaction
  {
    $delta     = $request->amount;
    $absAmount = abs($delta);
    $accountId = $user->account->id;

    $transaction = Transaction::create([
      'account_id'   => $accountId,
      'category_id'  => $request->category_id,
      'type'         => $request->type,
      'amount'       => $absAmount,
      'description'  => $request->description,
      'happened_at'  => $request->happened_at,
    ]);

    $this->accountService->update($transaction->account_id, $delta);

    return $transaction;
  }

  public function show(User $user, Transaction $transaction)
  {
    if ($transaction->user_id !== $user->id) {
      throw new \Exception('Transaction not found');
    }

    return $transaction;
  }

  public function update(User $user, Transaction $transaction, Request $request): Transaction
  {
    if ($transaction->account->id !== $user->account->id) {
      throw new \Exception('Transaction not found');
    }

    $oldDelta = $transaction->type === 'income'
      ? +$transaction->amount
      : -$transaction->amount;
    $this->accountService->update($transaction->account_id, -$oldDelta);

    $newDelta    = $request->amount;
    $newAbsValue = abs($newDelta);

    $transaction->fill([
      'category_id' => $request->category_id,
      'type'        => $request->type,
      'amount'      => $newAbsValue,
      'description' => $request->description,
      'happened_at' => $request->happened_at,
    ])->save();

    $this->accountService->update($transaction->account_id, $newDelta);

    return $transaction;
  }

  public function destroy(User $user, Transaction $transaction): void
  {
    if ($transaction->account->id !== $user->account->id) {
      throw new \Exception('Transaction not found');
    }

    $oldDelta = $transaction->type === 'income'
      ? +$transaction->amount
      : -$transaction->amount;

    $this->accountService->update($transaction->account_id, -$oldDelta);
    $transaction->delete();
  }

  private function getTransactionSummaryByCategory(User $user, string $type): array
  {
    return Transaction::selectRaw('
          categories.name as category,
          categories.color as category_color,
          SUM(transactions.amount) as total
      ')
      ->join('categories', 'transactions.category_id', '=', 'categories.id')
      ->where('transactions.account_id', $user->account->id)
      ->where('transactions.type', $type)
      ->whereBetween('transactions.happened_at', [now()->startOfMonth(), now()->endOfMonth()])
      ->groupBy('transactions.category_id', 'categories.name', 'categories.color')
      ->orderByDesc('total')
      ->get()
      ->toArray();
  }

  public function summary(User $user): array
  {
    $accountId = $user->account->id;
    $startDate = now()->startOfMonth();
    $endDate = now()->endOfMonth();

    $income = Transaction::where('account_id', $accountId)
      ->whereBetween('happened_at', [$startDate, $endDate])
      ->where('type', 'income')
      ->sum('amount');

    $expense = Transaction::where('account_id', $accountId)
      ->whereBetween('happened_at', [$startDate, $endDate])
      ->where('type', 'expense')
      ->sum('amount');

    return [
      'amount'  => (float) round($income - $expense, 2),
      'income'  => (float) $income,
      'expense' => (float) $expense,
      'dashboard' => [
        'income' => $this->getTransactionSummaryByCategory($user, 'income'),
        'expense' => $this->getTransactionSummaryByCategory($user, 'expense'),
      ]
    ];
  }
}
