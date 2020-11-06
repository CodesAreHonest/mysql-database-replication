<?php


namespace App\Http\Repositories;


use App\Models\MemberUsdtTransaction;
use Illuminate\Database\Eloquent\Builder;

class MemberUsdtTransactionRepository
{
    private Builder $memberUsdtTransaction;

    public function __construct(MemberUsdtTransaction $memberUsdtTransaction)
    {
        $this->memberUsdtTransaction = $memberUsdtTransaction->query();
    }

    public function debit(int $memberId, float $debitAmount, int $transactionTypeId)
    {
        $attributes = [
            'member_id'           => $memberId,
            'transaction_type_id' => $transactionTypeId,
            'txcode'              => $this->generateTxCode(),
            'debit'               => $debitAmount,
            'credit'              => 0
        ];

        return $this->memberUsdtTransaction->create($attributes);
    }

    private function generateTxCode()
    {
        return "usdt_" . md5(rand());
    }
}
