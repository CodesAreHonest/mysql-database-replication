<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletBonus extends Model
{
    use SoftDeletes;

    protected $table = 'wallet_bonus';

    protected $fillable = [
        'member_id',
        'transaction_type_id',
        'txcode',
        'credit',
        'debit'
    ];

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function transactionType()
    {
        return $this->hasOne(TransactionType::class);
    }
}
