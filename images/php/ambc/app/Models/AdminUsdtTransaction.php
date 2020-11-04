<?php

namespace App\Models;

use App\Model\MemberUsdtTransaction;
use Illuminate\Database\Eloquent\Model;

class AdminUsdtTransaction extends Model
{
    protected $table = 'admin_usdt_transaction';

    protected $fillable = [
        'member_usdt_transaction_id',
        'remarks'
    ];

    public function memberUsdtTransaction()
    {
        return $this->hasOne(MemberUsdtTransaction::class, 'member_usdt_transaction_id', 'id');
    }
}
