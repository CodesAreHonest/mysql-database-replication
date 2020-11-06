<?php


namespace App\Http\Repositories;


use App\Models\MemberBonusTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MemberBonusTransactionRepository
{
    private Builder $memberBonusTransaction;

    public function __construct(MemberBonusTransaction $memberBonusTransaction)
    {
        $this->memberBonusTransaction = $memberBonusTransaction->query();
    }

    /**
     * @param int   $memberId
     * @param float $debitAmount
     * @param int   $transactionTypeId
     *
     * @return Builder|Model
     */
    public function debit(int $memberId, float $debitAmount, int $transactionTypeId)
    {
        $attributes = [
            'member_id'           => $memberId,
            'transaction_type_id' => $transactionTypeId,
            'txcode'              => $this->generateTxCode(),
            'debit'               => $debitAmount,
            'credit'              => 0
        ];

        return $this->memberBonusTransaction->create($attributes);
    }

    private function generateTxCode()
    {
        return "bonus_" . md5(rand());
    }
}
