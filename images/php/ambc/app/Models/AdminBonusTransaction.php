<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminBonusTransaction extends Model
{
    use SoftDeletes;

    protected string $table = 'admin_bonus_transaction';

    protected array $fillable = [
        'member_bonus_transaction_id',
        'remarks'
    ];

    public function memberBonusTransaction()
    {
        return $this->hasOne(MemberBonusTransaction::class, 'member_bonus_transaction_id', 'id');
    }
}
