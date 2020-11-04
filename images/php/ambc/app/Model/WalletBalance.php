<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletBalance extends Model
{
    use SoftDeletes;

    protected $table = 'wallet_balances';

    protected $fillable = [
        'member_id',
        'roi',
        'bonus',
        'ambc',
        'usdt',
    ];

    public function member()
    {
        return $this->hasOne(Member::class);
    }
}
