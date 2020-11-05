<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletBalance extends Model
{
    use SoftDeletes;

    protected string $table = 'wallet_balances';

    protected array $fillable = [
        'member_id',
        'roi',
        'bonus',
        'ambc',
        'usdt',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
