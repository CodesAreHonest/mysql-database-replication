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
     *
     * @references
     * -> Filter: (wallet_balances.deleted_at is null)  (cost=0.26 rows=0) (actual time=0.061..0.064 rows=1 loops=1)
     * -> Index lookup on wallet_balances using wallet_balances_member_id_foreign (member_id=2)  (cost=0.26 rows=1) (actual time=0.060..0.062 rows=1 loops=1)
     *
     * @raw
     * select roi, ambc, bonus, usdt from wallet_balances where member_id = 2 and deleted_at is null;
     */
    public function find(int $memberId)
    {
        $walletTypes = [
            'roi',
            'bonus',
            'ambc',
            'usdt'
        ];

        return $this->walletBalance
            ->setConnection("mysql::read")
            ->find($memberId, $walletTypes);
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
            ->where('member_id', $memberId);

        dd($memberWallet->toSql());


        // ->first();

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

    /**
     * @param int $memberId
     * @param string $walletType
     * @param float $deductionAmount
     *
     * @return boolean
     *
     * @see
     * -> Filter: ((wallet_balances.bonus >= 1.00) and (wallet_balances.deleted_at is null))  (cost=0.26 rows=0) (actual time=0.052..0.056 rows=1 loops=1)
     * -> Index lookup on wallet_balances using wallet_balances_member_id_foreign (member_id=11)  (cost=0.26 rows=1) (actual time=0.047..0.051 rows=1 loops=1)
     *
     * @query
     * select `roi`, `bonus`, `ambc`, `usdt` from `wallet_balances` where `member_id` = 11 and `bonus` >= 1 and
     * `wallet_balances`.`deleted_at` is null
     */
    public function isSufficient(int $memberId, string $walletType, float $deductionAmount)
    {
        $lowerWalletType         = strtolower($walletType);
        $positiveDeductionAmount = abs($deductionAmount);

        $isSufficient = $this->walletBalance
            ->setConnection("mysql::read")
            ->select(['roi', 'bonus', 'ambc', 'usdt'])
            ->where('member_id', $memberId)
            ->where($lowerWalletType, '>=', $positiveDeductionAmount)
            ->exists();

        return $isSufficient;
    }
}
