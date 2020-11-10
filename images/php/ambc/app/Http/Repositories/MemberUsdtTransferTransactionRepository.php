<?php


namespace App\Http\Repositories;


use App\Models\MemberUsdtTransferTransaction;
use Illuminate\Database\Eloquent\Builder;

class MemberUsdtTransferTransactionRepository
{
    private MemberUsdtTransferTransaction $memberUsdtTransferTransation;

    public function __construct(MemberUsdtTransferTransaction $memberUsdtTransferTransaction)
    {
        $this->memberUsdtTransferTransation = $memberUsdtTransferTransaction;
    }

    public function create(
        int $senderMemberId,
        int $receiverMemberId,
        int $senderTransactionId,
        int $receiverTransactionId,
        ?int $senderFeeDeductionId = null,
        ?int $systemReceiveFeeId = null
    ) {
        $attributes = [
            'sender_member_id'                    => $senderMemberId,
            'receiver_member_id'                  => $receiverMemberId,
            'sender_transaction_id'               => $senderTransactionId,
            'receiver_transaction_id'             => $receiverTransactionId,
            'sender_fee_deduction_transaction_id' => $senderFeeDeductionId,
            'system_receive_fee_transaction_id'   => $systemReceiveFeeId
        ];

        $this->memberUsdtTransferTransation
            ->on("mysql::write")
            ->create($attributes);
    }
}
