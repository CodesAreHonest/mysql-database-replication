<?php


namespace App\Http\Repositories;

use App\Models\WalletBalance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class WalletBalanceRepository
{
    private Builder $walletBalance;

    public function __construct(WalletBalance $walletBalance)
    {
        $this->walletBalance = $walletBalance->query();
    }

    /**
     * @param int $memberId
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function find(int $memberId)
    {
        $walletTypes = [
            'roi',
            'bonus',
            'ambc',
            'usdt'
        ];

        return $this->walletBalance->find($memberId, $walletTypes);
    }

    /**
     * @param int   $memberId
     * @param float $amount
     */
    public function debitRoi(int $memberId, float $amount)
    {
        $positiveAmount = abs($amount);

        $this->walletBalance
            ->where('member_id', $memberId)
            ->increment('roi', $positiveAmount);
    }

    /**
     * @param int   $memberId
     * @param float $amount
     */
    public function debitBonus(int $memberId, float $amount)
    {
        $positiveAmount = abs($amount);

        $this->walletBalance
            ->where('member_id', $memberId)
            ->increment('bonus', $positiveAmount);
    }

}
