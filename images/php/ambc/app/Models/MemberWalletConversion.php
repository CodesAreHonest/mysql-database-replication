<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberWalletConversion extends Model
{
    protected $table = 'member_wallet_conversion';

    protected $fillable = [
        'member_id',
        'bonus_transaction_id',
        'roi_transaction_id',
        'usdt_transaction_id'
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'member_id', 'id');
    }

    public function senderBonusTransaction()
    {
        return $this->hasOne(MemberBonusTransaction::class, 'sender_bonus_transaction_id', 'id');
    }

    public function senderRoiTransaction()
    {
        return $this->hasOne(MemberRoiTransaction::class, 'sender_roi_transaction_id', 'id');
    }

    public function receiverTransaction()
    {
        return $this->hasOne(MemberUsdtTransaction::class, 'receiver_usdt_transaction_id', 'id');
    }
}
