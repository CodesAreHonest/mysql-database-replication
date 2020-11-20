<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminBonusTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'admin_bonus_transaction';

    protected $fillable = [
        'member_bonus_transaction_id',
        'remarks'
    ];

    public function memberBonusTransaction()
    {
        return $this->hasOne(MemberBonusTransaction::class, 'member_bonus_transaction_id', 'id');
    }
}
