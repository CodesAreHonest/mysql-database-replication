<?php


namespace App\Http\Repositories;


use App\Models\MemberRoiTransaction;
use Illuminate\Database\Eloquent\Builder;

class MemberRoiTransactionRepository
{
    private Builder $memberRoiTransaction;

    public function __construct(MemberRoiTransaction $memberRoiTransaction)
    {
        $this->memberRoiTransaction = $memberRoiTransaction->query();
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

        return $this->memberRoiTransaction->create($attributes);
    }

    private function generateTxCode()
    {
        return "roi_" . md5(rand());
    }

    public function credit(int $memberId, float $creditAmount, int $transactionTypeId)
    {
        $positiveCreditAmount = abs($creditAmount);

        $attributes = [
            'member_id'           => $memberId,
            'transaction_type_id' => $transactionTypeId,
            'txcode'              => $this->generateTxCode(),
            'debit'               => 0,
            'credit'              => $positiveCreditAmount
        ];

        return $this->memberRoiTransaction->create($attributes);
    }
}
