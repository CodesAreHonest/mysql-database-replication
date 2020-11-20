<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberUsdtTransferTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'member_usdt_transfer_transaction';

    protected $fillable = [
        'sender_member_id',
        'receiver_member_id',
        'sender_transaction_id',
        'receiver_transaction_id',
        'sender_fee_deduction_transaction_id',
        'system_receive_fee_transaction_id'
    ];

    public function sender()
    {
        return $this->hasOne(Member::class, 'sender_member_id', 'id');
    }

    public function receiver()
    {
        return $this->hasOne(Member::class, 'receiver_member_id', 'id');
    }

    public function senderTransaction()
    {
        return $this->hasOne(MemberUsdtTransaction::class, 'sender_transaction_id', 'id');
    }

    public function receiverTransaction()
    {
        return $this->hasOne(MemberUsdtTransaction::class, 'receiver_transaction_id', 'id');
    }

    public function senderFeeDeductionTransation()
    {
        return $this->hasOne(MemberUsdtTransaction::class, 'sender_fee_deduction_transaction_id', 'id');
    }

    public function systemReceiveFeeTransaction()
    {
        return $this->hasOne(MemberUsdtTransaction::class, 'system_receive_fee_transaction_id', 'id');
    }
}
