<?php


namespace App\Http\Repositories;

use App\Models\WalletBalance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WalletBalanceRepository
{
    private WalletBalance $walletBalance;

    public function __construct(WalletBalance $walletBalance)
    {
        $this->walletBalance = $walletBalance;
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

        $walletBalance =  $this->walletBalance
            ->setConnection("mysql::read")
            ->find($memberId, $walletTypes);

        return $walletBalance;
    }

    /**
     * @param int   $memberId
     * @param float $amount
     */
    public function debitRoi(int $memberId, float $amount)
    {
        $positiveAmount = abs($amount);

        $this->walletBalance
            ->setConnection("mysql::write")
            ->where('member_id', $memberId)
            ->increment('roi', $positiveAmount);
    }

    /**
     * @param int   $memberId
     * @param float $amount
     */
    public function creditBonus(int $memberId, float $amount)
    {

        $positiveAmount = abs($amount);

        $this->walletBalance
            ->setConnection("mysql::write")
            ->where('member_id', $memberId)
            ->decrement('bonus', $positiveAmount);
    }

    /**
     * @param int   $memberId
     * @param float $amount
     */
    public function debitBonus(int $memberId, float $amount)
    {
        $positiveAmount = abs($amount);

        $this->walletBalance
            ->setConnection("mysql::write")
            ->where('member_id', $memberId)
            ->increment('bonus', $positiveAmount);
    }

    /**
     * @param int   $memberId
     * @param float $amount
     */
    public function debitUsdt(int $memberId, float $amount)
    {
        $positiveAmount = abs($amount);

        $this->walletBalance
            ->setConnection("mysql::write")
            ->where('member_id', $memberId)
            ->increment('usdt', $positiveAmount);
    }

    /**
     * @param int   $memberId
     * @param float $amount
     */
    public function creditUsdt(int $memberId, float $amount)
    {
        $positiveAmount = abs($amount);

        $this->walletBalance
            ->setConnection("mysql::write")
            ->where('member_id', $memberId)
            ->decrement('usdt', $positiveAmount);
    }

    /**
     * @param int   $memberId
     * @param float $amount
     */
    public function creditRoi(int $memberId, float $amount)
    {
        $positiveAmount = abs($amount);

        $this->walletBalance
            ->setConnection("mysql::write")
            ->where('member_id', $memberId)
            ->decrement('usdt', $positiveAmount);
    }

    /**
     * @param int    $memberId
     * @param string $targetWallet
     * @param float  $convertAmount
     */
    public function convert(int $memberId, string $targetWallet, float $convertAmount)
    {
        $destinationWallet = 'usdt';
        $memberWallet      = $this->walletBalance
            ->setConnection("mysql::read")
            ->where('member_id', $memberId)
            ->first();

        $targetBalance      = $memberWallet[$targetWallet] - $convertAmount;
        $destinationBalance = $memberWallet[$destinationWallet] + $convertAmount;

        $this->walletBalance
            ->setConnection("mysql::write")
            ->where('member_id', $memberId)
            ->update([
                $destinationWallet => $destinationBalance,
                $targetWallet      => $targetBalance
            ]);
    }

    public function isSufficient(int $memberId, string $walletType, float $deductionAmount)
    {
        $lowerWalletType         = strtolower($walletType);
        $positiveDeductionAmount = abs($deductionAmount);

        $isSufficient = $this->walletBalance
            ->setConnection("mysql::read")
            ->where('member_id', $memberId)
            ->where($lowerWalletType, '>=', $positiveDeductionAmount)
            ->exists();

        return $isSufficient;
    }
}
