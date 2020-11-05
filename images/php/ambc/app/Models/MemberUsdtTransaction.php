<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberUsdtTransaction extends Model
{
    use SoftDeletes;

    protected string $table = 'member_usdt_transaction';

    protected array $fillable = [
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
