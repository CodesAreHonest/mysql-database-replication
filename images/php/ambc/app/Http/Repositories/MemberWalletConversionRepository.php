<?php


namespace App\Http\Repositories;


use App\Models\MemberWalletConversion;
use Illuminate\Database\Eloquent\Builder;

class MemberWalletConversionRepository
{
    private MemberWalletConversion $memberWalletConversion;

    public function __construct(MemberWalletConversion $memberWalletConversion)
    {
        $this->memberWalletConversion = $memberWalletConversion;
    }

    /**
     * @param int    $memberId
     * @param string $convertWalletType
     * @param int    $senderTransactionId
     * @param int    $receiveTransactionId
     */
    public function create(int $memberId, string $convertWalletType, int $senderTransactionId, int $receiveTransactionId)
    {
        $attributes = [
            'member_id'           => $memberId,
            'usdt_transaction_id' => $receiveTransactionId
        ];

        if ($convertWalletType === "bonus") {
            $attributes['bonus_transaction_id'] = $senderTransactionId;
        }

        if ($convertWalletType === "roi") {
            $attributes['roi_transaction_id'] = $senderTransactionId;
        }

        $this->memberWalletConversion
            ->on("mysql::write")
            ->create($attributes);
    }
}
