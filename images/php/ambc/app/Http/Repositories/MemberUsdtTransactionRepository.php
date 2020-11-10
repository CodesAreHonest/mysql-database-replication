<?php


namespace App\Http\Repositories;


use App\Models\MemberUsdtTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MemberUsdtTransactionRepository
{
    private MemberUsdtTransaction $memberUsdtTransaction;

    public function __construct(MemberUsdtTransaction $memberUsdtTransaction)
    {
        $this->memberUsdtTransaction = $memberUsdtTransaction;
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

        return $this->memberUsdtTransaction
            ->setConnection("mysql::write")
            ->create($attributes);
    }

    private function generateTxCode()
    {
        return "usdt_" . md5(rand());
    }

    /**
     * @param int   $memberId
     * @param float $creditAmount
     * @param int   $transactionTypeId
     *
     * @return Builder|Model
     */
    public function credit(int $memberId, float $creditAmount, int $transactionTypeId)
    {
        $attributes = [
            'member_id'           => $memberId,
            'transaction_type_id' => $transactionTypeId,
            'txcode'              => $this->generateTxCode(),
            'debit'               => 0,
            'credit'              => $creditAmount
        ];

        return $this->memberUsdtTransaction
            ->setConnection("mysql::write")
            ->create($attributes);
    }
}
